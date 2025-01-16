<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: login.php");
    exit;
}
// Get the freelancer's ID and role from the session
$freelancer_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch the freelancer's name from the database
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['name'];
} else {
    // If the user is not found, redirect to login (safety fallback)
    header("Location: login.php");
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
    </style>
</head>

<body>
    <div class="container mt-4">
    <div class="header">
                <div class="container d-flex align-items-center">
                    <!-- Logo and Navigation Links (move to the left) -->
                    <div class="d-flex align-items-center me-auto">
                    <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php"><strong>WorkWise</strong></a>
                        <!-- Navigation Links -->
                        <nav class="d-flex ms-4">
                            <a href="freelance_dashboard.php" class="nav-item">Find Work</a>
                            <a href="saved_jobs.php" class="nav-item">Saved jobs</a>
                            <a href="proposals.php" class="nav-item">Proposals and offers</a>
                        </nav>
                    </div>

                    <!-- Search Bar, Icons, and Profile (move to the right) -->
                    <div class="d-flex ms-auto align-items-center">
                        <!-- Search Bar -->
                        <form id="searchForm">
                            <div class="search-bar">
                                <input type="text" id="searchInput" name="query" placeholder="Search" />
                            </div>
                        </form>
                        <form>
                <!-- ropdown for Skills -->
                <div class="mb-3">
                    <select id="skills" name="skills[]" class="form-select">
                        <option value="Social Media Marketing">Social Media Marketing</option>
                        <option value="Search Engine Optimization">Search Engine Optimization (SEO)</option>
                        <option value="Photography and Editing">Photography and Editing</option>
                        <option value="Blockchain">Blockchain</option>
                        <option value="Graphic Design">Graphic Design</option>
                        <option value="Web Designing and Developing">Web Designing and Developing</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Affiliate Marketing">Affiliate Marketing</option>
                    </select>
                </div>
            </form>
                        <!-- Icons -->
                        <i class="icon bi bi-question"></i>
                        <i class="icon bi bi-bell"></i>

                        <!-- User Profile -->
                        <div class="profile-container">
                            <div class="profile-icon" id="profileIcon">
                                <i class="icon bi bi-person-circle"></i>
                            </div>
                            <div class="profile-menu" id="profileMenu" style="display: none;">
                                <div class="profile-header">
                                    <i class="icon bi bi-person-circle"></i>
                                    <div>
                                        <strong><?php echo htmlspecialchars($user_name); ?></strong>
                                        <p class="role"><?php echo htmlspecialchars($user_role); ?></p>
                                    </div>
                                </div>
                                <div class="profile-dropdown">
                                    <div class="menu-item">
                                        <span>Online for messages</span>
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <a href="profile.php" class="no-decoration">
                                        <div class="menu-item"><i class="icon bi bi-person"></i> Your profile</div>
                                    </a>
                                    <div class="menu-item">
                                        <i class="icon bi bi-sun"></i> Theme: Light
                                        <i class="icon bi bi-chevron-down"></i>
                                    </div>
                                    <div class="menu-item"><i class="icon bi bi-gear"></i> Account settings</div>
                                    <a href="logout.php" class="no-decoration2">
                                        <div class="menu-item">
                                            <i class="icon bi bi-box-arrow-right" style="color: red;"></i> Log out
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <h2>Saved Jobs</h2>

        <?php
        $stmt = $conn->prepare("
            SELECT jobs.id, jobs.title, jobs.description, jobs.budget
            FROM saved_jobs
            JOIN jobs ON saved_jobs.job_id = jobs.id
            WHERE saved_jobs.freelancer_id = ?
        ");
        $stmt->bind_param("i", $freelancer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text"><strong>Budget:</strong> $<?php echo number_format($row['budget'], 2); ?></p>
                                <a href="job_details.php?job_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View Job</a>
                                <form action="remove_saved_job.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No saved jobs found.</p>
        <?php endif;

        $stmt->close();
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
<script>
     document.getElementById('skills').addEventListener('change', function () {
            const selectedSkill = this.value.toLowerCase();  // Get selected skill

            // Fetch job posts and filter based on the selected skill
            filterJobsBySkills(selectedSkill);
        });

        // Function to filter job posts based on selected skill
        function filterJobsBySkills(selectedSkill) {
            const jobCards = document.querySelectorAll('.job-card');  // Get all job cards
            let foundMatch = false;

            jobCards.forEach(card => {
                // Get skills associated with the job post (you can adjust this part based on your HTML structure)
                const jobSkills = Array.from(card.querySelectorAll('.badge')).map(badge => badge.textContent.toLowerCase());

                // If the selected skill matches any skill listed on the job post, show it
                if (selectedSkill === "" || jobSkills.includes(selectedSkill)) {
                    card.style.display = '';  // Show job card
                    foundMatch = true;
                } else {
                    card.style.display = 'none';  // Hide job card
                }
            });

            // If no job matches, display "No results"
            const jobResultsContainer = document.getElementById('jobResults');
            if (!foundMatch) {
                const noResultsMessage = document.createElement('div');
                noResultsMessage.classList.add('alert', 'alert-warning');
                noResultsMessage.textContent = 'No jobs available with the selected skill.';
                jobResultsContainer.appendChild(noResultsMessage);
            } else {
                // Remove the "No results" message if any jobs are displayed
                const existingMessage = jobResultsContainer.querySelector('.alert-warning');
                if (existingMessage) {
                    existingMessage.remove();
                }
            }
        }
    document.querySelectorAll('.save-job-btn').forEach(button => {
    button.addEventListener('click', function () {
        const jobId = this.getAttribute('data-job-id');
        fetch('save_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ job_id: jobId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the saved_jobs.php page
                window.location.href = 'saved_jobs.php';
            } else {
                // Display an error message
                const messageDiv = document.getElementById('saveJobMessage');
                messageDiv.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${data.error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                messageDiv.scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

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

function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

document.getElementById('searchInput').addEventListener('keyup', debounce(function () {
    const query = this.value.toLowerCase();
    const jobCards = document.querySelectorAll('.card');
    let anyMatch = false;

    jobCards.forEach(card => {
        const jobTitle = card.querySelector('.card-title').textContent.toLowerCase();
        const jobDescription = card.querySelector('.card-text').textContent.toLowerCase();

        if (jobTitle.includes(query) || jobDescription.includes(query)) {
            card.style.display = '';
            anyMatch = true;
        } else {
            card.style.display = 'none';
        }
    });

    const noResultsMessage = document.getElementById('noResultsMessage');
    if (!anyMatch) {
        if (!noResultsMessage) {
            const messageDiv = document.createElement('div');
            messageDiv.id = 'noResultsMessage';
            messageDiv.classList.add('alert', 'alert-warning');
            messageDiv.textContent = 'No jobs found matching your search criteria.';
            document.querySelector('.container').appendChild(messageDiv);
        }
    } else if (noResultsMessage) {
        noResultsMessage.remove();
    }
}, 300));
</script>
</html>
