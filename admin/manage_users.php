<?php
session_start();
require_once '../config.php';

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all users from the database
$stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

    <!-- Manage Users Content -->
    <!-- Manage Users Content -->
    <div class="container">
        <h2 class="mb-4">Manage Users</h2>

        <!-- Button to Add User -->
        <div class="mt-4 d-flex justify-content-end">
            <a href="add_user.php" class="btn btn-success mb-3">Add User</a>
        </div>

        <!-- Clients Table -->
        <h3>Clients</h3>
        <div class="mt-4">
            <?php
            // Fetch clients
            $stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE role = 'client'");
            $stmt->execute();
            $clients = $stmt->get_result();
            ?>

            <?php if ($clients->num_rows > 0): ?>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $clients->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="<?php echo $row['id']; ?>" data-user-name="<?php echo $row['name']; ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No clients found.</p>
            <?php endif; ?>
        </div>

        <!-- Freelancers Table -->
        <h3>Freelancers</h3>
        <div class="mt-4">
            <?php
            // Fetch freelancers
            $stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE role = 'freelancer'");
            $stmt->execute();
            $freelancers = $stmt->get_result();
            ?>

            <?php if ($freelancers->num_rows > 0): ?>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $freelancers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="<?php echo $row['id']; ?>" data-user-name="<?php echo $row['name']; ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No freelancers found.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the user <strong id="userName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="delete_user.php" method="POST">
                        <input type="hidden" name="user_id" id="userId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // JavaScript to populate the modal with user information
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var userId = button.getAttribute('data-user-id');
            var userName = button.getAttribute('data-user-name');

            // Set the user info in the modal
            document.getElementById('userName').textContent = userName;
            document.getElementById('userId').value = userId;
        });
    </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database statement
$stmt->close();
?>