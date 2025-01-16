<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['freelancer_id']) || !isset($_POST['job_id'])) {
        die("Freelancer ID and Job ID are required.");
    }

    $freelancer_id = intval($_POST['freelancer_id']);
    $job_id = intval($_POST['job_id']);
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);

    if ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } else {
        $stmt = $conn->prepare("INSERT INTO ratings (freelancer_id, job_id, client_id, rating, feedback) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $freelancer_id, $job_id, $_SESSION['user_id'], $rating, $feedback);

        if ($stmt->execute()) {
            header("Location: view_proposals.php?job_id=$job_id&success=Freelancer rated successfully!");
            exit;
        } else {
            $error = "Failed to submit rating. Please try again.";
        }
    }
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
                                    <a href="contract.php?proposal_id=<?php echo $row['id']; ?>&action=accept" class="btn btn-success btn-sm">Accept Proposal</a>
                                    <a href="contract.php?proposal_id=<?php echo $row['id']; ?>&action=decline" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to decline this proposal?');">Decline Proposal</a>
                                <?php elseif ($row['status'] == 'accepted'): ?>
                                    <!-- Open the modal to rate freelancer -->
                                    <button class="btn btn-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ratingModal" 
                                            data-freelancer-id="<?php echo $row['freelancer_id']; ?>"
                                            data-job-id="<?php echo $job_id; ?>">Rate Freelancer</button>
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

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate Freelancer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <!-- Hidden fields to store freelancer_id and job_id -->
                        <input type="hidden" id="modalFreelancerId" name="freelancer_id">
                        <input type="hidden" id="modalJobId" name="job_id">
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (for the modal to work) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Set freelancer_id and job_id in the modal form when the Rate Freelancer button is clicked
        document.addEventListener('DOMContentLoaded', function () {
            const rateButtons = document.querySelectorAll('[data-bs-target="#ratingModal"]');
            rateButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const freelancerId = this.getAttribute('data-freelancer-id');
                    const jobId = this.getAttribute('data-job-id');
                    
                    document.getElementById('modalFreelancerId').value = freelancerId;
                    document.getElementById('modalJobId').value = jobId;
                });
            });
        });
    </script>
</body>
</html
