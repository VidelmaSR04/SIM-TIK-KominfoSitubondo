<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\MasterDataController;
use Illuminate\Validation\Validator;

$controller = new MasterDataController();
$rules = $controller->aturan();
$messages = $controller->pesan();

// Test with empty string
$data = [
    'kategori' => 'pejabat',
    'value' => 'TEST_VAL',
    'label' => 'Test Label',
    'urutan' => '',
    'is_aktif' => '1'
];

$validator = Validator::make($data, $rules, $messages);
echo "Validation passes: " . var_export($validator->passes(), true) . PHP_EOL;
if (!$validator->passes()) {
    echo 'Errors: ' . var_export($validator->errors()->all(), true) . PHP_EOL;
}

// Test with null
$data2 = [
    'kategori' => 'pejabat',
    'value' => 'TEST_VAL2',
    'label' => 'Test Label 2',
    'urutan' => null,
    'is_aktif' => '1'
];

$validator2 = Validator::make($data2, $rules, $messages);
echo "Validation passes (null): " . var_export($validator2->passes(), true) . PHP_EOL;
if (!$validator2->passes()) {
    echo 'Errors (null): ' . var_export($validator2->errors()->all(), true) . PHP_EOL;
}

// Test with integer
$data3 = [
    'kategori' => 'pejabat',
    'value' => 'TEST_VAL3',
    'label' => 'Test Label 3',
    'urutan' => 5,
    'is_aktif' => '1'
];

$validator3 = Validator::make($data3, $rules, $messages);
echo "Validation passes (int): " . var_export($validator3->passes(), true) . PHP_EOL;
if (!$validator3->passes()) {
    echo 'Errors (int): ' . var_export($validator3->errors()->all(), true) . PHP_EOL;
}