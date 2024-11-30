<?php
session_start();
require 'config.php'; // Include database connection

$error_message = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Query the `doctors` table to find the doctor with the provided email
        $stmt = $conn->prepare("SELECT id, password FROM doctors WHERE email = ?");
        $stmt->execute([$email]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            // Verify the entered password with the hashed password in the database
            if (password_verify($password, $doctor['password'])) {
                // Set session variables for the doctor
                $_SESSION['doctor_id'] = $doctor['id'];
                $_SESSION['doctor_email'] = $email;

                // Redirect to the doctor dashboard
                header("Location: doctor_dashboard.php");
                exit;
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Invalid email or password.";
        }
    } catch (Exception $e) {
        $error_message = "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6e7fd7, #a3b8e7);
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-out;
            background-image:url("images/doctlog.jpg");
            background-size: 100% 100%;
        }

        header {
            text-align: center;
            margin-top: 50px;
            color: #fff;
        }

        h1 {
            font-size: 36px;
            animation: slideDown 1s ease-out;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        form {
            background: transparent;
            opacity: 2;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: formSlideUp 1s ease-out;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #4c8bf5;
            outline: none;
        }

        button {
            background-color: #4c8bf5;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #3578e5;
            transform: scale(1.05);
        }

        a {
            color: #4c8bf5;
            text-decoration: none;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 10px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #3578e5;
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            animation: fadeIn 1s ease-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes formSlideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 80%;
            }
        }

    </style>
</head>
<body>
    <header>
        <h1 style="color:gold;">Doctor Login</h1>
    </header>
    <main>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
            <a href="doctor_forgot_password.php">Forgot Password?</a>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
    </main>

</body>
</html>
