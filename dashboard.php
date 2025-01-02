<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
    // Clear the message after displaying
    unset($_SESSION['message']);
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to Google Fonts for Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Apply Poppins font globally */
            background-color: #f9f9f9;
        }

        .header {
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
            font-weight: bold;                
            font-size: 1.8rem;                
            color: #333;                       
            text-decoration: none;            
        }

        .header .search-bar {
            flex-grow: 1;
            margin: 0 20px;
        }

        .header .search-bar input {
            width: 100%;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 18px;
        }

        .header .nav-item {
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
            font-size: 1rem;                  
            color: #333;                       
            text-decoration: none;            
            margin: 0 10px;                   
        }

        .header .nav-item:hover {
            color: #007bff;                   
        }

        .header .user-profile {
            display: flex;
            align-items: center;
            color: #333;
        }

        .header .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .icon {
            font-size: 1.2rem;
            margin: 0 10px;
            color: #333;
            cursor: pointer;
        }

        .icon:hover {
            color: #007bff;
        }

        .header .d-flex.ms-auto {
            gap: 3px; 
        }

        .header .icon {
            font-size: 1.5rem;
            margin: 0 10px;
            color: #333;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none; 
            position: absolute; 
            z-index: 1000; 
        }

        .dropdown-item {
            font-size: 1rem;   
            padding: 10px;    
        }

        .dropdown-item:hover {
            background-color: #007bff; 
            color: white;               
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0; 
        }

        .custom-grey {
            background-color: #f0f0f0; 
            color: #333; 
        }

        .profile-container {
            position: relative;
            display: inline-block;
        }

        .profile-icon {
            cursor: pointer;
            font-size: 2rem;
        }

        .profile-menu {
            position: absolute;
            top: 50px;
            right: 0;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            z-index: 1000;
        }

        .profile-header {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-header i {
            font-size: 2rem;
            margin-right: 10px;
        }

        .role {
            font-size: 0.85rem;
            color: gray;
            margin: 0;
        }

        .profile-dropdown .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .profile-dropdown .menu-item:hover {
            background-color: #f0f0f0;
        }
        .logo-img {
            height: 40px; /* Adjust the logo height */
            margin-right: 10px; /* Space between logo and title */
        }

    </style>
</head>

    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="container d-flex align-items-center">
         <!-- Logo and Navigation Links (move to the left) -->
<div class="d-flex align-items-center me-auto">
    <a href="dashboard.php" class="logo">
        <img src="img/workwise-logo.png" alt="Workwise Logo" class="logo-img" />
        
    </a>


    <nav class="d-flex ms-4">
    <?php if ($user_role == 'freelancer'): ?>
        <!-- Freelancer Navigation -->
        <div class="dropdown hover-dropdown">
            <a href="#" class="nav-item dropdown-toggle <?php echo ($active_nav == 'find_work') ? 'active' : ''; ?>" id="findWorkDropdown" role="button" aria-expanded="false">
                Find Work
            </a>
            <ul class="dropdown-menu" aria-labelledby="findWorkDropdown">
                <li><a class="dropdown-item" href="dashboard.php">Find work</a></li>
                <li><a class="dropdown-item" href="saved_jobs.php">Saved jobs</a></li>
                <li><a class="dropdown-item" href="submit_proposal.php">Proposal and offers</a></li>
            </ul>
        </div>
        <a href="deliver_work.php" class="nav-item <?php echo ($active_nav == 'deliver_work') ? 'active' : ''; ?>">Deliver Work</a>
        <a href="manage_finances.php" class="nav-item <?php echo ($active_nav == 'manage_finances') ? 'active' : ''; ?>">Manage Finances</a>
        <a href="messages.php" class="nav-item <?php echo ($active_nav == 'messages') ? 'active' : ''; ?>">Messages</a>
    <?php elseif ($user_role == 'client'): ?>
        <!-- Client Navigation -->
        <a href="hire_talent.php" class="nav-item <?php echo ($active_nav == 'hire_talent') ? 'active' : ''; ?>">Hire Talent</a>
        <div class="dropdown hover-dropdown">
            <a href="#" class="nav-item dropdown-toggle <?php echo ($active_nav == 'manage_work') ? 'active' : ''; ?>" id="manageWorkDropdown" role="button" aria-expanded="false">
                Manage Works
            </a>
            <ul class="dropdown-menu" aria-labelledby="manageWorkDropdown">
                <li><a class="dropdown-item" href="dashboard.php" class="nav-item <?php echo ($active_nav == 'dashboard') ? 'active' : ''; ?>">All Job Posts</a></li>
                <li><a class="dropdown-item" href="all_contracts.php" class="nav-item <?php echo ($active_nav == 'all_contracts') ? 'active' : ''; ?>">All Contracts</a></li>
            </ul>
        </div>
      
    
    <?php endif; ?>
</nav>

            </div>

            <!-- Search Bar, Icons, and Profile (move to the right) -->
            <div class="d-flex ms-auto align-items-center">
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" placeholder="Search for jobs" />
                </div>

                <!-- Icons -->
                <i class="icon bi bi-question"></i>
                <i class="icon bi bi-bell"></i>

                <!-- User Profile -->
                <div class="profile-container">
                    <!-- Profile Icon -->
                    <div class="profile-icon" id="profileIcon">
                        <i class="icon bi bi-person-circle"></i>
                    </div>

                    <!-- Dropdown Menu (Initially Hidden) -->
                    <div class="profile-menu" id="profileMenu" style="display: none;">
                    <a href="profiles.php" class="profile-link">
    <div class="profile-header">
        <i class="icon bi bi-person-circle"></i>
        <div>
            <strong><?php echo htmlspecialchars($user_name); ?></strong>
            <p class="role"><?php echo htmlspecialchars($user_role); ?></p>
        </div>
    </div>
</a>

                        <div class="profile-dropdown">
                            <div class="menu-item">
                                <span>Online for messages</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="menu-item"><i class="icon bi bi-person"></i>  <a href="profile.php">Your profile</a></div>
<div class="menu-item"><i class="icon bi bi-credit-card"></i><a href="membership_plan.php"> Membership Plan</a></div>
<div class="menu-item"><i class="icon bi bi-arrow-repeat"></i><a href="connects.php"> Connects</a></div>
                            <div class="menu-item">
                                <i class="icon bi bi-sun"></i> Theme: Light
                                <i class="icon bi bi-chevron-down"></i>
                            </div>
                            <div class="menu-item"><i class="icon bi bi-gear"></i> Account settings</div>
                            <div class="menu-item">
                            <i class="icon bi bi-box-arrow-right"></i> <a href="logout.php">Log out</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content mt-4">
        <h2>Your Role: <strong><?php echo ucfirst($user_role); ?></strong></h2>

        <?php if ($user_role == 'client'): ?>
            <h3>Your Job Postings</h3>
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE client_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><span class="badge bg-info"><?php echo ucfirst($row['status']); ?></span></td>
                                <td>$<?php echo number_format($row['budget'], 2); ?></td>
                                <td>
                                    <a href="view_proposals.php?job_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View Proposals</a>
                                    <a href="view_contract.php?job_id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">View Contract</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No job postings found. <a href="job_posting.php" class="btn btn-primary">Post a Job</a></p>
            <?php endif;
            $stmt->close();
            ?>
        <?php elseif ($user_role == 'freelancer'): ?>
            <h3>Available Jobs</h3>
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE status = 'open'");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <div class="container">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <!-- Card -->
                                <div class="card custom-grey" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $row['id']; ?>" style="cursor: pointer;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                        <p class="card-text"><strong>Budget:</strong> $<?php echo number_format($row['budget'], 2); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="jobModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="jobModalLabel<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Description:</strong></p>
                                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                                        <p><strong>Budget:</strong> $<?php echo number_format($row['budget'], 2); ?></p>
                                        <p><strong>Job Details:</strong></p>
                                        <p>Additional information about the job can go here (e.g., timeframe, requirements).</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="submit_proposal.php?job_id=<?php echo $row['id']; ?>" class="btn btn-success">Apply Now</a>
                                        <form action="save_job.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-warning">Save Job</button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No jobs available at the moment.</p>
            <?php endif;
            $stmt->close();
            ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>

<script>
    document.querySelectorAll('.save-job-btn').forEach(button => {
    button.addEventListener('click', function () {
        const jobId = this.getAttribute('data-job-id');
        fetch('save_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ job_id: jobId })
        });
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the saved_jobs.php page
                window.location.href = 'saved_jobs.php';
            } else {
                // Display an error message
                const messageDiv = document.getElementById('saveJobMessage');
                messageDiv.innerHTML = 
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${data.error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>;
                messageDiv.scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

</script>

<script>
    // Toggle the dropdown when the profile icon is clicked
    document.getElementById('profileIcon').addEventListener('click', function () {
        const profileMenu = document.getElementById('profileMenu');
        if (profileMenu.style.display === 'none' || profileMenu.style.display === '') {
            profileMenu.style.display = 'block';
        } else {
            profileMenu.style.display = 'none';
        }
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function(event) {
        const profileMenu = document.getElementById('profileMenu');
        const profileIcon = document.getElementById('profileIcon');
        
        if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.style.display = 'none';
        }
    });

     function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.querySelector('.header').classList.toggle('dark-mode');
            document.querySelectorAll('.profile-menu').forEach(function (menu) {
                menu.classList.toggle('dark-mode');
            });
        }
</script>

</html>