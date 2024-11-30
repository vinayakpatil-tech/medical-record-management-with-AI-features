<?php
require 'config.php'; // Database connection

$error_message = '';
$success_message = '';

// Ensure the doctor is logged in
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve doctor data from session
$doctor_id = $_SESSION['doctor_id'];
$security_question = $_SESSION['security_question'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['security_answer']) && isset($_POST['new_password'])) {
        // Get the security answer and new password from the form
        $security_answer = $_POST['security_answer'];
        $new_password = $_POST['new_password'];
        
        // Fetch the stored security answer from the database
        $stmt = $conn->prepare("SELECT security_answer FROM doctors WHERE id = ?");
        $stmt->execute([$doctor_id]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log the result from the query for debugging
        error_log("Doctor fetched for password reset: " . print_r($doctor, true));

        if ($doctor && password_verify($security_answer, $doctor['security_answer'])) {
            // Answer is correct, hash new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE doctors SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed_password, $doctor_id])) {
                $success_message = "Password updated successfully.";
                session_destroy(); // Log the doctor out after successful password reset
                header("Location: doctor_login.php"); // Redirect to login page
                exit;
            } else {
                $error_message = "Failed to update password. Please try again.";
            }
        } else {
            $error_message = "Incorrect security answer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* General Reset */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #64b3f4, #c2e9fb);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Container for the form */
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            width: 400px;
            animation: fadeIn 1s ease-out;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            align-self: flex-start;
        }

        input[type="text"], input[type="password"] {
            padding: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #4CAF50;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Error and Success Messages */
        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 10px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Reset Your Password</h1>

        <!-- Display error or success message -->
        <?php if ($error_message): ?>
            <div class="message error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php elseif ($success_message): ?>
            <div class="message success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <!-- Display the security question -->
        <p><strong>Security Question:</strong> <?= htmlspecialchars($security_question) ?></p>

        <!-- Form to reset password -->
        <form method="POST">
            <label for="security_answer">Answer:</label>
            <input type="text" id="security_answer" name="security_answer" required>
            
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>

</body>
</html>
