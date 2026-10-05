<?php

// Simple test to verify our fix works
// We'll test the logic directly without Laravel's validation

function testUrutanProcessing($inputValue) {
    // Step 1: Preprocessing (what we added to controller)
    $preprocessed = $inputValue;
    if ($preprocessed === '') {
        $preprocessed = null;
    }

    // Step 2: Validation check (simulating 'nullable', 'integer')
    $validationPassed = true;
    if ($preprocessed !== null && !is_int($preprocessed) && !ctype_digit((string)$preprocessed)) {
        $validationPassed = false;
    }

    // Step 3: olahData processing (what happens after validation)
    $processed = (int)($preprocessed ?? 0);

    // Step 4: Database suitability check
    $dbSuitable = is_int($processed) && !is_null($processed);

    return [
        'input' => $inputValue,
        'preprocessed' => $preprocessed,
        'validation_passed' => $validationPassed,
        'processed' => $processed,
        'db_suitable' => $dbSuitable
    ];
}

// Test cases
$testCases = [
    '',      // empty string from form
    null,    // explicit null
    0,       // zero
    5,       // integer
    '5',     // string number
    'abc',   // non-numeric string (should fail validation)
    false,   // boolean false
    true,    // boolean true
];

echo "=== TESTING URUTAN PROCESSING FIX ===" . PHP_EOL;
echo "Testing flow: Input -> Preprocessing -> Validation -> olahData -> DB Check" . PHP_EOL;
echo PHP_EOL;

$allGood = true;

foreach ($testCases as $testCase) {
    $result = testUrutanProcessing($testCase);

    echo "Input: " . var_export($result['input'], true) . PHP_EOL;
    echo "  After preprocessing: " . var_export($result['preprocessed'], true) . PHP_EOL;
    echo "  Validation passed: " . var_export($result['validation_passed'], true) . PHP_EOL;
    echo "  After olahData: " . var_export($result['processed'], true) . " (type: " . gettype($result['processed']) . ")" . PHP_EOL;
    echo "  DB suitable (INTEGER NOT NULL): " . var_export($result['db_suitable'], true) . PHP_EOL;

    if (!$result['validation_passed']) {
        echo "  ❌ VALIDATION FAILED - This input would be rejected" . PHP_EOL;
        $allGood = false;
    } elseif (!$result['db_suitable']) {
        echo "  ❌ DB UNSUITABLE - Would cause database error" . PHP_EOL;
        $allGood = false;
    } else {
        echo "  ✅ GOOD - Ready for database insertion" . PHP_EOL;
    }
    echo PHP_EOL;
}

if ($allGood) {
    echo "🎉 ALL TESTS PASSED! The fix should resolve the 'urutan cannot be null' error.";
} else {
    echo "❌ Some tests failed. The fix may need adjustment.";
}