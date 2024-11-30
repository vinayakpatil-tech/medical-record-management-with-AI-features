<?php
// Start session and include database configuration
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit;
}

// Check if the 'id' parameter is passed in the URL and it's a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Begin a transaction to ensure atomicity (both deletes happen together)
        $conn->beginTransaction();

        // First, delete any entries in the shared_records table that reference the medical record
        $stmt = $conn->prepare("DELETE FROM shared_records WHERE record_id = ?");
        $stmt->execute([$id]);

        // Now, delete the medical record itself
        $stmt = $conn->prepare("DELETE FROM medical_records WHERE id = ?");
        $stmt->execute([$id]);

        // Commit the transaction
        $conn->commit();

        // Redirect to dashboard or any page after successful deletion
        header("Location: dashboard.php");
        exit;

    } catch (PDOException $e) {
        // Rollback transaction in case of error
        $conn->rollBack();
        // Handle errors (optional, you can log errors if needed)
        echo "Error deleting record: " . $e->getMessage();
    }
} else {
    // Handle the case where the 'id' is not passed or invalid
    echo "Invalid record ID.";
}
?>
