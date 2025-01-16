<?php
session_start();
include('config.php');

// Check if user is logged in and has the right role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'client') {
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Work</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container main-content mt-4">
    <?php if ($user_role == 'client'): ?>
        <div class="header">
                <div class="container d-flex align-items-center">
                    <div class="d-flex align-items-center me-auto">
                    <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php"><strong>WorkWise</strong></a>
                        <nav class="d-flex ms-4">
                            
                            <a href="client_dashboard.php" class="nav-item">Hire talent</a>
                            <a href="manage_work.php" class="nav-item">Manage work</a>
                            <a href="#" class="nav-item">Reports</a>
                        </nav>
                    </div>

                    <div class="d-flex ms-auto align-items-center">
                        <div class="search-bar">
                            <input type="text" id="searchInput" name="query" placeholder="Search" />
                        </div>
                        <i class="icon bi bi-question"></i>
                        <i class="icon bi bi-bell"></i>

                        <!-- User Profile -->
                        <div class="profile-container">
                            <div class="profile-icon" id="profileIcon">
                                <i class="icon bi bi-person-circle"></i>
                            </div>

                            <div class="profile-menu" id="profileMenu">
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
        <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
            <h3>Your Job Postings</h3>
            <a href="job_posting.php" class="btn btn-primary">Post a Job</a>
        </div>
        <div class="row" id="jobResults">
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE client_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                    <div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Title: <?php echo htmlspecialchars($row['title']); ?></h5>
            <p class="card-text text-truncate" style="max-height: 40px; overflow: hidden;">Description: <?php echo htmlspecialchars($row['description']); ?></p>
            <!-- Color Based on Status -->
            <span class="badge <?php 
                if ($row['status'] == 'closed') {
                    echo 'bg-danger'; 
                } elseif ($row['status'] == 'in_progress') {
                    echo 'bg-success'; 
                } else {
                    echo 'bg-info'; 
                }
            ?>" id="status-<?php echo $row['id']; ?>">Status: <?php echo ucfirst($row['status']); ?></span>
            <p class="card-text mt-2"><strong>Budget:</strong> Php<?php echo number_format($row['budget'], 2); ?></p>

            <!-- Dropdown Button for Status Update -->
            <div class="btn-group mt-2">
                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                    Update Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'open')">Open</a></li>
                    <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'in_progress')">In Progress</a></li>
                    <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'closed')">Closed</a></li>
                </ul>
            </div>

            <button 
                class="btn btn-primary btn-sm mt-2" 
                data-bs-toggle="modal" 
                data-bs-target="#jobModal" 
                data-job-id="<?php echo $row['id']; ?>"
                data-title="<?php echo htmlspecialchars($row['title']); ?>"
                data-description="<?php echo htmlspecialchars($row['description']); ?>"
                data-requirements="<?php echo htmlspecialchars($row['requirements']); ?>"
                data-budget="Php<?php echo number_format($row['budget'], 2); ?>"
                data-status="<?php echo ucfirst($row['status']); ?>"
                data-address="<?php echo ucfirst($row['address']); ?>">
                More Details
            </button>
        </div>
    </div>
</div>

            <?php
                endwhile;
            else:
            ?>
                <p>No job postings found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobModalLabel">Job Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="modalTitle"></span></p>
                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                <p><strong>Requirements:</strong> <span id="modalRequirements"></span></p>
                <p><strong>Budget:</strong> <span id="modalBudget"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <p><strong>Address:</strong> <span id="modalAddress"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="viewProposalsLink" class="btn btn-primary">View Proposals</a>
            </div>
        </div>
    </div>
</div>
</body>
    <script>
    function updateStatus(jobId, status) {
    if (confirm("Are you sure you want to update the status to " + status + "?")) {
        // Perform AJAX request to update the status in the database
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_status_job.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                let badge = document.getElementById('status-' + jobId);
                
                badge.textContent = "Status: " + response.status.charAt(0).toUpperCase() + response.status.slice(1);
                
                badge.classList.remove('bg-info', 'bg-success', 'bg-danger');
                if (response.status === 'closed') {
                    badge.classList.add('bg-danger'); 
                } else if (response.status === 'in_progress') {
                    badge.classList.add('bg-success'); 
                } else {
                    badge.classList.add('bg-info'); 
                }
                var modalStatus = document.getElementById('modalStatus');
                modalStatus.textContent = "Status: " + response.status.charAt(0).toUpperCase() + response.status.slice(1);

                let modalButton = document.querySelector('[data-job-id="' + jobId + '"]');
                modalButton.setAttribute('data-status', response.status);
            }
        };
        xhr.send('job_id=' + jobId + '&status=' + status);
    }
}
    // Populate the modal with job details when "View Proposals" is clicked
        var jobModal = document.getElementById('jobModal');
        jobModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var jobId = button.getAttribute('data-job-id');
            var title = button.getAttribute('data-title');
            var description = button.getAttribute('data-description');
            var requirements = button.getAttribute('data-requirements');
            var budget = button.getAttribute('data-budget');
            var status = button.getAttribute('data-status');
            var address = button.getAttribute('data-address');

            // Update modal content
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalRequirements').textContent = requirements;
            document.getElementById('modalBudget').textContent = budget;
            document.getElementById('modalStatus').textContent = "" + status.charAt(0).toUpperCase() + status.slice(1); // Set updated status in modal
            document.getElementById('modalAddress').textContent = address;
            // Update "View Proposals" link
            var viewProposalsLink = document.getElementById('viewProposalsLink');
            viewProposalsLink.href = 'view_proposals.php?job_id=' + jobId;
        });
                // Profile menu toggle
            document.getElementById('profileIcon').addEventListener('click', function (event) {
                    event.stopPropagation();
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
                const freelancerCards = document.querySelectorAll('.card'); 
                let anyMatch = false;
                freelancerCards.forEach(card => {
                    const freelancerName = card.querySelector('.card-title').textContent.toLowerCase();
                    const freelancerEmail = card.querySelector('.card-text').textContent.toLowerCase();

                    if (freelancerName.includes(query) || freelancerEmail.includes(query)) {
                        card.style.display = ''; 
                        anyMatch = true;
                    } else {
                        card.style.display = 'none'; 
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
</html>
