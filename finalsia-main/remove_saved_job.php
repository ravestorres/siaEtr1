<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: login.php");
    exit;
}

$freelancer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = intval($_POST['job_id']);

    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE freelancer_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $freelancer_id, $job_id);

    if ($stmt->execute()) {
        header("Location: saved_jobs.php");
        exit;
    } else {
        echo "Error removing saved job.";
    }

    $stmt->close();
}
?>
