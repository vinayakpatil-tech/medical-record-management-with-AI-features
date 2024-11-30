<?php
session_start();
require 'config.php';  // Ensure this file contains your database connection

// Ensure the user is redirected properly and not accessing this page directly
if (!isset($_SESSION['user_id'])) {
    header('Location: forgot_password.php');
    exit();
}

// Check if the form for resetting the password is submitted
if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];  // Get the new password from the form
    $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);  // Hash the new password securely

    $user_id = $_SESSION['user_id'];  // Retrieve the user ID from the session

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password_hashed, $user_id]);

    // After successfully updating the password, clear the session and redirect to the login page
    unset($_SESSION['user_id']);  // Clear the session to prevent further access
    echo "Your password has been reset successfully. <a href='login.php'>Login here</a>";
}
?>

<!-- Password Reset Form -->
<form action="reset_password.php" method="POST">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit" name="reset_password">Reset Password</button>
</form>
