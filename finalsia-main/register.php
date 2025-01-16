<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'config.php';

    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = htmlspecialchars($_POST['role']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Account successfully created!";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid email format!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php">WorkWise</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4">Register</h2>

            <form method="POST">
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" name="name" id="name" class="form-control" required>
                        <label for="name">Name</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="email" name="email" id="email" class="form-control" required>
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select name="role" class="form-select">
                        <option value="freelancer">Freelancer</option>
                        <option value="client">Client</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <!-- Separator -->
            <div class="separator">
                <div class="separator-line"></div>
                <span class="separator-text">or</span>
                <div class="separator-line"></div>
            </div>

            <!-- Social Sign Up -->
            <div class="my-4 text-center">
                <button class="btn btn-light w-100 mb-2">
                    <img src="img/google-logo.png" alt="Google" width="20"> Continue with Google
                </button>
                <button class="btn btn-light w-100">
                    <img src="img/apple-logo.png" alt="Apple" width="20"> Continue with Apple
                </button>
            </div>

            <!-- Account Already Exists Link -->
            <div class="separator">
                <div class="separator-line"></div>
                <span class="separator-text">Already have an account?</span>
                <div class="separator-line"></div>
            </div>

            <div class="mt-3 text-center">
                <a href="login.php" class="btn btn-outline-primary">Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
</script>
</body>

</html>
