<?php
/**
 * Global Database Encoding Fix
 * 1. Convert all tables to utf8mb4_unicode_ci
 * 2. Scan all text columns for mojibake / double-encoded content
 * 3. Fix corrupted strings in-place
 *
 * Usage: php fix_db_encoding.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$dbName = DB::getDatabaseName();

// ═══════════════════════════════════════════════════════
// STEP 1: Convert all tables to utf8mb4_unicode_ci
// ═══════════════════════════════════════════════════════

echo "═══════════════════════════════════════════\n";
echo "STEP 1: Converting tables to utf8mb4\n";
echo "═══════════════════════════════════════════\n";

$tables = DB::select("SELECT TABLE_NAME, TABLE_COLLATION 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$dbName]);

$converted = 0;
foreach ($tables as $table) {
    $name = $table->TABLE_NAME;
    if ($table->TABLE_COLLATION !== 'utf8mb4_unicode_ci') {
        DB::statement("ALTER TABLE `{$name}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "  ✓ Converted: {$name} (was {$table->TABLE_COLLATION})\n";
        $converted++;
    }
}
echo $converted === 0 
    ? "  ✓ All tables already utf8mb4_unicode_ci\n" 
    : "  ✓ Converted {$converted} tables\n";


// ═══════════════════════════════════════════════════════
// STEP 2: Scan for mojibake patterns in all text columns
// ═══════════════════════════════════════════════════════

echo "\n═══════════════════════════════════════════\n";
echo "STEP 2: Scanning for corrupted text\n";
echo "═══════════════════════════════════════════\n";

// Common mojibake patterns: UTF-8 bytes interpreted as Latin-1/CP1252
$mojibakePatterns = [
    'â€"'   => '–',   // en dash
    'â€"'   => '—',   // em dash (note: same â€ prefix, different trailing byte)
    'â€™'   => "'",   // right single quote
    'â€˜'   => "'",   // left single quote
    'â€œ'   => '"',   // left double quote
    'â€'    => '"',   // right double quote (â€ followed by nothing/end)
    'Ã©'    => 'é',
    'Ã¨'    => 'è',
    'Ãª'    => 'ê',
    'Ã«'    => 'ë',
    'Ã '    => 'à',
    'Ã¢'    => 'â',
    'Ã§'    => 'ç',
    'Ã®'    => 'î',
    'Ã¯'    => 'ï',
    'Ã¹'    => 'ù',
    'Ã»'    => 'û',
    'Ã¼'    => 'ü',
    'Ã¶'    => 'ö',
    'Ã¤'    => 'ä',
    'Ã '    => 'Ä',   // this can conflict; we'll be careful
    'Ã±'    => 'ñ',
    'Ã³'    => 'ó',
    'Ã­'    => 'í',
    'Ãº'    => 'ú',
    'Ã¡'    => 'á',
    'Ã‰'    => 'É',
    'Ã"'    => 'Ö',
    'Ãœ'    => 'Ü',
    'Ã„'    => 'Ä',
];

// Content tables to scan
$contentTables = [
    'safari_packages',
    'destinations',
    'itineraries',
    'pages',
    'blog_posts',
    'blog_categories',
    'tour_types',
    'categories',
    'accommodations',
    'accommodation_images',
    'hero_settings',
    'testimonials',
    'countries',
    'departments',
];

$totalFixed = 0;

foreach ($contentTables as $tableName) {
    // Check if table exists
    $exists = DB::select("SELECT COUNT(*) as cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?", [$dbName, $tableName]);
    if ($exists[0]->cnt == 0) {
        continue;
    }

    // Get all text/varchar columns
    $columns = DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? 
        AND DATA_TYPE IN ('varchar', 'text', 'mediumtext', 'longtext', 'json')", [$dbName, $tableName]);
    
    if (empty($columns)) continue;

    $tableFixed = 0;

    foreach ($columns as $col) {
        $colName = $col->COLUMN_NAME;
        
        // Build LIKE conditions for mojibake detection
        $likeConditions = [];
        $params = [];
        foreach ($mojibakePatterns as $bad => $good) {
            $likeConditions[] = "`{$colName}` LIKE ?";
            $params[] = "%{$bad}%";
        }
        
        if (empty($likeConditions)) continue;

        // Find affected rows
        $whereClause = implode(' OR ', $likeConditions);
        $affected = DB::select("SELECT id, `{$colName}` FROM `{$tableName}` WHERE ({$whereClause}) LIMIT 100", $params);

        foreach ($affected as $row) {
            $value = $row->$colName;
            if ($value === null) continue;

            $original = $value;

            // Apply fixes — use mb_detect_encoding to handle properly
            // First try: if the string looks like double-encoded UTF-8, decode it
            $decoded = @mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
            
            // Check if the decoded version is valid UTF-8 and looks better
            if ($decoded && mb_check_encoding($decoded, 'UTF-8') && $decoded !== $value) {
                // Verify it actually fixed something (contains real unicode chars now)
                $hasFixes = false;
                foreach ($mojibakePatterns as $bad => $good) {
                    if (strpos($value, $bad) !== false && strpos($decoded, $bad) === false) {
                        $hasFixes = true;
                        break;
                    }
                }
                if ($hasFixes) {
                    $value = $decoded;
                }
            }

            // Fallback: direct string replacements for known patterns
            foreach ($mojibakePatterns as $bad => $good) {
                $value = str_replace($bad, $good, $value);
            }

            // Also fix HTML entity double-encoding
            // e.g. &amp;lt; → &lt; or &amp;amp; → &amp;
            if (strpos($value, '&amp;lt;') !== false || strpos($value, '&amp;gt;') !== false || strpos($value, '&amp;amp;') !== false) {
                $value = str_replace(
                    ['&amp;lt;', '&amp;gt;', '&amp;amp;', '&amp;quot;'],
                    ['&lt;', '&gt;', '&amp;', '&quot;'],
                    $value
                );
            }

            if ($value !== $original) {
                DB::table($tableName)->where('id', $row->id)->update([$colName => $value]);
                $tableFixed++;
                $totalFixed++;
            }
        }
    }

    if ($tableFixed > 0) {
        echo "  ✓ {$tableName}: fixed {$tableFixed} values\n";
    }
}

echo $totalFixed === 0 
    ? "  ✓ No corrupted text found in database\n"
    : "\n  Total: {$totalFixed} values fixed\n";


// ═══════════════════════════════════════════════════════
// STEP 3: Verify database connection charset
// ═══════════════════════════════════════════════════════

echo "\n═══════════════════════════════════════════\n";
echo "STEP 3: Connection charset verification\n";
echo "═══════════════════════════════════════════\n";

$charsetVars = DB::select("SHOW VARIABLES LIKE 'character_set%'");
foreach ($charsetVars as $var) {
    $ok = in_array($var->Value, ['utf8mb4', 'utf8', 'binary', '']) ? '✓' : '⚠';
    echo "  {$ok} {$var->Variable_name}: {$var->Value}\n";
}

$collVars = DB::select("SHOW VARIABLES LIKE 'collation%'");
foreach ($collVars as $var) {
    echo "  ✓ {$var->Variable_name}: {$var->Value}\n";
}

echo "\n═══════════════════════════════════════════\n";
echo "Done! Global encoding fix complete.\n";
echo "═══════════════════════════════════════════\n";
