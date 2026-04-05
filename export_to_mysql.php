<?php
/**
 * Export SQLite database to MySQL-compatible SQL file.
 * Run: php export_to_mysql.php
 * Output: database/database.sql
 */

$sqliteFile = __DIR__ . '/database/database.sqlite';
$outputFile = __DIR__ . '/database/database.sql';

if (!file_exists($sqliteFile)) {
    die("SQLite database not found at: $sqliteFile\n");
}

$db = new SQLite3($sqliteFile);

// Get all table names
$tables = [];
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $tables[] = $row['name'];
}

echo "Found " . count($tables) . " tables: " . implode(', ', $tables) . "\n";

$sql = "-- MySQL dump exported from SQLite\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Source: database/database.sqlite\n\n";
$sql .= "SET NAMES utf8mb4;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $table) {
    echo "Exporting table: $table\n";

    // Get CREATE TABLE info from SQLite
    $createResult = $db->querySingle("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'");

    // Convert SQLite CREATE TABLE to MySQL-compatible
    $createSql = convertCreateTable($createResult, $table);
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";
    $sql .= $createSql . ";\n\n";

    // Export data
    $dataResult = $db->query("SELECT * FROM `$table`");
    $rowCount = 0;
    $insertBatch = [];

    // Get column info
    $columns = [];
    $pragmaResult = $db->query("PRAGMA table_info(`$table`)");
    while ($col = $pragmaResult->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $col['name'];
    }

    while ($row = $dataResult->fetchArray(SQLITE3_ASSOC)) {
        $values = [];
        foreach ($row as $key => $value) {
            if ($value === null) {
                $values[] = 'NULL';
            } elseif (is_numeric($value) && !preg_match('/^0\d/', $value)) {
                $values[] = $value;
            } else {
                $values[] = "'" . $db->escapeString($value) . "'";
            }
        }
        $insertBatch[] = '(' . implode(', ', $values) . ')';
        $rowCount++;

        // Batch inserts every 100 rows
        if (count($insertBatch) >= 100) {
            $colList = '`' . implode('`, `', $columns) . '`';
            $sql .= "INSERT INTO `$table` ($colList) VALUES\n" . implode(",\n", $insertBatch) . ";\n";
            $insertBatch = [];
        }
    }

    // Flush remaining rows
    if (!empty($insertBatch)) {
        $colList = '`' . implode('`, `', $columns) . '`';
        $sql .= "INSERT INTO `$table` ($colList) VALUES\n" . implode(",\n", $insertBatch) . ";\n";
    }

    $sql .= "\n";
    echo "  -> $rowCount rows\n";
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents($outputFile, $sql);
echo "\nDone! Exported to: $outputFile\n";
echo "File size: " . round(filesize($outputFile) / 1024, 2) . " KB\n";

$db->close();

// --- Helper ---

function convertCreateTable(string $sqliteSql, string $table): string
{
    $s = $sqliteSql;

    // Replace double quotes with backticks for identifiers first
    $s = str_replace('"', '`', $s);

    // Remove SQLite-only IF NOT EXISTS
    $s = str_ireplace('IF NOT EXISTS', '', $s);

    // Handle all variations of INTEGER PRIMARY KEY AUTOINCREMENT
    // SQLite variants:
    //   `id` integer primary key autoincrement not null
    //   `id` integer not null primary key autoincrement
    //   `id` integer primary key autoincrement
    $s = preg_replace(
        '/`(\w+)`\s+integer\s+primary\s+key\s+autoincrement\s+not\s+null/i',
        '`$1` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
        $s
    );
    $s = preg_replace(
        '/`(\w+)`\s+integer\s+not\s+null\s+primary\s+key\s+autoincrement/i',
        '`$1` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
        $s
    );
    $s = preg_replace(
        '/`(\w+)`\s+integer\s+primary\s+key\s+autoincrement/i',
        '`$1` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
        $s
    );

    // Patch all *_id columns to BIGINT UNSIGNED if they reference a table with BIGINT UNSIGNED id
    $s = preg_replace_callback(
        '/(`(\w+_id)`\s+)BIGINT(?! UNSIGNED)([^,)]*)/i',
        function ($m) use ($s) {
            $col = $m[2];
            // Try to find referenced table
            if (preg_match("/foreign key\\(`$col`\\) references [`']?([a-zA-Z0-9_]+)[`']?\\s*\\(/i", $s, $fkMatch)) {
                // Always patch to BIGINT UNSIGNED for referenced id
                return $m[1] . 'BIGINT UNSIGNED' . $m[3];
            }
            return $m[0];
        },
        $s
    );

    // Convert SQLite CHECK constraints with backtick-quoted column to MySQL-compatible
    // e.g. check (`status` in ('pending','active')) → CHECK (status in ('pending','active'))
    $s = preg_replace_callback('/check\s*\(([^)]+)\)/i', function ($m) {
        return 'CHECK (' . str_replace('`', '', $m[1]) . ')';
    }, $s);

    // Convert common SQLite types to MySQL types
    $typeMap = [
        '/\binteger\b/i'  => 'BIGINT',
        '/\breal\b/i'     => 'DOUBLE',
        '/\bblob\b/i'     => 'LONGBLOB',
        '/\bnumeric\b/i'  => 'DECIMAL(10,2)',
    ];
    foreach ($typeMap as $pattern => $replacement) {
        $s = preg_replace($pattern, $replacement, $s);
    }

    // varchar without length → varchar(255)
    $s = preg_replace('/\bvarchar\b(?!\s*\()/i', 'VARCHAR(255)', $s);

    // bare "text" is fine for MySQL, but ensure boolean maps
    $s = preg_replace('/\btinyint\s*\(\s*1\s*\)/i', 'TINYINT(1)', $s);

    // Add ENGINE
    $s = preg_replace('/\)\s*$/', ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci', $s);

    return $s;
}
