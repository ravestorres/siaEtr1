<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: login.php");
    exit;
}

$job_id = $_GET['job_id'] ?? null;

if ($job_id === null) {
    echo "Job not found.";
    exit;
}

// Fetch job details from the database
$stmt = $conn->prepare("SELECT id, client_id, title, description, budget, requirements, created_at, address FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();
} else {
    echo "Job not found.";
    exit;
}

$stmt->close();

// Fetch client name from the users table
$stmt_client = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt_client->bind_param("i", $job['client_id']);
$stmt_client->execute();
$result_client = $stmt_client->get_result();
$client = $result_client->fetch_assoc();
$client_name = $client ? htmlspecialchars($client['name']) : 'Unknown';
$stmt_client->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    
</style>
<body>
    <div class="container mt-4">


        <h2>Job Details</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                <p class="card-text"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                <p class="card-text"><strong>Budget:</strong> $<?php echo number_format($job['budget'], 2); ?></p>
                <p class="card-text"><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                <p class="card-text"><strong>Posted By:</strong> <?php echo $client_name; ?></p>
                <p class="card-text"><strong>Posted On:</strong> <?php echo date("F j, Y, g:i A", strtotime($job['created_at'])); ?></p>
                <p class="card-text"><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($job['address'])); ?></p>

                <!-- Apply for the job button -->
                <form action="submit_proposal.php" method="GET">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
                    <button type="submit" class="btn btn-success">Apply for this job</button>
                </form>
            </div>
        </div>

        <br>
        <!-- Back button, can also go to the previous page -->
        <!-- <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-secondary">Back</a> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function goBack() {
        window.history.back();
    }
</script>
</body>

</html>
