<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Example: Symptoms entered by the user (this can be dynamic based on user input)
    $symptoms = [1, 0, 1, 1];  // Replace this with the actual user input

    // Prepare JSON payload
    $data = json_encode(['symptoms' => $symptoms]);

    // Initialize cURL
    $ch = curl_init('http://localhost:5000/predict');  // Flask API URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Execute request and get response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $result = json_decode($response, true);

    // Display the health risk prediction
    echo "Predicted Health Risk: " . $result['risk'];
}
?>

<!-- HTML Form for input -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Risk Prediction</title>
</head>
<body>
    <h2>Enter Symptoms for Health Risk Prediction</h2>
    <form method="POST">
        <label>Fatigue (1 = Yes, 0 = No):</label>
        <input type="text" name="fatigue" required><br><br>
        
        <label>Headache (1 = Yes, 0 = No):</label>
        <input type="text" name="headache" required><br><br>
        
        <label>Thirst (1 = Yes, 0 = No):</label>
        <input type="text" name="thirst" required><br><br>
        
        <label>Frequent Urination (1 = Yes, 0 = No):</label>
        <input type="text" name="frequent_urination" required><br><br>
        
        <button type="submit">Check Health Risk</button>
    </form>
</body>
</html>