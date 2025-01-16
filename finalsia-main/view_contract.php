<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header("Location: login.php");
    exit;
}

// Get the client ID from the session
$client_id = $_SESSION['user_id'];

// Fetch contracts related to the logged-in client
$stmt = $conn->prepare("SELECT contracts.id, contracts.proposal_id, contracts.terms, contracts.status, contracts.created_at, proposals.job_id, users.name AS freelancer_name 
                        FROM contracts
                        JOIN proposals ON contracts.proposal_id = proposals.id
                        JOIN users ON proposals.freelancer_id = users.id
                        WHERE contracts.client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Contracts</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Your Contracts</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Contract ID</th>
                        <th>Job ID</th>
                        <th>Freelancer</th>
                        <th>Terms</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['freelancer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['terms']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No contracts found for your account.</p>
        <?php endif; ?>

    </div>
</body>

</html>

<?php
$stmt->close();
?>
