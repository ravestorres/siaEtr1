<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reviewer_id = $_SESSION['user_id'];
    $reviewee_id = intval($_POST['reviewee_id']);
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars($_POST['comment']);

    $stmt = $conn->prepare("INSERT INTO reviews (reviewer_id, reviewee_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $reviewer_id, $reviewee_id, $rating, $comment);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
