<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch basic stats: number of completed jobs and job success rate
$stmt = $conn->prepare("SELECT COUNT(*) AS job_count, AVG(success_rate) AS avg_success_rate FROM jobs WHERE freelancer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Stats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */
        .stat-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .stat-box h4 {
            margin-bottom: 10px;
        }
        .stat-box p {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">My Stats</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="stat-box">
                    <h4>Completed Jobs</h4>
                    <p><?php echo $stats['job_count']; ?> Jobs</p>
                </div>
            </div>
            
            </div>
        </div>
    </div>
</body>
</html>
