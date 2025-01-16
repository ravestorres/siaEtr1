<?php
session_start();
require_once '../config.php';

// // Check if the logged-in user is an admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     header("Location: login.php");
//     exit;
// }

// Validate POST data
if (!isset($_POST['user_id']) || !isset($_POST['current_status'])) {
    header("Location: manage_users.php?error=InvalidInput");
    exit;
}

$userId = intval($_POST['user_id']);
$currentStatus = intval($_POST['current_status']);

// Toggle user status
$newStatus = $currentStatus ? 0 : 1;

if ($newStatus == 0) {
    // If enabling the user, reset report count threshold to 10
    $stmt = $conn->prepare("UPDATE users SET disabled = ?, report_threshold = 10 WHERE id = ?");
} else {
    // If disabling the user, leave the threshold as is
    $stmt = $conn->prepare("UPDATE users SET disabled = ? WHERE id = ?");
}

$stmt->bind_param("ii", $newStatus, $userId);

if ($stmt->execute()) {
    header("Location: reports.php?success=UserStatusUpdated");
} else {
    header("Location: reports.php?error=UpdateFailed");
}

$stmt->close();
$conn->close();
?>
