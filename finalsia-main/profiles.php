<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Fetch user profile data
$stmt = $conn->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();
$stmt->close();

// Define default messages for empty fields
$default_location = "ADD LOCATION";
$default_bio = "Add a bio to tell us about yourself.";
$default_work_history = "No work history added yet.";
$default_languages = "No languages specified.";
$default_education = "No education details added.";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .header {
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-header h2 {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 20px;
        }

        .card {
            margin-top: 10px;
        }

        .edit-btn {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <?php 
            if (empty($user_profile['portfolio_picture'])) {
                echo "Add Profile";
            } else {
                echo '<img src="uploads/' . htmlspecialchars($user_profile['portfolio_picture']) . '" alt="Profile Picture" class="profile-img">';
            }
            ?>
            <div>
                <h2><?php echo htmlspecialchars($user_name); ?></h2>
                <p><strong>Role:</strong> <?php echo ucfirst($user_role); ?></p>
                <p><strong>Location:</strong> <?php echo empty($user_profile['location']) ? $default_location : htmlspecialchars($user_profile['location']); ?></p>
                <p><strong>Bio:</strong> <?php echo empty($user_profile['bio']) ? $default_bio : nl2br(htmlspecialchars($user_profile['bio'])); ?></p>
            </div>
        </div>

        <div class="section-title">Work History</div>
        <div class="card">
            <div class="card-body">
                <p><?php echo empty($user_profile['workhistory']) ? $default_work_history : nl2br(htmlspecialchars($user_profile['workhistory'])); ?></p>
            </div>
        </div>

        <div class="section-title">Languages</div>
        <div class="card">
            <div class="card-body">
                <p><?php echo empty($user_profile['languages']) ? $default_languages : nl2br(htmlspecialchars($user_profile['languages'])); ?></p>
            </div>
        </div>

        <div class="section-title">Education</div>
        <div class="card">
            <div class="card-body">
                <p><?php echo empty($user_profile['education']) ? $default_education : nl2br(htmlspecialchars($user_profile['education'])); ?></p>
            </div>
        </div>

        <form action="edit_profile.php" method="GET">
            <button type="submit" class="edit-btn">Edit Profile</button>
        </form>
    </div>
</div>
</body>

</html>
