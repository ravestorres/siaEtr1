<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] == 'freelancer') {
    $job_id = intval($_POST['job_id']);
    $proposal_details = htmlspecialchars($_POST['proposal_details']);
    $proposed_rate = floatval($_POST['proposed_rate']);
    $freelancer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO proposals (job_id, freelancer_id, proposal_details, proposed_rate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $job_id, $freelancer_id, $proposal_details, $proposed_rate);

    if ($stmt->execute()) {
        echo "Proposal submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
