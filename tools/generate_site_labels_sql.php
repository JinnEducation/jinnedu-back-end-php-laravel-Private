<?php

// Generate SQL insert statements for labels and translations
// based on lang/en/site.php and lang/ar/site.php

$baseDir = dirname(__DIR__);

$enFile = $baseDir . '/lang/en/site.php';
$arFile = $baseDir . '/lang/ar/site.php';

$en = file_exists($enFile) ? include $enFile : [];
$ar = file_exists($arFile) ? include $arFile : [];

if (!is_array($en) || !is_array($ar)) {
    fwrite(STDERR, "Language files must return arrays.\n");
    exit(1);
}

$keys = array_unique(array_merge(array_keys($en), array_keys($ar)));
sort($keys, SORT_STRING | SORT_FLAG_CASE);

foreach ($keys as $key) {
    $labelName = mb_strtolower($key, 'UTF-8');

    $enTitle = isset($en[$key]) ? $en[$key] : '';
    $arTitle = isset($ar[$key]) ? $ar[$key] : '';

    // Escape single quotes for SQL
    $nameEsc = str_replace("'", "''", $labelName);
    $enEsc   = str_replace("'", "''", $enTitle);
    $arEsc   = str_replace("'", "''", $arTitle);

    echo "INSERT INTO labels (name, file, title, created_at)\n";
    echo "SELECT '{$nameEsc}', 'site', '{$enEsc}', NOW()\n";
    echo "WHERE NOT EXISTS (\n";
    echo "    SELECT 1 FROM labels WHERE name = '{$nameEsc}'\n";
    echo ");\n\n";

    echo "SET @labelid = (SELECT id FROM labels WHERE name = '{$nameEsc}');\n\n";

    // English translation (langid = 1)
    echo "INSERT INTO translations (langid, labelid, title, created_at)\n";
    echo "SELECT 1, @labelid, '{$enEsc}', NOW()\n";
    echo "WHERE NOT EXISTS (\n";
    echo "    SELECT 1 FROM translations WHERE langid = 1 AND labelid = @labelid\n";
    echo ");\n\n";

    // Arabic translation (langid = 2)
    echo "INSERT INTO translations (langid, labelid, title, created_at)\n";
    echo "SELECT 2, @labelid, '{$arEsc}', NOW()\n";
    echo "WHERE NOT EXISTS (\n";
    echo "    SELECT 1 FROM translations WHERE langid = 2 AND labelid = @labelid\n";
    echo ");\n\n";
}


