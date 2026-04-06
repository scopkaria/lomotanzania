<?php
/**
 * Export the lomo database to a MySQL 8.0 compatible SQL file.
 * Fixes MySQL 9.x expression defaults like DEFAULT (_utf8mb4'value').
 *
 * Run: php export_compatible.php
 */

$host     = '127.0.0.1';
$dbname   = 'lomo';
$user     = 'root';
$password = '';
$outFile  = __DIR__ . '/database/lomo_production.sql';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

$out = fopen($outFile, 'w');

fwrite($out, "-- MySQL 8.0 compatible dump of `$dbname`\n");
fwrite($out, "-- Generated: " . date('Y-m-d H:i:s') . "\n\n");
fwrite($out, "SET NAMES utf8mb4;\n");
fwrite($out, "SET FOREIGN_KEY_CHECKS = 0;\n");
fwrite($out, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

foreach ($tables as $table) {
    echo "Exporting $table...\n";

    // Get CREATE TABLE and fix it
    $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    $ddl = $create['Create Table'];

    // Fix expression defaults: DEFAULT (_utf8mb4'value') -> DEFAULT 'value'
    $ddl = preg_replace("/DEFAULT\s+\(_utf8mb4'([^']*)'\)/i", "DEFAULT '$1'", $ddl);
    // Also handle: DEFAULT (_utf8mb3'value')
    $ddl = preg_replace("/DEFAULT\s+\(_utf8mb3'([^']*)'\)/i", "DEFAULT '$1'", $ddl);
    // Fix CHECK constraints with expression syntax in CHECK bodies
    $ddl = preg_replace("/_utf8mb4\\\\?'([^']*)'/", "'$1'", $ddl);

    fwrite($out, "DROP TABLE IF EXISTS `$table`;\n");
    fwrite($out, $ddl . ";\n\n");

    // Export data
    $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    if ($count == 0) continue;

    $rows = $pdo->query("SELECT * FROM `$table`");
    $cols = null;

    $batch = [];
    $batchSize = 100;

    foreach ($rows as $row) {
        if ($cols === null) {
            $cols = array_keys($row);
            $colList = '`' . implode('`, `', $cols) . '`';
        }

        $values = [];
        foreach ($row as $val) {
            if ($val === null) {
                $values[] = 'NULL';
            } else {
                $values[] = $pdo->quote($val);
            }
        }
        $batch[] = '(' . implode(', ', $values) . ')';

        if (count($batch) >= $batchSize) {
            fwrite($out, "INSERT INTO `$table` ($colList) VALUES\n" . implode(",\n", $batch) . ";\n\n");
            $batch = [];
        }
    }

    if (!empty($batch)) {
        fwrite($out, "INSERT INTO `$table` ($colList) VALUES\n" . implode(",\n", $batch) . ";\n\n");
    }
}

fwrite($out, "SET FOREIGN_KEY_CHECKS = 1;\n");
fclose($out);

// Clean up temp file
@unlink(__DIR__ . '/database/lomo_production_raw.sql');

$size = round(filesize($outFile) / 1024);
echo "\nDone! Exported to database/lomo_production.sql ({$size} KB)\n";
