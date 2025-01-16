<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Successfully deleted user
        header("Location: manage_users.php?msg=User deleted successfully");
    } else {
        // Error while deleting
        header("Location: manage_users.php?error=Failed to delete user");
    }
    $stmt->close();
}
?>
