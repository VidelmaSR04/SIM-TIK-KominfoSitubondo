<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\MasterDataController;
use Illuminate\Validation\Validator;
use ReflectionMethod;

// Create controller instance
$controller = new MasterDataController();

// Get private methods using reflection
$reflection = new ReflectionMethod($controller, 'aturan');
$reflection->setAccessible(true);
$rules = $reflection->invoke($controller);

$reflection = new ReflectionMethod($controller, 'pesan');
$reflection->setAccessible(true);
$messages = $reflection->invoke($controller);

$reflection = new ReflectionMethod($controller, 'olahData');
$reflection->setAccessible(true);

// Test data with empty urutan (as from form)
$input = [
    'kategori' => 'pejabat',
    'value' => 'TEST_FORM',
    'label' => 'Test Label',
    'urutan' => '', // empty string from form
    'is_aktif' => '1',
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan',
    // Note: qrcode and hapus_qrcode are not included as they are files/checkboxes
];

echo "Input data:" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// Validate
$validator = Validator::make($input, $rules, $messages);
if (!$validator->passes()) {
    echo "VALIDATION FAILED:" . PHP_EOL;
    echo var_export($validator->errors()->all(), true) . PHP_EOL;
    exit;
}

$validated = $validator->validated();
echo "Validated data:" . PHP_EOL;
echo var_export($validated, true) . PHP_EOL . PHP_EOL;

// Process through olahData
// We need to create a mock request
$request = Request::create('/master-data', 'POST', $input);
$processed = $reflection->invoke($controller, $request, $validated);

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
} catch (\Exception $e) {
    echo "ERROR creating model: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}