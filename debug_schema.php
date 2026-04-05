<?php
$db = new SQLite3('database/database.sqlite');
$result = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name LIMIT 5");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "=== " . $row['name'] . " ===\n";
    echo $row['sql'] . "\n\n";
}
$db->close();
