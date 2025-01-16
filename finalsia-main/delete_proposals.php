<?php
session_start();
require_once 'config.php';

// Ensure the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'freelancer') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM proposals WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: proposals.php?msg=Proposal deleted successfully");
        } else {
            header("Location: proposals.php?error=Failed to delete proposal");
        }
        $stmt->close();
    } else {
        header("Location: proposals.php?error=Invalid proposal ID");
    }
} else {
    header("Location: proposals.php?error=No proposal ID provided");
}
?>
