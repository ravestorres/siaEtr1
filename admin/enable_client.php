<?php
session_start();
require_once '../config.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Enable client action
if (isset($_GET['id'])) {
    $client_id = $_GET['id'];
    
    // Update the client's penalty status to enable them
    $stmt = $conn->prepare("UPDATE users SET penalty_status = 'enabled', report_count = 0 WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back to manage users page
    header("Location: manage_clients.php");
}
?>
