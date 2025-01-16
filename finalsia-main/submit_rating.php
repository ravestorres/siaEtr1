<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted rating and comments
    $freelancer_id = $_POST['freelancer_id'];
    $rating = $_POST['rating'];
    $rating_comments = $_POST['rating_comments'];
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in the session

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating. Please select a rating between 1 and 5.";
        exit;
    }

    // Insert rating into the database
    $stmt = $conn->prepare("INSERT INTO ratings (freelancer_id, user_id, rating, rating_comments, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $freelancer_id, $user_id, $rating, $rating_comments);

    if ($stmt->execute()) {
        $_SESSION['rating_success'] = "Your rating has been submitted successfully!";
    } else {
        $_SESSION['rating_error'] = "An error occurred. Please try again.";
    }

    $stmt->close();

    // Redirect back to the freelancer profile page
    header("Location: view_freelancer.php?id=" . $freelancer_id);
    exit;
}
?>
