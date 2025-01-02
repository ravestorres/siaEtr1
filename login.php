<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'config.php';

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid email or password!";
        }

        $stmt->close();
    } else {
        $error_message = "Invalid email format!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
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

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            padding: 10px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-outline-success {
            color: #28a745;
            border-color: #28a745;
            width: 50%;
            padding: 10px;
            background-color: white;
        }

        .btn-outline-success:hover {
            color: white;
            background-color: #28a745;
            border-color: #28a745;
        }

        .alert {
            margin-bottom: 15px;
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

        .extra-space {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .bottom-separator {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Login</h2>

            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
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
                <button type="submit" class="btn btn-success">Login</button>
            </form>

            <!-- First Separator with text -->
            <div class="separator">
                <div class="separator-line"></div>
                <span class="separator-text">or</span>
                <div class="separator-line"></div>
            </div>

            <!-- Sign Up with Google and Apple -->
            <div class="my-4 text-center">
                <button class="btn btn-light w-100 mb-2">
                    <img src="img/google-logo.png" alt="Google" width="20"> Continue with Google
                </button>
                <button class="btn btn-light w-100">
                    <img src="img/apple-logo.png" alt="Apple" width="20"> Continue with Apple
                </button>
            </div>

            <!-- Second Separator -->
            <div class="separator bottom-separator">
                <div class="separator-line"></div>
                <span class="separator-text">Don't have an account?</span>
                <div class="separator-line"></div>
            </div>

            <div class="mt-3 text-center">
                <a href="register.php" class="btn btn-outline-success">Sign Up</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
