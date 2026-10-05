<?php

// Test the exact logic without Laravel dependencies

function processUrutan($value) {
    // This is the exact logic from the controller: (int)($value ?? 0)
    return (int)($value ?? 0);
}

// Test cases
$testCases = [
    '',      // empty string from form
    null,    // null
    0,       // zero
    5,       // integer
    '5',     // string number
    'abc',   // non-numeric string
    false,   // boolean false
    true,    // boolean true
];

echo "Testing urutan processing logic: (int)(\$value ?? 0)" . PHP_EOL;
echo "=====================================================" . PHP_EOL;

foreach ($testCases as $testCase) {
    $result = processUrutan($testCase);
    $isNull = is_null($result);
    $isInt = is_int($result);

    echo "Input: " . var_export($testCase, true) .
         " -> Output: " . var_export($result, true) .
         " (type: " . gettype($result) .
         ", is_null: " . var_export($isNull, true) .
         ", is_int: " . var_export($isInt, true) . ")" . PHP_EOL;
}

// Test what would happen in database
echo PHP_EOL . "Database compatibility check:" . PHP_EOL;
echo "=========================================" . PHP_EOL;

foreach ($testCases as $testCase) {
    $result = processUrutan($testCase);

    // In database, urutan column is INTEGER NOT NULL DEFAULT 0
    // So we need to check if result is suitable for this column
    $suitable = is_int($result) && !is_null($result);

    echo "Input: " . var_export($testCase, true) .
         " -> DB Value: " . var_export($result, true) .
         " - Suitable for INTEGER NOT NULL: " . var_export($suitable, true) . PHP_EOL;

    if (!$suitable) {
        echo "  ^^^ THIS WOULD CAUSE DATABASE ERROR! ^^^" . PHP_EOL;
    }
}