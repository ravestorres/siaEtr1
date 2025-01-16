<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "No freelancer selected.";
    exit;
}

$freelancer_id = $_GET['id'];

// Fetch freelancer data
$stmt = $conn->prepare("SELECT name, fullname, address, email, profile_picture, bio, hrate, availabitily, role, freelancer_level FROM users WHERE id = ? AND role = 'freelancer'");
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();
$freelancer = $result->fetch_assoc();
$stmt->close();

if (!$freelancer) {
    echo "Freelancer not found.";
    exit;
}


if (isset($_SESSION['report_success'])) {
    echo '<div class="alert alert-success text-center">' . $_SESSION['report_success'] . '</div>';
    unset($_SESSION['report_success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($freelancer['name']); ?> - Profile</title>
    <link rel="stylesheet" href="css/view_freelancer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <div class="text-center">
                    <?php if ($freelancer['profile_picture']): ?>
                        <img src="<?php echo htmlspecialchars($freelancer['profile_picture']); ?>" alt="Profile Picture" class="profile-img" />
                    <?php else: ?>
                        <img src="https://via.placeholder.com/150" alt="Profile Picture" class="profile-img" />
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($freelancer['name']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($freelancer['role']); ?></p>
                </div>

                <!-- Full Name -->
                <div class="profile-info">
                    <span class="label">Full Name:</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['fullname']); ?></span>
                </div>

                <!-- Email -->
                <div class="profile-info">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['email']); ?></span>
                </div>

                <!-- Address -->
                <div class="profile-info">
                    <span class="label">Address:</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['address']); ?></span>
                </div>

                <!-- Bio -->
                <div class="profile-info">
                    <span class="label">Bio:</span>
                    <span class="value"><?php echo nl2br(htmlspecialchars($freelancer['bio'])); ?></span>
                </div>

                <!-- Hourly Rate -->
                <div class="profile-info">
                    <span class="label">Hourly Rate (Php):</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['hrate']); ?></span>
                </div>

                <!-- Level -->
                <div class="profile-info">
                    <span class="label">Level:</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['freelancer_level']); ?></span>
                </div>

                <!-- Availability -->
                <div class="profile-info">
                    <span class="label">Availability:</span>
                    <span class="value"><?php echo htmlspecialchars($freelancer['availabitily']); ?></span>
                </div>

                <!-- Ratings and Report Buttons -->
                <div class="text-center">
                    <!-- Rating Button -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ratingModal">Rate Freelancer</button>

                    <!-- Report Button -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reportModal">
    Report Freelancer
</button>

                </div>

                <!-- Rating Modal -->
                <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ratingModalLabel">Rate Freelancer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="submit_rating.php" method="POST">
                                    <input type="hidden" name="freelancer_id" value="<?php echo $freelancer_id; ?>">
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating (1-5)</label>
                                        <input type="number" name="rating" class="form-control" min="1" max="5" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback" class="form-label">Comments</label>
                                        <textarea name="feedback" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Rating</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Freelancer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="submit_report.php" method="POST">
                    <!-- Pass the freelancer_id from the URL -->
                    <input type="hidden" name="freelancer_id" value="<?php echo $freelancer_id; ?>">
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

                </div>

                <!-- Back Button -->
                <div class="text-center btn-back">
                    <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
