<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, fullname, address, email, profile_picture, bio, hrate, availabitily, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $fname = htmlspecialchars($_POST['fname']);
    $address = htmlspecialchars($_POST['address']);
    $bio = htmlspecialchars($_POST['bio']);
    $hrate = htmlspecialchars($_POST['hrate']);
    $availabitily = htmlspecialchars($_POST['availabitily']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $freelancer_level = htmlspecialchars($_POST['freelancer_level']);

    // Handle profile picture upload
    $profile_picture = $user['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Update user information
    if ($password) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, fullname = ?, address = ?, password = ?, bio = ?, hrate = ?, availabitily = ?, profile_picture = ?, freelancer_level = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssi", $name, $email, $fname, $address, $password, $bio, $hrate, $availabitily, $profile_picture, $freelancer_level, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, fullname = ?, address = ?, bio = ?, hrate = ?, availabitily = ?, profile_picture = ?, freelancer_level = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $name, $email, $fname, $address, $bio, $hrate, $availabitily, $profile_picture, $freelancer_level, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        $_SESSION['user_name'] = $name; // Update session name
        header("Location: profile.php");
    } else {
        echo "Error updating profile.";
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <div class="form-wrapper">
        <h2 class="text-center mb-4">Update Profile</h2>
        <form method="POST" enctype="multipart/form-data" class="profile-form">
            <?php if ($user['profile_picture']): ?>
                <div class="text-center mb-3">
                    <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-img" />
                </div>
            <?php endif; ?>
            
            <!-- Name and Email Fields -->
            <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <!-- Password Field (Optional) -->
            <div class="mb-3">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="fname">Full Name</label>
                <input type="fname" id="fname" name="fname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address">Address</label>
                <input type="address" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>

            <!-- Bio Field -->
            <div class="mb-3">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" class="form-control" rows="3" required><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>

             <!-- Freelancer level -->
            <div class="mb-3" id="freelancerLevelContainer" >
                <label for="freelancer_level">Freelancer Level</label>
                <select name="freelancer_level" id="freelancer_level" class="form-select">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="expert">Expert</option>
                </select>
            </div>

            <!-- Hourly Rate Field -->
            <div class="mb-3">
                <label for="hrate">Hourly Rate (Php)</label>
                <input type="number" id="hrate" name="hrate" class="form-control" value="<?php echo htmlspecialchars($user['hrate']); ?>" required>
            </div>

            <!-- Availability Field -->
            <div class="mb-3">
                <label for="availabitily">Availability (e.g., 9:00 AM - 5:00 PM)</label>
                <input type="text" id="availabitily" name="availabitily" class="form-control" value="<?php echo htmlspecialchars($user['availabitily']); ?>" required>
            </div>

            <!-- Profile Picture Upload -->
            <div class="mb-3">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
            </div>

            <!-- Submit Button -->
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="<?php echo ($user['role'] == 'client') ? 'client_dashboard.php' : 'freelance_dashboard.php'; ?>" class="no-decoration">
                    <button type="button" class="btn btn-secondary">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>
</body>

</html>
