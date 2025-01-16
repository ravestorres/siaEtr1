<?php
    session_start();
    require_once 'config.php';

    // Check if the user is logged in and is a client
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
        header("Location: login.php");
        exit;
    }

    // Validate the `proposal_id` and `action` parameters from the query string
    if (!isset($_GET['proposal_id']) || !isset($_GET['action'])) {
        die("Proposal ID and action are required.");
    }
    

    $proposal_id = intval($_GET['proposal_id']);
    $action = $_GET['action']; // Accept or Decline
    $client_id = $_SESSION['user_id'];

    // Fetch the proposal details and validate it
    $stmt = $conn->prepare("SELECT proposals.id AS proposal_id, proposals.proposal_details, proposals.status, 
                            proposals.job_id, users.name AS freelancer_name 
                            FROM proposals 
                            JOIN jobs ON proposals.job_id = jobs.id 
                            JOIN users ON proposals.freelancer_id = users.id 
                            WHERE proposals.id = ? AND jobs.client_id = ? AND proposals.status = 'pending'");
    $stmt->bind_param("ii", $proposal_id, $client_id);
    $stmt->execute();
    $proposal = $stmt->get_result()->fetch_assoc();

    if (!$proposal) {
        die("Invalid or already processed proposal.");
    }

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Handle Accept Proposal action
        if ($action == 'accept') {
            // Insert a new contract
            $terms = "Contract for Job ID: " . $proposal['job_id'] . " with Freelancer: " . $proposal['freelancer_name'];
            $status = 'active';
            $stmt = $conn->prepare("INSERT INTO contracts (proposal_id, client_id, terms, status, created_at) 
                                    VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiss", $proposal['proposal_id'], $client_id, $terms, $status);
            $stmt->execute();

            // Update the proposal status to 'accepted'
            $stmt = $conn->prepare("UPDATE proposals SET status = 'accepted' WHERE id = ?");
            $stmt->bind_param("i", $proposal_id);
            $stmt->execute();
            
            // Commit the transaction
            $conn->commit();
            
            // Redirect with success message
            header("Location: view_proposals.php?job_id=" . $proposal['job_id'] . "&success=Proposal accepted and contract created.");
            exit;
        }

        // Handle Decline Proposal action
        if ($action == 'decline') {
            // Update the proposal status to 'rejected'
            $stmt = $conn->prepare("UPDATE proposals SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param("i", $proposal_id);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();
            
            // Redirect with success message
            header("Location: view_proposals.php?job_id=" . $proposal['job_id'] . "&success=Proposal rejected.");
            exit;
        }


        // If invalid action
        die("Invalid action.");
    } catch (Exception $e) {
        // Roll back the transaction on error
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        die("An error occurred while processing your request. Please try again later.");
    }
?>
