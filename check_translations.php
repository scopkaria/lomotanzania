<?php
// Check which EN translation keys are missing from FR, DE, ES
$en = require __DIR__ . '/lang/en/messages.php';
$fr = require __DIR__ . '/lang/fr/messages.php';
$de = require __DIR__ . '/lang/de/messages.php';
$es = require __DIR__ . '/lang/es/messages.php';

$enKeys = array_keys($en);
echo "EN keys: " . count($enKeys) . "\n";
echo "FR keys: " . count($fr) . "\n";
echo "DE keys: " . count($de) . "\n";
echo "ES keys: " . count($es) . "\n\n";

echo "=== MISSING FROM FR ===\n";
foreach ($enKeys as $k) {
    if (!isset($fr[$k])) echo "  {$k} => {$en[$k]}\n";
}

echo "\n=== MISSING FROM DE ===\n";
foreach ($enKeys as $k) {
    if (!isset($de[$k])) echo "  {$k} => {$en[$k]}\n";
}

echo "\n=== MISSING FROM ES ===\n";
foreach ($enKeys as $k) {
    if (!isset($es[$k])) echo "  {$k} => {$en[$k]}\n";
}
