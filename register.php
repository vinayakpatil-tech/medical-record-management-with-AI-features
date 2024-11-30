<?php
require 'config.php';

// Check if registration form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password securely

    // Get the selected security question and answer
    $security_question = $_POST['security_question'];
    $security_answer = password_hash($_POST['security_answer'], PASSWORD_BCRYPT);  // Hash the security answer

    // Prepare SQL to insert the new user with all details
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $phone, $password, $security_question, $security_answer])) {
        $registration_success = true;  // Indicate successful registration
    } else {
        $registration_error = "Error: " . $stmt->errorInfo()[2];  // Capture any error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        .register-box input,
        .register-box select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .register-box button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .register-box button:hover {
            background-color: #45a049;
        }
        .register-box a {
            color: #007BFF;
            text-decoration: none;
        }
        .register-box a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="password" name="password" placeholder="Password" required>

        <!-- Security Question Selection -->
        <label for="security_question">Security Question:</label>
        <select name="security_question" id="security_question" required>
            <option value="mother_maiden_name">What is your mother's maiden name?</option>
            <option value="first_pet_name">What was the name of your first pet?</option>
            <option value="childhood_friend">What is the name of your childhood best friend?</option>
        </select>

        <!-- Security Answer -->
        <input type="text" name="security_answer" placeholder="Answer to the security question" required>

        <button type="submit" name="register">Register</button>

        <?php if (isset($registration_error)): ?>
            <div class="error-message"><?= htmlspecialchars($registration_error) ?></div>
        <?php elseif (isset($registration_success) && $registration_success): ?>
            <div class="success-message">Registration successful! <a href="login.php">Login here</a></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>