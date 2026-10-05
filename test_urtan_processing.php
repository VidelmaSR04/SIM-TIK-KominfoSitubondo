<?php

// Test the exact processing that should happen
$testValues = [
    '',      // empty string
    null,    // null
    0,       // zero
    5,       // integer
    '5',     // string number
    'abc',   // non-numeric string
    false,   // boolean false
    true,    // boolean true
];

echo "Testing urutan processing: (int)(\$value ?? 0)" . PHP_EOL;
echo "======================================" . PHP_EOL;

foreach ($testValues as $value) {
    $result = (int)($value ?? 0);
    echo "Input: " . var_export($value, true) .
         " -> " . var_export($result, true) .
         " (type: " . gettype($result) . ")" . PHP_EOL;
}

echo PHP_EOL . "Testing if result is null: " . PHP_EOL;
foreach ($testValues as $value) {
    $result = (int)($value ?? 0);
    $isNull = is_null($result);
    echo "Input: " . var_export($value, true) .
         " -> Result is null: " . var_export($isNull, true) . PHP_EOL;
}