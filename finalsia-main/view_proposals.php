<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['job_id'])) {
    die("Job ID is required.");
}

$job_id = intval($_GET['job_id']);
$stmt = $conn->prepare("SELECT proposals.*, users.name AS freelancer_name, 
                        proposals.attachment, proposals.profile_highlight
                        FROM proposals 
                        JOIN users ON proposals.freelancer_id = users.id 
                        WHERE job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Proposals</title>
</head>

<body>
    <div class="container mt-5">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="d-flex align-items-center" style="justify-content: space-between;">
            <!-- Back button aligned to the left -->
            <h2>Proposals for Job ID: <?php echo $job_id; ?></h2>
            <a href="manage_work.php" class="btn btn-secondary me-3" style="background-color: green;">Back</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Freelancer</th>
                        <th>Details</th>
                        <th>Rate</th>
                        <th>Status</th>
                        <th>Attachments</th>
                        <th>Profile Highlights</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['freelancer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['proposal_details']); ?></td>
                            <td>$<?php echo number_format($row['proposed_rate'], 2); ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>

                            <!-- Attachments and Profile Highlights -->
                            <td>
    <?php if ($row['attachment']): ?>
        <a href="<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank" class="btn btn-info btn-sm">View Attachment</a>
    <?php else: ?>
        No attachment
    <?php endif; ?>
</td>

<td>
    <?php if ($row['profile_highlight']): ?>
        <a href="<?php echo htmlspecialchars($row['profile_highlight']); ?>" target="_blank" class="btn btn-info btn-sm">View Profile Highlight</a>
    <?php else: ?>
        No profile highlight
    <?php endif; ?>
</td>

                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="contract.php?proposal_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Accept Proposal</a>
                                    <a href="contract.php?proposal_id=<?php echo $row['id']; ?>&action=decline" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to decline this proposal?');">
                                       Decline Proposal
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No proposals found for this job.</p>
        <?php endif;
        $stmt->close();
        ?>
    </div>
</body>

</html>
