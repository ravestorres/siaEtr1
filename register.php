<?php
session_start(); // Start the session to use session variables

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
            // Set a session variable for the success message
            $_SESSION['success_message'] = "Account successfully created!";
            // Redirect to login.php with a success message
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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }

        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 10px;
        }

        /* Green Register Button */
        .btn-primary {
            background-color: #28a745; /* Green */
            border-color: #28a745; /* Green */
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #218838; /* Darker Green */
            border-color: #1e7e34; /* Darker Green */
        }

        .btn-light {
            background-color: white;
            border: 1px solid #ddd;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-light:hover {
            background-color: #f8f9fa;
        }

        .btn-light img {
            margin-right: 10px;
        }

        .my-4 {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .separator-line {
            flex-grow: 1;
            border-top: 1px solid #ddd;
        }

        .separator-text {
            margin: 0 10px;
            font-weight: bold;
            color: #666;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
            width: 50%;
            padding: 10px;
            background-color: white;
        }

        .btn-outline-primary:hover {
            color: white;
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body>

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
</body>

</html>
