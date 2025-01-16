<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'freelancer') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['job_id'])) {
    die("Job ID is required.");
}

$job_id = intval($_GET['job_id']);

// Fetch job details to display the card
$stmt = $conn->prepare("SELECT id, title, description, budget FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Job not found.");
}

$row = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proposal_details = htmlspecialchars(trim($_POST['proposal_details']));
    $proposed_rate = floatval($_POST['proposed_rate']);
    
    if ($proposed_rate <= 0) {
        $modalMessage = "Please provide a valid rate.";
        $proposalSubmitted = false;
    } elseif (empty($proposal_details)) {
        $modalMessage = "Proposal details cannot be empty.";
        $proposalSubmitted = false;
    } else {
        $freelancer_id = $_SESSION['user_id'];

   // Check if the file upload was successful for attachment and profile highlight
$attachmentPath = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['attachment']['tmp_name'];
    $fileName = $_FILES['attachment']['name'];
    $fileDest = 'uploads/' . basename($fileName);
    move_uploaded_file($fileTmpPath, $fileDest);
    $attachmentPath = $fileDest;
}

$profileHighlightPath = null;
if (isset($_FILES['profile_highlight']) && $_FILES['profile_highlight']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_highlight']['tmp_name'];
    $fileName = $_FILES['profile_highlight']['name'];
    $fileDest = 'uploads/profile_highlights/' . basename($fileName);
    move_uploaded_file($fileTmpPath, $fileDest);
    $profileHighlightPath = $fileDest;
}



    // Prepare the SQL query with error handling
    if ($stmt = $conn->prepare("INSERT INTO proposals (job_id, freelancer_id, proposal_details, proposed_rate, attachment, profile_highlight) VALUES (?, ?, ?, ?,?,?)")) {
        $stmt->bind_param("iisdss", $job_id, $freelancer_id, $proposal_details, $proposed_rate, $attachmentPath, $profileHighlightPath);

        if ($stmt->execute()) {
            $modalMessage = "Proposal submitted successfully!";
            $proposalSubmitted = true;
        } else {
            $modalMessage = "Error: " . $stmt->error;
            $proposalSubmitted = false;
        }
        $stmt->close();
    } else {
        $modalMessage = "Error preparing statement: " . $conn->error;
        $proposalSubmitted = false;
    }

    $conn->close();
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Submit Proposal</title>
</head>

<body>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Submit Proposal</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Submit Proposal for Job ID: <?php echo $job_id; ?></h2>

        <!-- Job Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card custom-grey">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Budget:</strong> $<?php echo number_format($row['budget'], 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proposal Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Proposal Details</label>
                <textarea name="proposal_details" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Proposed Rate</label>
                <input type="number" name="proposed_rate" class="form-control" step="0.01" min="0.01" required>
            </div>

            <!-- Attachments Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-grey">
                        <div class="card-body">
                            <h5 class="card-title">Attachments</h5>
                            <p class="card-text">Upload your PDF file (max size: 20MB):</p>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Choose a PDF file</label>
                                <input type="file" name="attachment" id="attachment" class="form-control" accept="application/pdf">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Highlights Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-grey">
                        <div class="card-body">
                            <h5 class="card-title">Profile Highlights</h5>
                            <p class="card-text">Upload your profile highlight as a PDF (no size limit):</p>
                            <div class="mb-3">
                                <label for="profile_highlight" class="form-label">Choose a PDF file</label>
                                <input type="file" name="profile_highlight" id="profile_highlight" class="form-control" accept="application/pdf">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit Proposal</button>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Proposal Submission Status</h5>
                    <!-- No close button in header, we will add it below -->
                </div>
                <div class="modal-body" id="modalMessage">
                    <!-- Dynamic message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <!-- Button goes to dashboard -->
                    <a href="proposals.php" class="btn btn-primary">Go to Proposals</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Display the modal after form submission
    <?php if (isset($proposalSubmitted) && $proposalSubmitted): ?>
        var modalMessage = "<?php echo addslashes($modalMessage); ?>";
        var modal = new bootstrap.Modal(document.getElementById('responseModal'));
        document.getElementById('modalMessage').textContent = modalMessage;
        modal.show();
    <?php endif; ?>
    </script>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>

</html>

