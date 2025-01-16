<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once './config.php';

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

            if ($role == 'admin') {
                header("Location: ./admin/admin_dashboard.php");
                exit;
            }elseif ($role == 'client') {
                header("Location: client_dashboard.php");
                exit;
            } 
            else {
                header("Location: freelance_dashboard.php");
                exit;
            }
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
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <img src="img/google-logo.png" alt="Google" width="20"> Continue Google
                </button>
                <button class="btn btn-light w-100">
                    <img src="img/apple-logo.png" alt="Apple" width="20"> Continue with Apple
                </button>
            </div>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
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
