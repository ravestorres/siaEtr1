<?php 
session_start();
require_once '../config.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

    // Profile Picture Upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture'];
        $profile_picture_name = time() . '_' . basename($profile_picture['name']);
        $profile_picture_path = 'uploads/' . $profile_picture_name;
        move_uploaded_file($profile_picture['tmp_name'], $profile_picture_path);
    } else {
        $profile_picture_name = null;
    }

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, profile_picture, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $name, $email, $password, $role, $profile_picture_name);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Add New User</title>
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
            <h2 class="text-center mb-4">Add New User</h2>
            <form action="add_user.php" method="POST" enctype="multipart/form-data" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter user name" required>
                    <div class="invalid-feedback">Please provide a valid name.</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter user email" required>
                    <div class="invalid-feedback">Please provide a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    <div class="invalid-feedback">Please provide a password.</div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="client">Client</option>
                        <option value="freelancer">Freelancer</option>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" onchange="previewImage(event)">
                    <img id="profilePicturePreview" class="profile-picture-preview" width="80" style="display:none;" alt="Profile Picture Preview">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Add User</button>
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
