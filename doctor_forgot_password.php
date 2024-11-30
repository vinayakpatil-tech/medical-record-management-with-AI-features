<?php
require 'config.php'; // Database connection

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Normalize the email by trimming spaces
        $email = trim($_POST['email']);
        
        // Log the email being checked for debugging
        error_log("Checking email: '$email'");

        // Prepare the query to select the doctor with the provided email
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
        $stmt->execute([$email]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log the result from the query for debugging
        error_log("Doctor fetched: " . print_r($doctor, true));

        if ($doctor) {
            // If the doctor exists, store session data and redirect to reset password page
            session_start();
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_email'] = $email;
            $_SESSION['security_question'] = $doctor['security_question'];
            header("Location: doctor_reset_password.php"); // Redirect to the reset password page
            exit;
        } else {
            // If no account found, show error
            $error_message = "No account found with that email.";
            error_log("Failed lookup for email: $email"); // Logs failure to find email
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
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #f3f4f6, #e6e8eb);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 1s ease-out;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="email"] {
            padding: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus {
            border-color: #4CAF50;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

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
        <h1>Forgot Password</h1>
        
        <!-- Display error message if any -->
        <?php if ($error_message): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Submit</button>
        </form>
    </div>

    
</body>
</html>
