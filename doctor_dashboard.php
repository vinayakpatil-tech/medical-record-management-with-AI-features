<?php
session_start();
require 'config.php';

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch doctor email from the database based on the logged-in doctor ID if it's not already set
if (!isset($_SESSION['doctor_email'])) {
    $stmt = $conn->prepare("SELECT email FROM doctors WHERE id = ?");
    $stmt->execute([$_SESSION['doctor_id']]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        $_SESSION['doctor_email'] = $doctor['email'];
    } else {
        echo "Invalid session. Please log in again.";
        exit;
    }
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch all records shared with the doctor, including access level
$stmt = $conn->prepare("
    SELECT m.record_name, m.medical_history, m.diagnosis, m.treatment, m.medication, 
           s.access_level, m.record_file, s.record_id
    FROM medical_records m
    JOIN shared_records s ON m.id = s.record_id
    WHERE s.doctor_id = ?
");
$stmt->execute([$doctor_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: white;
        }

        main {
            padding: 80px 20px 20px;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        h2 {
            color: #4CAF50;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .record-row:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }

        /* Button Styling */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .view-btn {
            background-color: #2196F3;
        }

        .view-btn:hover {
            background-color: #1e88e5;
        }

        .edit-btn {
            background-color: #FF9800;
        }

        .edit-btn:hover {
            background-color: #f57c00;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>

    <script>
        // Simple JavaScript to handle confirmation before editing
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const confirmation = confirm("Are you sure you want to edit this record?");
                    if (!confirmation) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Doctor's Dashboard</h1>
        <nav>
            <a href="index.html">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Records Shared with You</h2>

        <?php if (count($records) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Medical History</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Medication</th>
                        <th>Access Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr class="record-row">
                            <td><?= htmlspecialchars($record['record_name']) ?></td>
                            <td><?= htmlspecialchars($record['medical_history']) ?></td>
                            <td><?= htmlspecialchars($record['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($record['treatment']) ?></td>
                            <td><?= htmlspecialchars($record['medication']) ?></td>
                            <td><?= htmlspecialchars($record['access_level']) ?></td>
                            <td>
                                <?php if ($record['access_level'] === 'view'): ?>
                                    <a href="uploads/<?= htmlspecialchars($record['record_file']) ?>" target="_blank" class="btn view-btn">View</a>
                                <?php elseif ($record['access_level'] === 'edit'): ?>
                                    <a href="edit.php?id=<?= $record['record_id'] ?>" class="btn edit-btn">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No records shared with you.</p>
        <?php endif; ?>
    </main>
</body>
</html>
