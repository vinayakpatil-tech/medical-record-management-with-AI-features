<?php
// Start session and include database configuration
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the 'id' parameter is passed in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $record_id = $_GET['id'];

    // Fetch the record from the database
    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE id = ?");
    $stmt->execute([$record_id]);
    $record = $stmt->fetch();

    if ($record) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $updated_diagnosis = $_POST['diagnosis'];
            $updated_treatment = $_POST['treatment'];
            $updated_medication = $_POST['medication'];

            $update_stmt = $conn->prepare("UPDATE medical_records SET diagnosis = ?, treatment = ?, medication = ? WHERE id = ?");
            $update_stmt->execute([$updated_diagnosis, $updated_treatment, $updated_medication, $record_id]);

            header("Location: dashboard.php");
            exit;
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid record ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            color: #444;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
        }
        .container {
            background: lightcyan;
            border-radius: 35px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin-top: 150px;
            margin-bottom: 50px;
            max-width: 500px;
            padding: 20px 30px;
            text-align: center;
            animation: slideIn 1s ease-out;
        }
        h2 {
            color: #8697c4;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            font-size: 1rem;
            margin-bottom: 5px;
            color: #555;
        }
        textarea {
            width: 100%;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 0.95rem;
            resize: none;
            transition: all 0.3s;
        }
        textarea:focus {
            border-color: #74ebd5;
            box-shadow: 0 0 8px rgba(116, 235, 213, 0.4);
        }
        button {
            background: #74ebd5;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #57c5a6;
        }
        .cancel-link {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #74ebd5;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .cancel-link:hover {
            color: #57c5a6;
        }
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Medical Record</h2>
        <form action="edit.php?id=<?php echo $record_id; ?>" method="POST">
            <label for="diagnosis">Diagnosis:</label>
            <textarea id="diagnosis" name="diagnosis" required><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>
            
            <label for="treatment">Treatment:</label>
            <textarea id="treatment" name="treatment" required><?php echo htmlspecialchars($record['treatment']); ?></textarea>

            <label for="medication">Medication:</label>
            <textarea id="medication" name="medication" required><?php echo htmlspecialchars($record['medication']); ?></textarea>

            <button type="submit">Update Record</button>
        </form>
        <a href="dashboard.php" class="cancel-link">Cancel</a>
    </div>
</body>
</html>
