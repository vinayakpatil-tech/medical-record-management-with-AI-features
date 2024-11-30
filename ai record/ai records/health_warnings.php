<?php
// Capture form data from the frontend (user input)
$age = $_POST['age'] ?? 0;
$blood_sugar = $_POST['blood_sugar'] ?? 0;
$bmi = $_POST['bmi'] ?? 0;

// Log the input data for debugging purposes
file_put_contents('debug.log', "Input Data: Age = $age, Blood Sugar = $blood_sugar, BMI = $bmi\n", FILE_APPEND);

// Prepare data to send to Python script
$data = json_encode(array('age' => $age, 'blood_sugar' => $blood_sugar, 'bmi' => $bmi));

// Create a temporary file with the data to be passed to the Python script
file_put_contents("input_data.json", $data);

// Execute the Python script and capture output
$output = shell_exec("python C:\\xampp\\htdocs\\medical-record-system\\ai\\risk_analysis.py");

// Log the output for debugging
file_put_contents('debug.log', "Python Script Output: $output\n", FILE_APPEND);

// Display the output (health warning)
echo $output;
?>
