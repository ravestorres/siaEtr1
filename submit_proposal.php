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
    // Sanitize and validate inputs
    $proposal_details = htmlspecialchars(trim($_POST['proposal_details']));
    $proposed_rate = floatval($_POST['proposed_rate']);
    
    // Additional validation for proposed_rate
    if ($proposed_rate <= 0) {
        die("Please provide a valid rate.");
    }

    if (empty($proposal_details)) {
        die("Proposal details cannot be empty.");
    }

    $freelancer_id = $_SESSION['user_id'];

    // File upload logic for attachments
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = $_FILES['attachment']['name'];
        $fileType = $_FILES['attachment']['type'];
        
        // Validate file type (only PDF allowed)
        $allowedTypes = ['application/pdf'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Only PDF files are allowed for attachments.");
        }

        // Define upload directory and move the uploaded file
        $uploadDir = 'uploads/'; // Make sure this directory is writable
        $fileDest = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpPath, $fileDest)) {
            echo "File uploaded successfully: $fileName<br>";
        } else {
            echo "Error: There was an issue uploading the attachment.";
        }
    }

    // File upload logic for profile highlights
    if (isset($_FILES['profile_highlight']) && $_FILES['profile_highlight']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_highlight']['tmp_name'];
        $fileName = $_FILES['profile_highlight']['name'];
        $fileType = $_FILES['profile_highlight']['type'];
        
        // Validate file type (only PDF allowed)
        $allowedTypes = ['application/pdf'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Only PDF files are allowed for profile highlights.");
        }

        // Define upload directory and move the uploaded file
        $uploadDir = 'uploads/profile_highlights/'; // Ensure this directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        $fileDest = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpPath, $fileDest)) {
            echo "Profile highlight uploaded successfully: $fileName<br>";
        } else {
            echo "Error: There was an issue uploading the profile highlight.";
        }
    }

    // Prepare the SQL query with error handling
    if ($stmt = $conn->prepare("INSERT INTO proposals (job_id, freelancer_id, proposal_details, proposed_rate) VALUES (?, ?, ?, ?)")) {
        $stmt->bind_param("iisd", $job_id, $freelancer_id, $proposal_details, $proposed_rate);

        if ($stmt->execute()) {
            echo "Proposal submitted successfully! <a href='dashboard.php'>Back to Dashboard</a>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles to apply Poppins font -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .custom-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            width: 50%;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .custom-grey {
            background-color: #f2f2f2;
        }
    </style>

    <title>Submit Proposal</title>
</head>

<body>
    <div class="container custom-container">
        <div class="form-container">
            <h2 class="text-center mb-4">Submit Proposal for Job ID: <?php echo $job_id; ?></h2>

            <!-- Job Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <!-- Card -->
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

                <!-- Submit Proposal Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Submit Proposal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
