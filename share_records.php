<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record_id = $_POST['record_id'];
    $access_level = $_POST['access_level'];

    try {
        $doctor_id = 5; // Assuming doctor ID is selected dynamically or predefined.

        // Check if the doctor exists in the `doctors` table.
        $stmt = $conn->prepare("SELECT id FROM doctors WHERE id = ?");
        $stmt->execute([$doctor_id]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            // Check if the record belongs to the patient.
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT id FROM medical_records WHERE id = ? AND user_id = ?");
            $stmt->execute([$record_id, $user_id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record) {
                // Check if the record is already shared with the doctor.
                $stmt = $conn->prepare("SELECT * FROM shared_records WHERE record_id = ? AND doctor_id = ?");
                $stmt->execute([$record_id, $doctor_id]);
                if ($stmt->rowCount() > 0) {
                    $message = "This record is already shared with the specified doctor.";
                } else {
                    // Share the record.
                    $stmt = $conn->prepare("INSERT INTO shared_records (record_id, doctor_id, access_level) VALUES (?, ?, ?)");
                    $stmt->execute([$record_id, $doctor_id, $access_level]);
                    $message = "Record shared successfully!";
                }
            } else {
                $message = "You can only share records that belong to you.";
            }
        } else {
            $message = "Doctor not found. Cannot share the record.";
        }
    } catch (Exception $e) {
        $message = "An error occurred while sharing the record: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Medical Records</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f1f3f6; /* Light gray background for the body */
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-out;
            background-image: url("images/sharebg.jpg");
            background-size: 100% 100%;
        }

        header {
             /* Purple background for the header */
            color: white;
            padding: 15px 0;
            text-align: center;
            border-radius: 30px;
        }

        header nav {
            margin-top: 10px;
        }

        header nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        header nav a:hover {
            color: #ffeb3b; /* Yellow hover effect */
        }

        main {
            width: 70%;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff; /* White background for the main section */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-out;
            background: transparent;
            color: white;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: whitesmoke; /* Dark text color for title */
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #666; /* Slightly lighter text color for subtitles */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
            font-size: 16px;
            color: black;
        }

        select, button {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
        }

        select:focus, button:focus {
            border-color: #6a1b9a;
            outline: none;
        }

        button {
            background-color: #6a1b9a;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #9c4dcc; /* Lighter purple hover effect */
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

        /* Animations */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                width: 90%;
            }

            header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1 >Share Medical Records</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_records.php">Manage Records</a>
        </nav>
    </header>
    <main>
        <h2 style="color:white; margin-left:260px;" >Select a Record to Share</h2>
        <?php if (!empty($message)): ?>
            <p class="message <?= (strpos($message, 'success') !== false) ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php
        try {
            // Fetch records belonging to the logged-in user (patient)
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT id, record_name FROM medical_records WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($records) > 0):
        ?>
            <form style="background-color:lightblue;" action="share_records.php" method="POST">
                <label for="record_id">Select Record:</label>
                <select name="record_id" id="record_id" required>
                    <?php foreach ($records as $record): ?>
                        <option value="<?= $record['id'] ?>"><?= htmlspecialchars($record['record_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="access_level">Access Level:</label>
                <select name="access_level" id="access_level" required>
                    <option value="view">View</option>
                    <option value="edit">Edit</option>
                </select>

                <button type="submit">Share Record</button>
            </form>
        <?php
            else:
                echo "<p>No records found. <a href='upload.php'>Upload a new record</a>.</p>";
            endif;
        } catch (Exception $e) {
            echo "<p>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </main>
</body>
</html>
