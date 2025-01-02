<?php
session_start();
header("Content-Type: application/json");

require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['job_id'])) {
    $freelancer_id = intval($_SESSION['user_id']);
    $job_id = intval($_POST['job_id']);

    if (!$freelancer_id || !$job_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid freelancer ID or job ID']);
        exit;
    }

    // Check if the job is already saved
    $stmt = $conn->prepare("SELECT 1 FROM saved_jobs WHERE freelancer_id = ? AND job_id = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $freelancer_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflict
        echo json_encode(['error' => 'Job already saved']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Save the job
    $stmt = $conn->prepare("INSERT INTO saved_jobs (freelancer_id, job_id) VALUES (?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $freelancer_id, $job_id);

    if ($stmt->execute()) {
        // Successful save, redirect to the saved_jobs page
        header("Location: saved_jobs.php");  // Redirect to saved_jobs.php
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save job', 'details' => $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
