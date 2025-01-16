<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure feedback is provided
    if (!isset($_POST['feedback']) || empty($_POST['feedback'])) {
        echo "Feedback is required.";
        exit;
    }

    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $freelancer_id = $_POST['freelancer_id'];
    $client_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in the session

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating. Please select a rating between 1 and 5.";
        exit;
    }

    // Fetch the job_id based on the client_id from the contracts, proposals, and jobs table
    $stmt = $conn->prepare("
        SELECT j.id  -- job_id from jobs table
        FROM contracts c
        JOIN proposals p ON c.proposal_id = p.id  -- join contracts with proposals
        JOIN jobs j ON p.job_id = j.id  -- join proposals with jobs
        WHERE c.client_id = ? AND c.status = 'active'
    ");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a job exists for this client
    if ($result->num_rows > 0) {
        // Fetch the job_id
        $job = $result->fetch_assoc();
        $job_id = $job['id'];  // job_id from jobs table

        // Insert rating into the database
        $stmt = $conn->prepare("INSERT INTO ratings (freelancer_id, job_id, client_id, rating, feedback, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiss", $freelancer_id, $job_id, $client_id, $rating, $feedback);

        if ($stmt->execute()) {
            $_SESSION['rating_success'] = "Your rating has been submitted successfully!";
        } else {
            $_SESSION['rating_error'] = "An error occurred. Please try again.";
        }
        $stmt->close();
    } else {
        echo "No active job found for this client.";
        exit;
    }

    // Redirect back to the freelancer profile page
    header("Location: view_freelancer.php?id=" . $freelancer_id);
    exit;
}
?>
