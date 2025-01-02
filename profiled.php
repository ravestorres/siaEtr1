<?php
// Include your database connection file
include('config.php');

// Get the freelancer ID from URL
$freelancer_id = $_GET['id'];

// Fetch user and user profile data
$sql = "SELECT u.id, u.name, u.email, u.profile_picture, up.bio, up.workhistory, up.languages, up.education
        FROM users u
        LEFT JOIN user_profile up ON u.id = up.user_id
        WHERE u.id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the freelancer data
    $freelancer = $result->fetch_assoc();
} else {
    // Handle the case where no data is found for the given id
    echo "<p class='alert alert-warning'>No profile found for this freelancer.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Profile</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-details {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .profile-details h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .profile-details p {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="profile-header">
            <h1>Profile of <?php echo htmlspecialchars($freelancer['name']); ?></h1>
            <img src="<?php echo htmlspecialchars($freelancer['profile_picture']); ?>" alt="Profile Picture" />
        </div>

        <div class="profile-details">
            <h2>Contact Information</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($freelancer['email']); ?></p>

            <h2>Bio</h2>
            <p><?php echo nl2br(htmlspecialchars($freelancer['bio'])); ?></p>

            <h2>Work History</h2>
            <p><?php echo nl2br(htmlspecialchars($freelancer['workhistory'])); ?></p>

            <h2>Languages</h2>
            <p><?php echo htmlspecialchars($freelancer['languages']); ?></p>

            <h2>Education</h2>
            <p><?php echo htmlspecialchars($freelancer['education']); ?></p>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Optional, if you need functionality like modals, dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
