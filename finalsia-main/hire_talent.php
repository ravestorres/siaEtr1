<?php
session_start();
require_once 'config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user session details
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Fetch freelancers' data
$sql = "SELECT id, name, email, profile_picture, created_at FROM users WHERE role = 'freelancer'";
$result = $conn->query($sql);

$freelancers = []; 
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $freelancers[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Google Fonts and Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
               body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header .logo {
            font-weight: bold;
            font-size: 1.8rem;
            color: #333;
            text-decoration: none;
        }
        .header .nav-item {
            font-size: 1rem;
            margin: 0 10px;
            text-decoration: none;
            color: #333;
        }
        .header .nav-item:hover {
            color: #007bff;
        }
        .header .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .profile-container {
            position: relative;
            display: inline-block;
        }
        .profile-menu {
            position: absolute;
            top: 50px;
            right: 0;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
        }
        .profile-container:hover .profile-menu {
            display: block;
        }
        .profile-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .profile-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .dropdown-menu {
            margin-top: 0;
        }
        .freelancer-card {
            position: relative;
        }
        .heart-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px; /* Increased size */
            color: red;
            cursor: pointer;
        }
        .heart-button:hover {
            color: darkred;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="container d-flex align-items-center">
            <!-- Logo -->
            <a href="#" class="logo">
                <img src="img/workwise-logo.png" alt="Workwise Logo" style="height: 40px; margin-right: 10px;">
            </a>
            <!-- Navigation -->
            <nav class="d-flex ms-4">
                <?php if ($user_role === 'freelancer'): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="#" class="nav-item dropdown-toggle" id="findWorkDropdown" role="button" aria-expanded="false">
                            Find Work
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="findWorkDropdown">
                            <li><a class="dropdown-item" href="#active">Find Work</a></li>
                            <li><a class="dropdown-item" href="saved_jobs.php">Saved Jobs</a></li>
                            <li><a class="dropdown-item" href="submit_proposal.php">Proposals</a></li>
                        </ul>
                    </div>
                    <a href="deliver_work.php" class="nav-item">Deliver Work</a>
                    <a href="manage_finances.php" class="nav-item">Manage Finances</a>
                    <a href="messages.php" class="nav-item">Messages</a>
                <?php elseif ($user_role === 'client'): ?>
                    <a href="hire_talent.php" class="nav-item">Hire Talent</a>
                    <div class="dropdown hover-dropdown">
                        <a href="#" class="nav-item dropdown-toggle" id="manageWorkDropdown" role="button" aria-expanded="false">
                            Manage Works
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="manageWorkDropdown">
                            <li><a class="dropdown-item" href="ongoing_projects.php">Ongoing Projects</a></li>
                            <li><a class="dropdown-item" href="completed_projects.php">Completed Projects</a></li>
                        </ul>
                    </div>
                    <a href="all_job_posts.php" class="nav-item">All Job Posts</a>
                    <a href="all_contracts.php" class="nav-item">All Contracts</a>
                <?php endif; ?>
            </nav>
            <!-- User Profile -->
            <div class="ms-auto profile-container">
                <div class="profile-icon">
                    <i class="icon bi bi-person-circle"></i>
                </div>
                <div class="profile-menu">
                    <div class="profile-header">
                        <strong><?php echo htmlspecialchars($user_name); ?></strong>
                        <p><?php echo htmlspecialchars($user_role); ?></p>
                    </div>
                    <a href="profile.php" class="dropdown-item">Your Profile</a>
                    <a href="membership_plan.php" class="dropdown-item">Membership Plan</a>
                    <a href="connects.php" class="dropdown-item">Connects</a>
                    <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center">Freelancers</h1>
        <div class="row mt-4">
            <?php if (!empty($freelancers)): ?>
                <?php foreach ($freelancers as $freelancer): ?>
                    <div class="col-md-4">
                        <div class="card mb-4" onclick="window.location='profiled.php?id=<?php echo $freelancer['id']; ?>'">
                            <img src="<?php echo htmlspecialchars($freelancer['profile_picture']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($freelancer['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($freelancer['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($freelancer['email']); ?></p>
                                <p class="text-muted">Joined on <?php echo date('F j, Y', strtotime($freelancer['created_at'])); ?></p>
                                <button class="btn-invite">Invite to Job</button>
                                <button class="btn-heart" onclick="toggleHeart(this)">
                                    <i class="heart-icon bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No freelancers found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleHeart(button) {
            const icon = button.querySelector('.heart-icon');
            icon.classList.toggle('liked');
        }
    </script>
</body>
</html>
