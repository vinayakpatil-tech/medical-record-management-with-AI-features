<?php
session_start();
require 'config.php';  // Ensure this file contains your database connection

// Ensure session step is initialized
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 'email'; // Default to the email entry step
}

// Initialize variables for error and success messages
$error_message = '';
$success_message = '';

// Process form submission based on the current step
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['step'] === 'email') {
        $email = $_POST['email'];

        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['security_question'] = $user['security_question'];
            $_SESSION['step'] = 'security'; // Move to the security question step
            $success_message = "Please answer your security question.";
        } else {
            $error_message = "No account found with that email address.";
        }
    } elseif ($_SESSION['step'] === 'security') {
        $security_answer = $_POST['security_answer'];

        // Retrieve the stored answer from the database
        $stmt = $conn->prepare("SELECT security_answer FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($security_answer, $user['security_answer'])) {
            // Correct answer, move to the password reset step
            $_SESSION['step'] = 'reset';
            $success_message = "Answer correct! You can now set your new password.";
        } else {
            $error_message = "Incorrect security answer. Please try again.";
        }
    } elseif ($_SESSION['step'] === 'reset') {
        $new_password = $_POST['new_password'];
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
            // Password reset successful
            session_destroy(); // Clear the session
            header("Location: login.php"); // Redirect to login page
            exit;
        } else {
            $error_message = "Failed to reset password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: 
        linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), /* Gradient overlay */
        url('images/aif.jpeg'), 
        url('images/aif2.jpeg');
    background-size: 100%, 50% 100%, 50% 100%; /* Gradient spans full width, images cover 50% each */
    background-position: center, left top, right top; /* Center gradient, images on left and right */
    background-repeat: no-repeat, no-repeat, no-repeat; /* Prevent repeating for all layers */

        }
        .forgot-password-box {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
            border-radius: 50px;
            background-color: lightcyan;
        }
        .forgot-password-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .forgot-password-box button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .forgot-password-box button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="forgot-password-box">
    <!-- Display error or success message -->
    <?php if ($error_message): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php elseif ($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form action="forgot_password.php" method="POST">
        <!-- Email Step -->
        <?php if ($_SESSION['step'] === 'email'): ?>
            <label for="email">Enter your email address:</label>
            <input type="email" name="email" id="email" required>
        
        <!-- Security Question Step -->
        <?php elseif ($_SESSION['step'] === 'security'): ?>
            <p><strong>Your Security Question:</strong></p>
            <p><?= htmlspecialchars($_SESSION['security_question']) ?></p>
            <label for="security_answer">Answer:</label>
            <input type="text" name="security_answer" id="security_answer" required>
        
        <!-- Password Reset Step -->
        <?php elseif ($_SESSION['step'] === 'reset'): ?>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
        <?php endif; ?>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>