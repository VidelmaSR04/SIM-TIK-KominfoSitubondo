<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Http\Controllers\MasterDataController;
use ReflectionClass;

// Create controller instance
$controller = new MasterDataController();

// Get private methods using reflection
$reflection = new ReflectionClass($controller);
$aturanMethod = $reflection->getMethod('aturan');
$aturanMethod->setAccessible(true);
$rules = $aturanMethod->invoke($controller);

$pesanMethod = $reflection->getMethod('pesan');
$pesanMethod->setAccessible(true);
$messages = $pesanMethod->invoke($controller);

// Test data with empty urutan (as from form)
$input = [
    'kategori' => 'pejabat',
    'value' => 'TEST_FINAL_CHECK',
    'label' => 'Test Final Check',
    'urutan' => '', // empty string from form
    'is_aktif' => '1',
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan',
];

echo "=== TESTING THE FIX ===" . PHP_EOL;
echo "Input data (as from form):" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// Step 1: Apply preprocessing (what we added to controller)
$preprocessed = $input;
if (isset($preprocessed['urutan']) && $preprocessed['urutan'] === '') {
    $preprocessed['urutan'] = null;
}

echo "Step 1 - After preprocessing (empty string -> null):" . PHP_EOL;
echo var_export($preprocessed, true) . PHP_EOL . PHP_EOL;

// Step 2: Run validation
echo "Step 2 - Running validation..." . PHP_EOL;

// Create validator instance
$app = require __DIR__ . '/bootstrap/app.php';
$validationFactory = $app->make(ValidationFactory::class);
$validator = $validationFactory->make($preprocessed, $rules, $messages);

if (!$validator->passes()) {
    echo "VALIDATION FAILED!" . PHP_EOL;
    echo "Errors:" . PHP_EOL;
    echo var_export($validator->errors()->all(), true) . PHP_EOL;
    exit;
}

$validated = $validator->validated();
echo "VALIDATION PASSED!" . PHP_EOL;
echo "Validated data:" . PHP_EOL;
echo var_export($validated, true) . PHP_EOL . PHP_EOL;

// Step 3: Run through olahData
echo "Step 3 - Running through olahData..." . PHP_EOL;

$olahDataMethod = $reflection->getMethod('olahData');
$olahDataMethod->setAccessible(true);

$request = Request::create('/master-data', 'POST', $preprocessed);
$processed = $olahDataMethod->invoke($controller, $request, $validated);

echo "Processed data (after olahData):" . PHP_EOL;
echo var_export($processed, true) . PHP_EOL . PHP_EOL;

// Step 4: Check urutan specifically
echo "Step 4 - Checking urutan..." . PHP_EOL;
echo "Urutan value: " . var_export($processed['urutan'], true) . PHP_EOL;
echo "Urutan type: " . gettype($processed['urutan']) . PHP_EOL;
echo "Is urutan null? " . var_export(is_null($processed['urutan']), true) . PHP_EOL;
echo "Is urutan integer? " . var_export(is_int($processed['urutan']), true) . PHP_EOL;

// Step 5: Try to create the model
echo "Step 5 - Trying to create model..." . PHP_EOL;
try {
    $model = App\Models\MasterData::create($processed);
    echo "SUCCESS: Model created with ID: " . $model->id . PHP_EOL;
    echo "Urutan in DB: " . $model->urutan . PHP_EOL;

    // Clean up test record
    $model->delete();
    echo "Test record cleaned up." . PHP_EOL;
    echo PHP_EOL . "=== ALL TESTS PASSED ===" . PHP_EOL;
} catch (\Exception $e) {
    echo "ERROR creating model: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}