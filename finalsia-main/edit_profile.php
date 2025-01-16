<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user profile data
$stmt = $conn->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();
$stmt->close();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'] ?: 'Add Location';
    $bio = $_POST['bio'] ?: 'Add Bio';
    $work_history = $_POST['workhistory'] ?: 'Add Work History';
    $languages = $_POST['languages'] ?: 'Add Languages';
    $education = $_POST['education'] ?: 'Add Education';

    // Check if the profile exists, if not, insert it
    if ($user_profile) {
        // Update the user profile in the database
        $update_stmt = $conn->prepare("UPDATE user_profile SET location = ?, bio = ?, workhistory = ?, languages = ?, education = ? WHERE user_id = ?");
        $SSupdate_stmt->bind_param("sssssi", $location, $bio, $work_history, $languages, $education, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: profile.php"); // Redirect to the profile page after updating
            exit;
        } else {
            $_SESSION['error'] = "Failed to update profile. Please try again.";
        }
    } else {
        // If user profile does not exist, insert a new profile
        $insert_stmt = $conn->prepare("INSERT INTO user_profile (user_id, location, bio, workhistory, languages, education) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("isssss", $user_id, $location, $bio, $work_history, $languages, $education);

        if ($insert_stmt->execute()) {
            $_SESSION['success'] = "Profile created successfully!";
            header("Location: profiles.php"); // Redirect to the profile page after creating
            exit;
        } else {
            $_SESSION['error'] = "Failed to create profile. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .profile-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-container">
            <h2>Edit Profile</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['success'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            ?>
            <form action="edit_profile.php" method="POST">
    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($user_profile['location'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="bio">Bio</label>
        <textarea class="form-control" id="bio" name="bio" rows="4" required><?php echo htmlspecialchars($user_profile['bio'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label for="work_history">Work History</label>
        <textarea class="form-control" id="workhistory" name="workhistory" rows="4" required><?php echo htmlspecialchars($user_profile['work_history'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label for="languages">Languages</label>
        <input type="text" class="form-control" id="languages" name="languages" value="<?php echo htmlspecialchars($user_profile['languages'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="education">Education</label>
        <textarea class="form-control" id="education" name="education" rows="4" required><?php echo htmlspecialchars($user_profile['education'] ?? ''); ?></textarea>
    </div>

    <button type="submit" class="btn">Save Changes</button>
</form>

        </div>
    </div>
</body>

</html>
