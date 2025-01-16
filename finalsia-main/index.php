<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkWise Platform</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="img/workwise-logo.png" style="height: 60px; width: 60px"><a class="navbar-brand" href="index.php">WorkWise</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="about_workwise.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="nav-link" href="#">Find Work</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success" href="register.php">Post a Job</a>
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

    <!-- Bootstrap 4 JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>