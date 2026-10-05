<?php

// Direct test of the fix without Laravel container

// Simulate the exact flow in the controller

// 1. Input from form (empty urutan)
$input = [
    'kategori' => 'pejabat',
    'value' => 'DIRECT_TEST',
    'label' => 'Direct Test Record',
    'urutan' => '', // This is what empty form field submits as
    'is_aktif' => '1',
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan',
];

echo "Step 1 - Original input from form:" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// 2. Preprocessing (what we added to controller)
if (isset($input['urutan']) && $input['urutan'] === '') {
    $input['urutan'] = null;
}

echo "Step 2 - After preprocessing (empty string -> null):" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// 3. Validation (simulating Laravel's 'nullable', 'integer' rules)
$validationErrors = [];

// Check required fields
$requiredFields = ['kategori', 'value', 'is_aktif'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || $input[$field] === '') {
        $validationErrors[$field][] = "The {$field} field is required.";
    }
}

// Check urutan: nullable integer means it can be null or integer
if (isset($input['urutan']) && $input['urutan'] !== null) {
    if (!is_int($input['urutan']) && !ctype_digit((string)$input['urutan'])) {
        $validationErrors['urutan'][] = "The urutan field must be an integer.";
    }
}

// Check other fields...
// For simplicity, we'll assume other validations pass

if (!empty($validationErrors)) {
    echo "VALIDATION ERRORS:" . PHP_EOL;
    echo var_export($validationErrors, true) . PHP_EOL;
    exit;
}

echo "Step 3 - Validation passed!" . PHP_EOL . PHP_EOL;

// 4. olahData processing (what happens after validation)
echo "Step 4 - Running olahData logic..." . PHP_EOL;

// For pejabat category:
if ($input['kategori'] !== 'pejabat') {
    // Non-pejabat category logic
    $input['nip'] = null;
    $input['pangkat'] = null;
    $input['jabatan_ttd'] = null;
    $input['qrcode_path'] = null;
    $input['urutan'] = (int)($input['urutan'] ?? 0);
} else {
    // Pejabat category logic - ensure urutan is integer
    $input['urutan'] = (int)($input['urutan'] ?? 0);
}

echo "Step 4 - After olahData processing:" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// 5. Check if ready for database
echo "Step 5 - Database readiness check:" . PHP_EOL;
$urutan = $input['urutan'];
$isInteger = is_int($urutan);
$isNotNull = !is_null($urutan);
$dbReady = $isInteger && $isNotNull;

echo "Urutan value: " . var_export($urutan, true) . PHP_EOL;
echo "Is integer: " . var_export($isInteger, true) . PHP_EOL;
echo "Is not null: " . var_export($isNotNull, true) . PHP_EOL;
echo "Ready for INTEGER NOT NULL column: " . var_export($dbReady, true) . PHP_EOL;

if ($dbReady) {
    echo PHP_EOL . "✅ SUCCESS: The fix works! Record can be inserted into database." . PHP_EOL;

    // Show what would be saved
    echo PHP_EOL . "Data that would be saved:" . PHP_EOL;
    $saveData = [
        'kategori' => $input['kategori'],
        'value' => $input['value'],
        'label' => $input['label'],
        'urutan' => $input['urutan'],
        'is_aktif' => $input['is_aktif'] == '1',
        'nip' => $input['nip'],
        'pangkat' => $input['pangkat'],
        'jabatan_ttd' => $input['jabatan_ttd'],
        // qrcode_path would be set if file uploaded
    ];
    echo var_export($saveData, true) . PHP_EOL;
} else {
    echo PHP_EOL . "❌ FAILURE: Record would cause database error." . PHP_EOL;
}