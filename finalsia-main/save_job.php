<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['job_id'])) {
    $freelancer_id = intval($_SESSION['user_id']);
    $job_id = intval($_POST['job_id']);

    if (!$freelancer_id || !$job_id) {
        echo json_encode(['error' => 'Invalid freelancer ID or job ID']);
        exit;
    }

    // Check if the job is already saved
    $stmt = $conn->prepare("SELECT 1 FROM saved_jobs WHERE freelancer_id = ? AND job_id = ?");
    if (!$stmt) {
        echo json_encode(['error' => 'Database error', 'details' => $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $freelancer_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the job is already saved
        echo json_encode(['error' => 'Job already saved']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Save the job if it's not already saved
    $stmt = $conn->prepare("INSERT INTO saved_jobs (freelancer_id, job_id) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(['error' => 'Database error', 'details' => $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $freelancer_id, $job_id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Job successfully saved',
            'job_id' => $job_id,
            'freelancer_id' => $freelancer_id
        ]);
    } else {
        echo json_encode(['error' => 'Failed to save job', 'details' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
