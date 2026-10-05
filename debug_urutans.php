<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Http\Controllers\MasterDataController;

// Simulate the request data that would come from a form with empty urutan
$requestData = [
    'kategori' => 'pejabat',
    'value' => 'TEST_DEBUG',
    'label' => 'Test Label',
    'urutan' => '', // This is what an empty form field would submit as
    'is_aktif' => '1',
    'nip' => '1234567890',
    'pangkat' => 'IV/a Pengatur Muda',
    'jabatan_ttd' => 'Test Jabatan'
];

// Create a mock request
$request = Request::create('/master-data', 'POST', $requestData);

// Get the controller and run validation
$controller = new MasterDataController();
$rules = $controller->aturan();
$messages = $controller->pesan();

$validator = Validator::make($requestData, $rules, $messages);

echo "Validation passes: " . var_export($validator->passes(), true) . PHP_EOL;
if (!$validator->passes()) {
    echo 'Validation errors: ' . var_export($validator->errors()->all(), true) . PHP_EOL;
    exit;
}

$validated = $validator->validated();
echo "Validated data before olahData: " . var_export($validated, true) . PHP_EOL;

// Now run through olahData
$processedData = $controller->olahData($request, $validated);
echo "Processed data after olahData: " . var_export($processedData, true) . PHP_EOL;

// Test what happens when we create the model
try {
    $model = App\Models\MasterData::create($processedData);
    echo "Model created successfully: " . var_export($model->toArray(), true) . PHP_EOL;
} catch (\Exception $e) {
    echo "Error creating model: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}