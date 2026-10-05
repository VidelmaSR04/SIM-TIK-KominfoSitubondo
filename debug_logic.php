<?php

// Test the exact logic that's happening in the controller

// Simulate request data
$validated = [
    'kategori' => 'pejabat',
    'value' => 'TEST_LOGIC',
    'label' => 'Test Label',
    'urutan' => '', // Empty string from form
    'is_aktif' => true,
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan',
    'qrcode_path' => 'some/path.png'
];

echo "Input data: " . var_export($validated, true) . PHP_EOL;

// This is the logic from olahData function for pejabat category
// Line 160-161: $validated['urutan'] = (int)($validated['urutan'] ?? 0);
$validated['urutan'] = (int)($validated['urutan'] ?? 0);

echo "After processing urutan: " . var_export($validated['urutan'], true) . PHP_EOL;

// Test what gets saved to database
try {
    // In real app, this would be: App\Models\MasterData::create($validated);
    // But we'll just validate the data manually

    // Check if urutan is integer and not null
    if (!is_int($validated['urutan'])) {
        throw new Exception("urutan is not integer: " . gettype($validated['urutan']));
    }

    if ($validated['urutan'] === null) {
        throw new Exception("urutan is null");
    }

    echo "Data is valid for database insertion!" . PHP_EOL;
    echo "Final urutan value: " . $validated['urutan'] . PHP_EOL;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

// Test other cases
echo PHP_EOL . "=== Testing other cases ===" . PHP_EOL;

$testCases = [
    '',
    null,
    0,
    5,
    '5',
    'abc',
    false,
    true
];

foreach ($testCases as $testCase) {
    $result = (int)($testCase ?? 0);
    echo "Input: " . var_export($testCase, true) . " -> Output: " . var_export($result, true) . " (type: " . gettype($result) . ")" . PHP_EOL;
}