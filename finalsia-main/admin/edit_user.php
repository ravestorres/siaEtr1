<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user data
    $stmt = $conn->prepare("SELECT id, name, email, role, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        die("User not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Profile Picture Upload (optional)
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $profile_picture = $_FILES['profile_picture'];
            $profile_picture_name = time() . '_' . basename($profile_picture['name']);
            $profile_picture_path = 'uploads/' . $profile_picture_name;
            move_uploaded_file($profile_picture['tmp_name'], $profile_picture_path);
        } else {
            $profile_picture_name = $user['profile_picture'];
        }

        // Update user information
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $role, $profile_picture_name, $user_id);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Edit User</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-picture-preview {
            margin-top: 10px;
        }
    </style>
</head>
<body>   
    <div class="container mt-5">
        <div class="form-container mx-auto" style="max-width: 600px;">
            <h2 class="text-center mb-4">Edit User</h2>
            <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter user name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    <div class="invalid-feedback">Please provide a valid name.</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter user email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <div class="invalid-feedback">Please provide a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="client" <?php if ($user['role'] == 'client') echo 'selected'; ?>>Client</option>
                        <option value="freelancer" <?php if ($user['role'] == 'freelancer') echo 'selected'; ?>>Freelancer</option>
                    </select>
                    <div class="invalid-feedback">Please select a valid role.</div>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" onchange="previewImage(event)">
                    <?php if ($user['profile_picture']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" id="profilePicturePreview" class="profile-picture-preview" width="80" alt="Profile Picture">
                    <?php else: ?>
                        <img id="profilePicturePreview" class="profile-picture-preview" width="80" style="display:none;" alt="Profile Picture">
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Preview uploaded profile picture
        function previewImage(event) {
            const preview = document.getElementById('profilePicturePreview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        }

        // Bootstrap form validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
