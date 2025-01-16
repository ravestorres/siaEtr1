<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <!-- Main Content -->
    <div class="container main-content mt-4">
        <?php if ($user_role == 'freelancer'): ?>
            <div class="header">
                <div class="container d-flex align-items-center">
                    <div class="d-flex align-items-center me-auto">
                    <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php"><strong>WorkWise</strong></a>
                        <!-- <a href="#" class="logo">WorkWise</a> -->

                        <nav class="d-flex ms-4">
                            <a href="#" class="nav-item">Find Work</a>
                            <a href="saved_jobs.php" class="nav-item">Saved jobs</a>
                            <a href="proposals.php" class="nav-item">Proposals and offers</a>
                        </nav>
                    </div>

                    <div class="d-flex ms-auto align-items-center">
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
                        <i class="icon bi bi-question"></i>
                        <i class="icon bi bi-bell"></i>

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

            <div class="banner">
                <h2>Freelancer Plus with new perks</h2>
                <p>100 monthly Connects and full access Workwise's Mindful AI.</p>
                <button class="btn btn-light">Learn More</button>
            </div><br><br>

            <h3>Available Jobs</h3>

            <div id="jobResults">
                <!-- Filtered job posts will be displayed here -->
            </div>
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE status IN ('open', 'in_progress');");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <div class="container mt-4">
                    <?php while ($row = $result->fetch_assoc()): 
                        $createdAt = date("F j, Y", strtotime($row['created_at']));
                        ?>
                        <div class="col-12">

                            <div class="job-card">
                            <div class="d-flex justify-content-between">
                                <p class="text-muted mb-2"><?php echo $createdAt ?></p> 
                            </div>
                                <div>
                                    <i class="icon-btn bi bi-hand-thumbs-down"></i>
                                    <i class="icon-btn bi bi-heart"></i>
                                </div>
                                <div class="card custom-grey" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $row['id']; ?>" style="cursor: pointer;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p class="card-text"><strong>Budget:</strong> Php<?php echo number_format($row['budget'], 2); ?></p>
                                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                    </div>
                                </div>
                                <div>
                                <?php 
                                if (!empty($row['skills'])) {
                                    $skills = explode(',', $row['skills']);
                                    foreach ($skills as $skill) {
                                        echo '<span class="badge">' . htmlspecialchars(trim($skill)) . '</span>';
                                    }
                                } else {
                                    echo '<span class="text-muted">No skills specified</span>';
                                }
                                ?>
                            </div>
                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="payment-verified">Payment verified</span>
                                        <span class="text-muted ms-3">Php 0 spent</span>
                                        <span class="text-muted ms-3">Location: <?php echo htmlspecialchars($row['address']); ?></span>
                                    </div>
                                    <p class="text-muted mb-0">Proposals: Less than 5</p>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="jobModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="jobModalLabel<?php echo $row['id']; ?>">Title: <?php echo htmlspecialchars($row['title']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Description: </strong><?php echo htmlspecialchars($row['description']); ?></p>
                                        <p><strong>Name of Company: </strong><?php echo htmlspecialchars($row['description']); ?></p>
                                        <p><strong>Budget:</strong> Php<?php echo number_format($row['budget'], 2); ?></p>
                                        <p><strong>Location: </strong><?php echo htmlspecialchars($row['address']); ?></p>

                                        <p><strong>Job Details:</strong></p>
                                        <p>Additional information about the job can go here (e.g., timeframe, requirements).</p>

                                        <div id="saveJobMessage<?php echo $row['id']; ?>" class="alert d-none" role="alert">
                                            <!-- Message will be inserted here -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="submit_proposal.php?job_id=<?php echo $row['id']; ?>" class="btn btn-success">Apply Now</a>
                                        <form action="save_job.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-warning save-job-btn" data-job-id="<?php echo $row['id']; ?>">Save Job</button>
                                        </form>
                                                  <!-- Report Button -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reportModal">
                                            Report Freelancer
                                        </button>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reportModalLabel">Report Client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="report_client.php" method="POST">
                                                <!-- Pass the freelancer_id from the URL -->
                                                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                                                <div class="mb-3">
                                                    <label for="report_reason" class="form-label">Reason for Report</label>
                                                    <textarea name="report_reason" class="form-control" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger">Submit Report</button>
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

    <!-- Error Modal (Optional) -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="errorMessage">
                    <!-- Dynamic error message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
    button.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent form submission to allow AJAX behavior

        const jobId = this.getAttribute('data-job-id');
        const saveJobMessageDiv = document.getElementById(`saveJobMessage${jobId}`);
        const modalElement = document.getElementById(`jobModal${jobId}`); // Get the modal element

        // Reset the message div (hide previous message)
        saveJobMessageDiv.classList.add('d-none');
        saveJobMessageDiv.innerHTML = '';

        // Show a loading or generic message while we are sending the request
        saveJobMessageDiv.classList.remove('d-none');
        saveJobMessageDiv.innerHTML = `
            <div class="alert alert-info" role="alert">
                Saving job, please wait...
            </div>`;

        // Send AJAX request to save the job
        fetch('save_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ job_id: jobId }) // Send job_id as URL-encoded data
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            if (data.success) {
                // Show success message
                saveJobMessageDiv.innerHTML = `
                    <div class="alert alert-success" role="alert">
                        <strong>Success!</strong> Job saved successfully.
                    </div>`;

                // Debugging: Check if modal is correctly selected
                console.log('Modal element:', modalElement);
                
                // Delay closing the modal (e.g., 2 seconds after success)
                setTimeout(() => {
                    $(modalElement).modal('hide'); // Using jQuery (Bootstrap 4 style)
                }, 2000); // 2000 milliseconds = 2 seconds
            } else {
                // Show error message if there's an issue
                saveJobMessageDiv.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <strong>Error!</strong> ${data.error}
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show generic error message in the alert
            saveJobMessageDiv.classList.remove('d-none');
            saveJobMessageDiv.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <strong>Error!</strong> Something went wrong. Please try again later.
                </div>`;
        });
    });
});

        // Profile menu toggle
        document.getElementById('profileIcon').addEventListener('click', function (event) {
            event.stopPropagation();  // Prevents click from bubbling up to the document
            const profileMenu = document.getElementById('profileMenu');
            profileMenu.style.display = (profileMenu.style.display === 'block') ? 'none' : 'block';
        });

        // Close profile menu if clicking outside
        document.addEventListener('click', function (event) {
            const profileMenu = document.getElementById('profileMenu');
            const profileIcon = document.getElementById('profileIcon');
            if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.style.display = 'none';
            }
        });

    // Debounce function to limit the frequency of searches
    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', debounce(function () {
        const query = this.value.toLowerCase();
        const freelancerCards = document.querySelectorAll('.card'); // Ensure you target all freelancer cards

        let anyMatch = false;

        freelancerCards.forEach(card => {
            const freelancerName = card.querySelector('.card-title').textContent.toLowerCase();
            const freelancerEmail = card.querySelector('.card-text').textContent.toLowerCase();

            if (freelancerName.includes(query) || freelancerEmail.includes(query)) {
                card.style.display = '';  // Show the card if it matches
                anyMatch = true;
            } else {
                card.style.display = 'none';  // Hide the card if it doesn't match
            }
        });

        // Optional: show a no results message if no matches are found
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (!anyMatch) {
            if (!noResultsMessage) {
                const messageDiv = document.createElement('div');
                messageDiv.id = 'noResultsMessage';
                messageDiv.classList.add('alert', 'alert-warning');
                messageDiv.textContent = 'No freelancers found matching your search criteria.';
                document.querySelector('.container').appendChild(messageDiv);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }

    }, 300)); // 300ms debounce delay
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>