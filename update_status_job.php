<?php
session_start();
require_once 'config.php';
// update_status.php

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['job_id']) && isset($_POST['status'])) {
    $jobId = $_POST['job_id'];
    $status = $_POST['status'];

    // Assuming you have a database connection $conn
    $stmt = $conn->prepare("UPDATE jobs SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $jobId);
    
    if ($stmt->execute()) {
        // Return the updated status in JSON format
        echo json_encode(['status' => $status]);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
}
?>
