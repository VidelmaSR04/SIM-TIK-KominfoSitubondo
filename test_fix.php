<?php

// Test the fix: preprocessing empty urutan to null before validation

// Simulate the input that would come from a form with empty urutan
$input = [
    'kategori' => 'pejabat',
    'value' => 'TEST_FIX',
    'label' => 'Test Label',
    'urutan' => '', // This is what an empty form field submits as
    'is_aktif' => '1',
];

echo "Original input:" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// Apply the preprocessing (what we added to controller)
if (isset($input['urutan']) && $input['urutan'] === '') {
    $input['urutan'] = null;
}

echo "After preprocessing (empty string -> null):" . PHP_EOL;
echo var_export($input, true) . PHP_EOL . PHP_EOL;

// Now simulate what the validation rules would do
$rules = [
    'kategori' => ['required'],
    'value' => ['required', 'string', 'max:255'],
    'label' => ['nullable', 'string', 'max:255'],
    'urutan' => ['nullable', 'integer'], // This now accepts null
    'is_aktif' => ['boolean'],
];

// Simple validation function
function validate($data, $rules) {
    $errors = [];

    foreach ($rules as $field => $ruleList) {
        if (!isset($data[$field])) {
            // Check if rule allows nullable/missing values
            $allowsNull = false;
            foreach ($ruleList as $rule) {
                if ($rule === 'nullable' || $rule === 'sometimes') {
                    $allowsNull = true;
                    break;
                }
            }

            if (!$allowsNull) {
                $errors[$field][] = "The $field field is required.";
            }
            continue;
        }

        $value = $data[$field];

        foreach ($ruleList as $rule) {
            if ($rule === 'required' && (is_null($value) || $value === '')) {
                $errors[$field][] = "The $field field is required.";
            }
            elseif ($rule === 'nullable' && is_null($value)) {
                // nullable allows null, continue to next rule
                continue;
            }
            elseif ($rule === 'integer' && !is_null($value)) {
                if (!is_int($value) && !ctype_digit((string)$value)) {
                    $errors[$field][] = "The $field field must be an integer.";
                }
            }
            elseif ($rule === 'boolean' && !is_null($value)) {
                if (!is_bool($value) && !in_array(strtolower((string)$value), ['true', 'false', '1', '0'])) {
                    $errors[$field][] = "The $field field must be a boolean.";
                }
            }
            elseif (strpos($rule, 'max:') === 0 && !is_null($value)) {
                $max = (int)substr($rule, 4);
                if (is_string($value) && strlen($value) > $max) {
                    $errors[$field][] = "The $field field may not be greater than $max characters.";
                }
            }
        }
    }

    return $errors;
}

$errors = validate($input, $rules);

if (!empty($errors)) {
    echo "VALIDATION ERRORS:" . PHP_EOL;
    echo var_export($errors, true) . PHP_EOL;
} else {
    echo "VALIDATION PASSED!" . PHP_EOL;
    echo "Validated data:" . PHP_EOL;
    echo var_export($input, true) . PHP_EOL;

    // Now test the olahData logic
    echo "Testing olahData logic:" . PHP_EOL;

    // For pejabat category, urutan processing
    $processedUrutan = (int)($input['urutan'] ?? 0);
    echo "Input urutan: " . var_export($input['urutan'], true) . PHP_EOL;
    echo "Processed urutan: " . var_export($processedUrutan, true) . " (type: " . gettype($processedUrutan) . ")" . PHP_EOL;

    // Check if it's suitable for database (INTEGER NOT NULL)
    $dbSuitable = is_int($processedUrutan) && !is_null($processedUrutan);
    echo "Suitable for INTEGER NOT NULL column: " . var_export($dbSuitable, true) . PHP_EOL;
}