<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the number of records the user has uploaded
$stmt = $conn->prepare("SELECT COUNT(*) AS record_count FROM medical_records WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_stats = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['record_file'])) {
    $record_name = $_POST['record_name'];
    $medical_history = $_POST['medical_history'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $medication = $_POST['medication'];
    $file = $_FILES['record_file'];

    $allowed_extensions = ['pdf', 'jpg', 'png', 'jpeg'];
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Validate file type
    if (!in_array($file_extension, $allowed_extensions)) {
        $error = "Invalid file type. Only PDF, JPG, and PNG files are allowed.";
    } elseif ($file['size'] > 5 * 1024 * 1024) { // Limit file size to 5MB
        $error = "File size exceeds the limit of 5MB.";
    } else {
        $file_name = uniqid() . "." . $file_extension;
        $upload_path = 'uploads/' . $file_name;

        // Move file to the uploads directory
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Insert record into the database
            try {
                $stmt = $conn->prepare("
                    INSERT INTO medical_records 
                    (user_id, record_name, medical_history, diagnosis, treatment, medication, record_file, uploaded_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([ 
                    $user_id, 
                    $record_name, 
                    $medical_history, 
                    $diagnosis, 
                    $treatment, 
                    $medication, 
                    $file_name
                ]);
                $success = "Medical record uploaded successfully!";
            } catch (PDOException $e) {
                $error = "Error inserting data: " . $e->getMessage();
            }
        } else {
            $error = "Failed to upload the file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Medical Record</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-out;
            background-image:url("images/uploadbg.jpg");
            background-size: 100% 100%;
        }

        .upload-container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background: snow;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-out;
        }

        .stats {
            text-align: center;
            margin-bottom: 20px;
        }

        .upload-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
            font-size: 16px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #0073e6;
            outline: none;
        }

        textarea {
            height: 100px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out, background-color 0.3s;
        }

        button:hover {
            background-color: #005bb5;
            transform: scale(1.05);
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
            .upload-container {
                width: 90%;
            }
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    button.style.transform = 'scale(0.95)';
                    setTimeout(() => button.style.transform = 'scale(1)', 150);
                });
            });
        });
    </script>
</head>
<body>
    <div class="upload-container">
        <div class="stats">
            <h2>Upload Your Medical Records</h2>
            <p>You have uploaded <strong><?= $user_stats['record_count'] ?></strong> records so far.</p>
        </div>
        <form class="upload-form" method="POST" enctype="multipart/form-data">
            <label for="record_name">Patient Name:</label>
            <input type="text" id="record_name" name="record_name" required placeholder="Enter record name">

            <label for="medical_history">Medical History:</label>
            <textarea id="medical_history" name="medical_history" required placeholder="Enter medical history"></textarea>

            <label for="diagnosis">Diagnosis:</label>
            <textarea id="diagnosis" name="diagnosis" required placeholder="Enter diagnosis"></textarea>

            <label for="treatment">Treatment:</label>
            <textarea id="treatment" name="treatment" required placeholder="Enter treatment details"></textarea>

            <label for="medication">Medication:</label>
            <textarea id="medication" name="medication" required placeholder="Enter medication details"></textarea>

            <label for="record_file">Upload Supporting Document:</label>
            <input type="file" id="record_file" name="record_file" required>

            <button type="submit">Upload Record</button>
        </form>

        <?php if (isset($success)): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>