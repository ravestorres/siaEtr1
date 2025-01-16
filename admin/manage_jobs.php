<?php
session_start();
require_once '../config.php';

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Check if a decline action is requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'decline') {
    // Get the job ID from the form
    $job_id = $_POST['job_id'];

    // Prepare the update statement to change the status to 'declined'
    $stmt = $conn->prepare("UPDATE jobs SET status = 'declined' WHERE id = ?");
    $stmt->bind_param("i", $job_id);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: manage_jobs.php?status=declined");
        exit;
    } else {
        // Redirect back with an error message
        header("Location: manage_jobs.php?status=error");
        exit;
    }

    // Close the statement
    $stmt->close();
}

// Fetch all jobs from the database
$stmt = $conn->prepare("SELECT jobs.id, jobs.title, jobs.description, jobs.budget, jobs.status, jobs.created_at, users.name AS client_name
                        FROM jobs
                        JOIN users ON jobs.client_id = users.id");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <!-- Manage Jobs Content -->
    <div class="container">
        <h2 class="mb-4">Manage Jobs</h2>
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'declined'): ?>
                <div class="alert alert-success">Job has been successfully declined!</div>
            <?php elseif ($_GET['status'] == 'error'): ?>
                <div class="alert alert-danger">There was an error declining the job. Please try again.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mt-4">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>$<?php echo number_format($row['budget'], 2); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
                                <td>
                                <?php if ($row['status'] != 'declined'): ?>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#declineModal<?php echo $row['id']; ?>">Decline</button>

                                        <!-- Decline Modal -->
                                        <div class="modal fade" id="declineModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="declineModalLabel">Decline Job</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="manage_jobs.php" method="POST">
                                                            <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="action" value="decline">
                                                            <div class="mb-3">
                                                                <label for="decline_comment" class="form-label">Reason for Declining</label>
                                                                <textarea class="form-control" id="decline_comment" name="decline_comment" rows="4" required></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-danger">Decline Job</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Declined</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No jobs posted yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $stmt->close(); ?>
