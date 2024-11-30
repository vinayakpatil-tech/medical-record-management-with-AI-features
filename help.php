<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Simplified Medical Record Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        header {
            background: darkkhaki;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007BFF;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 0.5rem 0;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f4f4f4;
        }
        ul li strong {
            color: #007BFF;
            cursor: pointer;
        }
        ul li p {
            display: none; /* Hide answers by default */
            margin-top: 0.5rem;
        }
        .btn {
            display: inline-block;
            margin: 0.5rem 0;
            padding: 0.75rem 1.5rem;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
            transition: background-color 0.3s ease-in-out;
        }
        .btn:hover {
            background: #0056b3;
        }
        footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            background: #f4f4f4;
            color: #333;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all FAQ questions
            const questions = document.querySelectorAll('ul li strong');
            
            // Add click event to each question
            questions.forEach(function (question) {
                question.addEventListener('click', function () {
                    // Toggle visibility of the answer (the p element) for each clicked question
                    const answer = question.nextElementSibling;
                    if (answer.style.display === 'none' || answer.style.display === '') {
                        answer.style.display = 'block'; // Show answer
                    } else {
                        answer.style.display = 'none'; // Hide answer
                    }
                });
            });
        });
    </script>
    
</head>


<body>
    <header>
        <h1>Help & Support</h1>
        <p>Find answers to your login and registration issues</p>
    </header>

    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <ul>
            <li>
                <strong>Q: I forgot my password. What should I do?</strong>
                <p>A: Click on the "Forgot Password?" link on the login page. Enter your registered email address, and we’ll send you a password reset link.</p>
            </li>
            <li>
                <strong>Q: I’m not receiving the verification email after registration.</strong>
                <p>A: Check your spam or junk mail folder. If the email is still not there, click on "Resend Verification Email" on the login page or contact support.</p>
            </li>
            <li>
                <strong>Q: I see an error message when trying to register.</strong>
                <p>A: Ensure all required fields are filled correctly and your password meets the criteria. If the problem persists, contact support.</p>
            </li>
            <li>
                <strong>Q: Why can’t I log in after registration?</strong>
                <p>A: Ensure you have verified your email address. If verified, double-check your credentials. Use the "Forgot Password?" option if needed.</p>
            </li>
        </ul>

        <h2>Contact Support</h2>
        <p>If your issue is not listed, you can reach out to our support team for assistance:</p>
        <ul>
            <li>Email: <a href="#">codebytes@gmail.com</a></li>
            <li>Phone: +1 123 234 4567 </li>
        </ul>
        <a href="index.html" class="btn">Back to Home</a>
    </div>

    <footer>
        <p>&copy; 2024 Simplified Medical Record Management System. All rights reserved.</p>
    </footer>
</body>
</html>
