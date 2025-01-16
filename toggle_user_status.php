<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $current_status = intval($_POST['current_status']);
    $new_status = $current_status ? 0 : 1;

    // Update the user's disabled status
    $stmt = $conn->prepare("UPDATE users SET disabled = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $user_id);
    $stmt->execute();

    header("Location: reports.php");
    exit;
}
