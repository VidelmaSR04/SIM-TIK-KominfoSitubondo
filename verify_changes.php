<?php

// Verification script for the implemented changes

echo "=== VERIFICATION OF IMPLEMENTED CHANGES ===\n\n";

// 1. Check Server model for missingRequiredFields method
echo "1. Checking Server model methods:\n";
$modelFile = __DIR__.'/app/Models/Server.php';
if (file_exists($modelFile)) {
    $content = file_get_contents($modelFile);
    $hasMissingFields = strpos($content, 'public function getMissingRequiredFields()') !== false;
    $hasFieldLabel = strpos($content, 'public function getFieldLabel(string $field)') !== false;

    echo "   - getMissingRequiredFields() method exists: " . ($hasMissingFields ? '✓ PASS' : '✗ FAIL') . "\n";
    echo "   - getFieldLabel() method exists: " . ($hasFieldLabel ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - Server model file not found: ✗ FAIL\n";
}
echo "\n";

// 2. Check detailserver.blade.php for missing fields warning
echo "2. Checking detailserver.blade.php:\n";
$detailView = __DIR__.'/resources/views/detailserver.blade.php';
if (file_exists($detailView)) {
    $content = file_get_contents($detailView);
    $hasMissingWarning = strpos($content, 'missingRequiredFields()') !== false;
    $hasStatusKelengkapanMap = strpos($content, '$statusKelengkapanMap = [') !== false;

    echo "   - missing fields warning section: " . ($hasMissingWarning ? '✓ PASS' : '✗ FAIL') . "\n";
    echo "   - status kelengkapan mapping: " . ($hasStatusKelengkapanMap ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - detailserver.blade.php not found: ✗ FAIL\n";
}
echo "\n";

// 3. Check inputdata.blade.php for missing fields warning
echo "3. Checking inputdata.blade.php:\n";
$inputView = __DIR__.'/resources/views/inputdata.blade.php';
if (file_exists($inputView)) {
    $content = file_get_contents($inputView);
    $hasMissingWarning = strpos($content, 'missingRequiredFields()') !== false;
    $hasStatusKelengkapanMap = strpos($content, '$statusKelengkapanMap = [') !== false;

    echo "   - missing fields warning section: " . ($hasMissingWarning ? '✓ PASS' : '✗ FAIL') . "\n";
    echo "   - status kelengkapan mapping: " . ($hasStatusKelengkapanMap ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - inputdata.blade.php not found: ✗ FAIL\n";
}
echo "\n";

// 4. Check dashboarduser.blade.php for status mapping and missing fields
echo "4. Checking dashboarduser.blade.php:\n";
$dashboardView = __DIR__.'/resources/views/user/dashboarduser.blade.php';
if (file_exists($dashboardView)) {
    $content = file_get_contents($dashboardView);
    $hasStatusMapping = strpos($content, '$statusKelengkapanMap = [') !== false;
    $hasMissingFieldsUsage = strpos($content, '$device->missingRequiredFields()') !== false;
    $hasStatusColors = strpos($content, 'bg-gray-100 text-gray-800') !== false &&
                         strpos($content, 'bg-yellow-100 text-yellow-800') !== false &&
                         strpos($content, 'bg-green-100 text-green-800') !== false;

    echo "   - status kelengkapan mapping: " . ($hasStatusMapping ? '✓ PASS' : '✗ FAIL') . "\n";
    echo "   - missing fields usage: " . ($hasMissingFieldsUsage ? '✓ PASS' : '✗ FAIL') . "\n";
    echo "   - consistent status colors: " . ($hasStatusColors ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - dashboarduser.blade.php not found: ✗ FAIL\n";
}
echo "\n";

// 5. Check manajemen-server.blade.php for status mapping
echo "5. Checking manajemen-server.blade.php:\n";
$manajemenView = __DIR__.'/resources/views/manajemen-server.blade.php';
if (file_exists($manajemenView)) {
    $content = file_get_contents($manajemenView);
    $hasStatusMapping = strpos($content, '$statusMap = [') !== false &&
                       strpos($content, '\'Aktif\' => [\'label\' => \'Aktif\',') !== false &&
                       strpos($content, '\'Pending\' => [\'label\' => \'Menunggu Kelengkapan Data\',') !== false;

    echo "   - status mapping with correct labels: " . ($hasStatusMapping ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - manajemen-server.blade.php not found: ✗ FAIL\n";
}
echo "\n";

// 6. Check server.blade.php for status mapping
echo "6. Checking server.blade.php:\n";
$serverView = __DIR__.'/resources/views/server.blade.php';
if (file_exists($serverView)) {
    $content = file_get_contents($serverView);
    $hasStatusMapping = strpos($content, '$statusMap = [') !== false &&
                       strpos($content, '\'Aktif\' => [\'label\' => \'Aktif\',') !== false &&
                       strpos($content, '\'Pending\' => [\'label\' => \'Menunggu Kelengkapan Data\',') !== false;

    echo "   - status mapping with correct labels: " . ($hasStatusMapping ? '✓ PASS' : '✗ FAIL') . "\n";
} else {
    echo "   - server.blade.php not found: ✗ FAIL\n";
}
echo "\n";

echo "=== VERIFICATION COMPLETE ===\n";
