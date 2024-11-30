<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Body Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            animation: fadeIn 1s ease-out;
        }

        /* Header Styling */
        .dashboard-header {
            background-color: lightcoral;
            color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
            position: relative;
            bottom: 148px;
           
        }
        /* Container for the Dashboard Links */
        .dashboard-container {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        /* Dashboard Links Styling */
        .dashboard-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Individual Link Box Styling */
        .dashboard-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            width: 180px;
            text-align: center;
            background: #f4f4f9;
            border: 2px solid #ccc;
            border-radius: 15px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .dashboard-link:hover {
            background-color: #0073e6;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dashboard-link img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        .dashboard-link:hover img {
            transform: rotate(360deg); /* Rotate image on hover */
        }

        .dashboard-link span {
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-links {
                flex-direction: column;
            }
            .dashboard-link {
                width: 150px;
                padding: 15px;
            }
        }

    </style>
</head>
<body>

    <!-- Dashboard Header with Old Code -->
    <header class="dashboard-header">
        <h1 style>Welcome to Your Dashboard</h1>
    </header>

    <div class="dashboard-container">
        <div class="dashboard-links">
            <a href="upload.php" class="dashboard-link">
                <img src="images/upload.png" alt="Upload">
                <span>Upload Records</span>
            </a>
            <a href="manage_records.php" class="dashboard-link">
                <img src="images/records.png" alt="Manage">
                <span>Manage Records</span>
            </a>
            <a href="share_records.php" class="dashboard-link">
                <img src="images/share.png" alt="Share">
                <span>Share Records</span>
            </a>
            <a href="index.html" class="dashboard-link">
                <img src="images/logout.png" alt="Logout">
                <span>Logout</span>
            </a>
        </div>
    </div>

    <script>
        // JavaScript to add more interactivity (optional)
        document.addEventListener('DOMContentLoaded', () => {
            const dashboardLinks = document.querySelectorAll('.dashboard-link');

            // Add mouseover effect to the links for added interaction
            dashboardLinks.forEach(link => {
                link.addEventListener('mouseover', () => {
                    link.querySelector('span').style.color = '#fff'; // Change text color on hover
                });

                link.addEventListener('mouseout', () => {
                    link.querySelector('span').style.color = '#333'; // Reset text color
                });
            });
        });
    </script>
</body>
</html>
