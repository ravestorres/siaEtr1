<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted report reason
    $client_id = $_POST['client_id'];
    $report_reason = $_POST['report_reason'];
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in the session

    // Validate the report reason
    if (empty($report_reason)) {
        echo "Please provide a reason for the report.";
        exit;
    }

    // Insert report into the database
    $stmt = $conn->prepare("INSERT INTO reports_client (client_id, user_id, report_reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $client_id, $user_id, $report_reason);

    if ($stmt->execute()) {
        $_SESSION['report_success'] = "Your report has been submitted successfully!";
    } else {
        $_SESSION['report_error'] = "An error occurred. Please try again.";
    }

    $stmt->close();

    // Redirect back to the freelancer profile page
    header("Location: freelance_dashboard.php");
    exit;
}
?>
