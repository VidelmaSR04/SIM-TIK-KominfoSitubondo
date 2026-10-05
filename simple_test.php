<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\Validator;
use App\Http\Controllers\MasterDataController;

// Create controller instance
$controller = new MasterDataController();

// Get protected methods using reflection to bypass access restrictions
$reflectionClass = new ReflectionClass($controller);
$aturanMethod = $reflectionClass->getMethod('aturan');
$aturanMethod->setAccessible(true);
$rules = $aturanMethod->invoke($controller);

$pesanMethod = $reflectionClass->getMethod('pesan');
$pesanMethod->setAccessible(true);
$messages = $pesanMethod->invoke($controller);

$olahDataMethod = $reflectionClass->getMethod('olahData');
$olahDataMethod->setAccessible(true);

// Test data with empty urutan (as from form)
$input = [
    'kategori' => 'pejabat',
    'value' => 'TEST_SIMPLE',
    'label' => 'Test Label',
    'urutan' => '', // empty string from form
    'is_aktif' => '1',
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan',
];

echo "Input data:" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// Create validator instance manually
$app = require __DIR__ . '/bootstrap/app.php';
$validationFactory = $app->make(ValidationFactory::class);
$validator = $validationFactory->make($input, $rules, $messages);

if (!$validator->passes()) {
    echo "VALIDATION FAILED:" . PHP_EOL;
    echo var_export($validator->errors()->all(), true) . PHP_EOL;
    exit;
}

$validated = $validator->validated();
echo "Validated data:" . PHP_EOL;
echo var_export($validated, true) . PHP_EOL . PHP_EOL;

// Process through olahData
$request = Request::create('/master-data', 'POST', $input);
$processed = $olahDataMethod->invoke($controller, $request, $validated);

echo "Processed data (after olahData):" . PHP_EOL;
echo var_export($processed, true) . PHP_EOL . PHP_EOL;

// Check urutan specifically
echo "Urutan value: " . var_export($processed['urutan'], true) . PHP_EOL;
echo "Urutan type: " . gettype($processed['urutan']) . PHP_EOL;
echo "Is urutan null? " . var_export(is_null($processed['urutan']), true) . PHP_EOL;

// Try to create the model
try {
    $model = App\Models\MasterData::create($processed);
    echo "SUCCESS: Model created with ID: " . $model->id . PHP_EOL;
    echo "Urutan in DB: " . $model->urutan . PHP_EOL;

    // Clean up test record
    $model->delete();
    echo "Test record cleaned up." . PHP_EOL;
} catch (\Exception $e) {
    echo "ERROR creating model: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}