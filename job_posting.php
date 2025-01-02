<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] == 'client') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $requirements = htmlspecialchars($_POST['requirements']);
    $budget = floatval($_POST['budget']);
    $client_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO jobs (client_id, title, description, requirements, budget) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $client_id, $title, $description, $requirements, $budget);

    if ($stmt->execute()) {
        // Set success message in session and redirect to dashboard
        $_SESSION['message'] = "Job posted successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        h2 {
            font-weight: 500;
        }

        label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4 text-center">Post a Job</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="requirements">Requirements</label>
                        <textarea id="requirements" name="requirements" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="budget">Budget</label>
                        <input type="number" id="budget" name="budget" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Post Job</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
