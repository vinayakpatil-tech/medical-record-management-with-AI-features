<?php
require 'config.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($password) && !empty($security_question) && !empty($security_answer)) {
        try {
            // Check if the email is already registered
            $check_stmt = $conn->prepare("SELECT email FROM doctors WHERE email = ?");
            $check_stmt->execute([$email]);

            if ($check_stmt->rowCount() > 0) {
                // Email is already registered
                $error_message = "The email is already registered. Please use a different email.";
            } else {
                // Hash the password and security answer
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $hashed_answer = password_hash($security_answer, PASSWORD_BCRYPT);

                // Insert the doctor into the database
                $stmt = $conn->prepare("INSERT INTO doctors (name, email, phone, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $phone, $hashed_password, $security_question, $hashed_answer])) {
                    $success_message = "Registration successful! You can now log in";
                } else {
                    $error_message = "Failed to register. Please try again.";
                }
            }
        } catch (PDOException $e) {
            // Handle database errors
            $error_message = "An error occurred: " . $e->getMessage();
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #64b3f4, #c2e9fb);
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-out;
            background-image: url("images/doctbg.jpg");
            background-size: cover;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
            color: white;
        }

        .form-container {
            width: 40%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-out;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #0073e6;
            outline: none;
        }

        button {
            background-color: #64b3f4;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0073e6;
            transform: scale(1.05);
        }

        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            animation: fadeIn 1s ease-out;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <h1>Doctor Registration</h1>
    <div class="form-container">
        <?php if ($error_message): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="security_question">Security Question:</label>
            <input type="text" name="security_question" id="security_question" required>

            <label for="security_answer">Security Answer:</label>
            <input type="text" name="security_answer" id="security_answer" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
