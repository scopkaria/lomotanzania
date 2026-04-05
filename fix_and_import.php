<?php
/**
 * Fix SQLite-to-MySQL compatibility issues and import the SQL dump.
 * Main issue: FK columns are BIGINT but PKs are BIGINT UNSIGNED — MySQL requires exact type match.
 */

$sqlFile = __DIR__ . '/database/database_fixed.sql';
$sql = file_get_contents($sqlFile);

if (!$sql) {
    die("Could not read SQL file.\n");
}

// Fix: For each CREATE TABLE, find foreign key constraints and ensure
// the referenced columns in the same table are BIGINT UNSIGNED
$sql = preg_replace_callback(
    '/CREATE TABLE `[^`]+` \((.+?)\) ENGINE=/s',
    function ($match) {
        $body = $match[1];

        // Find all foreign key column names
        preg_match_all('/foreign key\(`([^`]+)`\)/', $body, $fkMatches);
        $fkColumns = $fkMatches[1] ?? [];

        foreach ($fkColumns as $col) {
            // Replace `column_name` BIGINT not null → `column_name` BIGINT UNSIGNED not null
            $body = preg_replace(
                '/`' . preg_quote($col, '/') . '` BIGINT not null/',
                '`' . $col . '` BIGINT UNSIGNED not null',
                $body
            );
            // Also handle nullable FK columns: `column_name` BIGINT, → `column_name` BIGINT UNSIGNED,
            $body = preg_replace(
                '/`' . preg_quote($col, '/') . '` BIGINT,/',
                '`' . $col . '` BIGINT UNSIGNED,',
                $body
            );
            // Handle `column_name` BIGINT not null default
            $body = preg_replace(
                '/`' . preg_quote($col, '/') . '` BIGINT not null default/',
                '`' . $col . '` BIGINT UNSIGNED not null default',
                $body
            );
        }

        return str_replace($match[1], $body, $match[0]);
    },
    $sql
);

// Write fixed SQL to temp file
$fixedFile = __DIR__ . '/database/database_mysql_ready.sql';
file_put_contents($fixedFile, $sql);
echo "Fixed SQL written to: $fixedFile\n";

// Now import via MySQL CLI
$mysqlBin = 'C:\\wamp64\\bin\\mysql\\mysql9.1.0\\bin\\mysql.exe';
$cmd = '"' . $mysqlBin . '" -u root lomo < "' . $fixedFile . '"';

// Use proc_open for proper stdin redirection
$descriptors = [
    0 => ['file', $fixedFile, 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open(
    [$mysqlBin, '-u', 'root', 'lomo'],
    $descriptors,
    $pipes
);

if (is_resource($process)) {
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    if ($exitCode === 0) {
        echo "SUCCESS: Database imported successfully!\n";
    } else {
        echo "ERROR (exit code $exitCode):\n";
        echo $stderr . "\n";
        echo $stdout . "\n";
    }
} else {
    die("Failed to start MySQL process.\n");
}
