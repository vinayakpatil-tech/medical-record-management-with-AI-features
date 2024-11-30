<?php
require 'config.php';

$error_message = ''; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit(); // Ensure the script exits after redirection
    } else {
        $error_message = "Invalid email or password."; // Set error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeInBackground 2s ease-in-out;
        }

        .login-box {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            width: 350px;
            text-align: center;
            position: relative;
            animation: fadeIn 1s ease-in-out;
            z-index: 2; /* Ensure login box stays above the image */
            overflow: hidden; /* Hide the part of the image outside the box */
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .login-box input:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(0, 0, 255, 0.6);
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .login-box button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(255, 65, 108, 0.6);
        }

        .login-box a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .login-image {
            display: none; /* Hidden by default */
            position: absolute;
            top: -30%; /* Adjust to make the image appear from the back */
            left: 0;
            width: 100%; /* Set image width to match the login box */
            height: 60%; /* Set height to 60% of the login box */
            object-fit: cover; /* Make the image cover the space */
            z-index: 1; /* Place the image behind the login box */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeInBackground {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <img id="loginImage" src="images/login.jpg" alt="Secure Login" class="login-image">
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <br><br>
        <a href="forgot_password.php">Forgot Password?</a>

        <?php if ($error_message): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
    </form>
</div>

<script>
    const passwordField = document.querySelector('input[name="password"]');
    const loginImage = document.getElementById('loginImage');

    // Show image on mousemove over password field
    passwordField.addEventListener('mousemove', () => {
        loginImage.style.display = 'block'; // Show the image on mousemove
        loginImage.style.opacity = '1'; // Ensure the image is visible
    });

    // Hide image when mouse leaves the password field
    passwordField.addEventListener('mouseleave', () => {
        loginImage.style.opacity = '0'; // Fade out the image
        setTimeout(() => {
            loginImage.style.display = 'none'; // Hide the image after fade-out
        }, 300); // Wait for fade-out transition
    });
</script>

</body>
</html>

