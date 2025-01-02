<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkWise Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="#">WorkWise</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Find Work</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Sign Up</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success" href="#">Post a Job</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="jumbotron jumbotron-fluid text-center text-white bg-primary">
        <div class="container">
            <h1 class="display-4">Find Top Freelancers</h1>
            <p class="lead">Connect with professionals to get your job done.</p>
            <a href="register.php" class="btn btn-light btn-lg">Get Started</a>
        </div>
    </header>

    <!-- Categories Section -->
    <section class="container py-5">
        <h2 class="text-center mb-4">Explore Popular Categories</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <img src="img/webdev.jpg" class="img-fluid rounded-circle mb-3" alt="Category">
                <h5>Web Development</h5>
            </div>
            <div class="col-md-4">
                <img src="img/graphics.jpg" class="img-fluid rounded-circle mb-3" alt="Category">
                <h5>Graphic Design</h5>
            </div>
            <div class="col-md-4">
                <img src="img/contentwriting.jpg" class="img-fluid rounded-circle mb-3" alt="Category">
                <h5>Content Writing</h5>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 WorkWise. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
