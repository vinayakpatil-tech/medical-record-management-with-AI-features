<?php
header('Content-Type: application/json');

// Get input from POST request
$age = $_POST['age'] ?? 0;
$blood_sugar = $_POST['blood_sugar'] ?? 0;
$bmi = $_POST['bmi'] ?? 0;

// Validate input
if (!$age || !$blood_sugar || !$bmi) {
    echo json_encode(["error" => "Invalid input data. Please provide all fields."]);
    exit;
}

// Prepare input data for the Python script
$input_data = [
    "age" => (int)$age,
    "blood_sugar" => (float)$blood_sugar,
    "bmi" => (float)$bmi
];

// Write input data to JSON file
if (!file_put_contents('ai/input_data.json', json_encode($input_data))) {
    echo json_encode(["error" => "Failed to save input data."]);
    exit;
}

// Run the Python script and capture output
$output = shell_exec('python ai/risk_analysis.py 2>&1'); // Redirect errors to output

// Check if Python script executed properly
if ($output) {
    // Send the response
    $decoded_output = json_decode($output, true);
    if ($decoded_output) {
        echo $output; // Proper JSON response
    } else {
        echo json_encode(["error" => "Python script did not return valid JSON."]);
    }
} else {
    echo json_encode(["error" => "Error executing Python script. Check server configuration."]);
}
?>
