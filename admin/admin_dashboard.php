<?php
session_start();
require_once '../config.php';

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=freelance_platform", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query for statistics
    $totalClientsQuery = $pdo->query("SELECT COUNT(*) AS total_clients FROM users WHERE role = 'client'");
    $totalClients = $totalClientsQuery->fetch(PDO::FETCH_ASSOC)['total_clients'];

    $totalFreelancersQuery = $pdo->query("SELECT COUNT(*) AS total_freelancers FROM users WHERE role = 'freelancer'");
    $totalFreelancers = $totalFreelancersQuery->fetch(PDO::FETCH_ASSOC)['total_freelancers'];

    $totalJobsQuery = $pdo->query("SELECT COUNT(*) AS total_jobs FROM jobs");
    $totalJobs = $totalJobsQuery->fetch(PDO::FETCH_ASSOC)['total_jobs'];

    $declinedJobsQuery = $pdo->query("SELECT COUNT(*) AS declined_jobs FROM jobs WHERE status = 'declined'");
    $declinedJobs = $declinedJobsQuery->fetch(PDO::FETCH_ASSOC)['declined_jobs'];

    $availableJobsQuery = $pdo->prepare("
        SELECT COUNT(*) AS available_jobs
        FROM jobs j
        LEFT JOIN proposals p ON j.id = p.job_id AND p.status = 'accepted'
        WHERE j.status = 'open' AND p.id IS NULL
    ");
    $availableJobsQuery->execute();
    $availableJobs = $availableJobsQuery->fetch(PDO::FETCH_ASSOC)['available_jobs'];

   // Fetch job creation counts grouped by date
   $jobUsageQuery = $pdo->query("
   SELECT DATE(created_at) AS date, COUNT(*) AS job_count
   FROM jobs
   GROUP BY DATE(created_at)
   ORDER BY DATE(created_at)
    ");
    $jobUsageData = $jobUsageQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch proposal creation counts grouped by date
    $proposalUsageQuery = $pdo->query("
    SELECT DATE(created_at) AS date, COUNT(*) AS proposal_count
    FROM proposals
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at)
    ");
    $proposalUsageData = $proposalUsageQuery->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the chart
    $chartLabels = [];
    $jobCounts = [];
    $proposalCounts = [];

    // Merge job and proposal data by date
    $usageData = [];
    foreach ($jobUsageData as $row) {
    $usageData[$row['date']]['jobs'] = $row['job_count'];
    }
    foreach ($proposalUsageData as $row) {
    $usageData[$row['date']]['proposals'] = $row['proposal_count'];
    }

    // Normalize the data
    foreach ($usageData as $date => $counts) {
    $chartLabels[] = $date;
    $jobCounts[] = $counts['jobs'] ?? 0; // Default to 0 if no jobs
    $proposalCounts[] = $counts['proposals'] ?? 0; // Default to 0 if no proposals
    } 
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
// Query to count the number of reports
$reportsQuery = $pdo->query("SELECT COUNT(*) AS total_reports FROM reports");
$totalReports = $reportsQuery->fetch(PDO::FETCH_ASSOC)['total_reports'];

// Query to count the number of reports
$reportsQuery1 = $pdo->query("SELECT COUNT(*) AS total_reported FROM reports_client");
$totalReportss = $reportsQuery1->fetch(PDO::FETCH_ASSOC)['total_reported'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #3a3f58;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 24px;
            letter-spacing: 2px;
        }

        .navbar-nav .nav-link {
            color: white !important;
            padding: 12px 20px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #f4a261 !important;
            color: #fff !important;
            border-radius: 5px;
            transform: scale(1.05);
        }

        .navbar-toggler-icon {
            background-color: white !important;
        }

        .navbar-collapse {
            justify-content: flex-end;
        }

        /* Button Styles */
        .logout-btn {
            background-color: #e76f51;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #d35b39;
        }

        /* Header Section */
        .header-section {
            margin-top: 30px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .header-section h2 {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
        }

        .header-section p {
            color: #718096;
            font-size: 18px;
        }

        .icon-container {
            min-width: 50px; /* Ensure consistent width for icons */
            text-align: center;
        }

        /* Card Layout */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 15px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
        }
        .card-text {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }
        .card-clients {
            background-color: #f0f8ff; /* Light blue */
        }

        .card-freelancers {
            background-color: #e6ffe6; /* Light green */
        }

        .card-jobs {
            background-color: #fff5e6; /* Light orange */
        }

        .card-declined-jobs {
            background-color: #ffe6e6; /* Light red */
        }

        .card-available-jobs {
            background-color: #e6f7ff; /* Light cyan */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9fafb;
        }

        /* Footer Section */
        footer {
            text-align: center;
            padding: 15px;
            background-color: #3a3f58;
            color: white;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
        .card-reports {
    background-color: #f0f8ff; /* Light blue or any other color you prefer */
}

    </style>
</head>

<body>
    <!-- Admin Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_jobs.php">Manage Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Manage Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout-btn" href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Dashboard Content -->
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h2>Welcome to Admin Dashboard</h2>
            <p>This is the main dashboard page for the admin.</p>
        </div>

       <!-- Statistics Section -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card card-clients d-flex flex-row align-items-center p-3">
            <div class="icon-container text-primary">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Clients</h5>
                <p class="card-text fs-4 mb-0"><?php echo $totalClients; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-freelancers d-flex flex-row align-items-center p-3">
            <div class="icon-container text-success">
                <i class="fas fa-user-tie fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Freelancers</h5>
                <p class="card-text fs-4 mb-0"><?php echo $totalFreelancers; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-jobs d-flex flex-row align-items-center p-3">
            <div class="icon-container text-warning">
                <i class="fas fa-briefcase fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Posted Jobs</h5>
                <p class="card-text fs-4 mb-0"><?php echo $totalJobs; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-declined-jobs d-flex flex-row align-items-center p-3">
            <div class="icon-container text-danger">
                <i class="fas fa-ban fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Declined Jobs</h5>
                <p class="card-text fs-4 mb-0"><?php echo $declinedJobs; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-3">
        <div class="card card-available-jobs d-flex flex-row align-items-center p-3">
            <div class="icon-container text-info">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Available Jobs</h5>
                <p class="card-text fs-4 mb-0"><?php echo $availableJobs; ?></p>
            </div>
        </div>
    </div>
    <!-- New Reports Card -->
    <div class="col-md-3 mt-3">
        <div class="card card-reports d-flex flex-row align-items-center p-3">
            <div class="icon-container text-dark">
                <i class="fas fa-flag fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Reported Freelancer</h5>
                <p class="card-text fs-4 mb-0"><?php echo $totalReports; ?></p>
            </div>
        </div>
    </div>

    <!-- New Reports Card -->
    <div class="col-md-3 mt-3">
        <div class="card card-reports d-flex flex-row align-items-center p-3">
            <div class="icon-container text-dark">
                <i class="fas fa-flag fa-2x"></i>
            </div>
            <div class="ms-auto text-end">
                <h5 class="card-title mb-1">Reported Client</h5>
                <p class="card-text fs-4 mb-0"><?php echo $totalReportss; ?></p>
            </div>
        </div>
    </div>
</div>
</div>

    

            <div class="container mt-5">
                <h3>System Usage Statistics</h3>
                <canvas id="usageChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Prepare data for the chart
                const labels = <?php echo json_encode($chartLabels); ?>;
                const jobData = <?php echo json_encode($jobCounts); ?>;
                const proposalData = <?php echo json_encode($proposalCounts); ?>;

                // Create the chart
                const ctx = document.getElementById('usageChart').getContext('2d');
                const usageChart = new Chart(ctx, {
                    type: 'line', // Line chart
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Jobs Created',
                                data: jobData,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                fill: true,
                                tension: 0.3,
                            },
                            {
                                label: 'Proposals Created',
                                data: proposalData,
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                fill: true,
                                tension: 0.3,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'System Usage Over Time'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Count'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
