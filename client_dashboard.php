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
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .star-rating {
            font-size: 1.25rem;
            color: #ff9800;
        }
        .star-rating i {
            margin-right: 3px;
        }
        .card-text {
            font-size: 0.9rem;
        }
        .profile-picture {
            width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
        }
        .freelancer-card {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <div class="container main-content mt-4">
        <?php if ($user_role == 'client'): ?>
            <div class="header">
                <div class="container d-flex align-items-center">
                    <div class="d-flex align-items-center me-auto">
                        <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php"><strong>WorkWise</strong></a>
                        <nav class="d-flex ms-4">
                            <a href="#" class="nav-item">Hire talent</a>
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Freelancers</h3>
            </div>

            <div id="freelancerResults" class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Filtered freelancers will be displayed here -->
                <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'freelancer'");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        // Get the freelancer's average rating
                        $freelancer_id = $row['id'];
                        $rating_stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM ratings WHERE freelancer_id = ?");
                        $rating_stmt->bind_param("i", $freelancer_id);
                        $rating_stmt->execute();
                        $rating_result = $rating_stmt->get_result();
                        $rating_row = $rating_result->fetch_assoc();
                        $avg_rating = $rating_row['avg_rating'] ?: 0; // Default to 0 if no ratings
                        $rating_percentage = ($avg_rating / 5) * 100; // Calculate percentage from 5 stars
                ?>
                        <div class="col freelancer-card">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" class="card-img-top profile-picture" alt="Freelancer Profile Picture">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($row['email']); ?></p>
                                    <p class="card-text"><strong>Level:</strong> <?php echo htmlspecialchars($row['freelancer_level']); ?></p>
                                    <p class="card-text"><strong>Joined:</strong> <?php echo date('F j, Y', strtotime($row['created_at'])); ?></p>

                                    <!-- Display Rating Stars -->
                                    <div class="star-rating">
                                        <?php
                                            $full_stars = floor($avg_rating); // Number of full stars
                                            $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0; // Check for half star
                                            $empty_stars = 5 - $full_stars - $half_star; // Remaining empty stars

                                            // Full stars
                                            for ($i = 0; $i < $full_stars; $i++) {
                                                echo '<i class="bi bi-star-fill"></i>';
                                            }

                                            // Half star
                                            if ($half_star) {
                                                echo '<i class="bi bi-star-half"></i>';
                                            }

                                            // Empty stars
                                            for ($i = 0; $i < $empty_stars; $i++) {
                                                echo '<i class="bi bi-star"></i>';
                                            }
                                        ?>
                                        <span class="ms-2"><?php echo round($rating_percentage, 2); ?>%</span>
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="view_freelancer.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    endwhile;
                else:
                    echo "<p>No freelancers found.</p>";
                endif;
                ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
</body>

