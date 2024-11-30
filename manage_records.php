<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all medical records uploaded by the logged-in user, including medical details
$stmt = $conn->prepare("SELECT * FROM medical_records WHERE user_id = ?");
$stmt->execute([$user_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Records</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            animation: fadeIn 1.5s ease-out;
        }

        header {
            background-color: #00796b; /* Teal background */
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2rem;
        }

        header nav {
            margin-top: 15px;
        }

        header nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        header nav a:hover {
            color: #ffeb3b; /* Yellow hover effect */
        }

        main {
            width: 80%;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff; /* White background for content */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-out;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #00796b;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table a {
            color: #00796b;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        table a:hover {
            color: #ff5722; /* Orange color on hover */
        }

        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        /* Animation for Fade-In */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Animation for Slide-Up */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                width: 90%;
            }

            header h1 {
                font-size: 1.5rem;
            }

            table th, table td {
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Your Medical Records</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="index.html">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Your Uploaded Records</h2>

        <?php if (count($records) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Uploaded At</th>
                        <th>Medical History</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Medication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['record_name']) ?></td>
                            <td><?= htmlspecialchars($record['uploaded_at']) ?></td>
                            <td><?= htmlspecialchars($record['medical_history']) ?></td>
                            <td><?= htmlspecialchars($record['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($record['treatment']) ?></td>
                            <td><?= htmlspecialchars($record['medication']) ?></td>
                            <td>
                                <a href="uploads/<?= htmlspecialchars($record['record_file']) ?>" target="_blank">View</a> |
                                <a href="delete_record.php?id=<?= $record['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No records found. <a href="upload.php">Upload a new record</a>.</p>
        <?php endif; ?>
    </main>
</body>
</html>
