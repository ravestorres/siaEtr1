<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] == 'client') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $requirements = htmlspecialchars($_POST['requirements']);
    $budget = floatval($_POST['budget']);
    $client_id = intval($_SESSION['user_id']);
    $address = htmlspecialchars($_POST['address']);
    $company_name = htmlspecialchars($_POST['company_name']);
    $position = htmlspecialchars($_POST['position']);
    $skills = isset($_POST['skills']) ? implode(",", $_POST['skills']) : "";

    // Handle logo upload
    $upload_dir = "uploads/logos/";
    $logo_path = "";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_name = basename($_FILES['logo']['name']);
        $logo_path = $upload_dir . uniqid() . "_" . $logo_name;
        
        // Validate image type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['logo']['type'], $allowed_types)) {
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path)) {
                echo "Error uploading the logo.";
                exit;
            }
        } else {
            echo "Invalid logo file type. Only JPG, PNG, and GIF are allowed.";
            exit;
        }
    }

    $skills = $_POST['skills'];
    // Ensure skills is a comma-separated string
    $skills_str = implode(", ", $skills); // Convert array to string

    $stmt = $conn->prepare("INSERT INTO jobs (client_id, title, description, requirements, budget, address, company_name, position, logo, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdsssss", $client_id, $title, $description, $requirements, $budget, $address, $company_name, $position, $logo_path, $skills_str);
    

if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/logo/';
    $fileName = basename($_FILES['logo']['name']);
    $uploadFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFilePath)) {
        // Save $uploadFilePath to the database as the logo path
        $stmt = $conn->prepare("INSERT INTO jobs (client_id, title, description, requirements, budget, address, company_name, position, logo, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdsssss", $client_id, $title, $description, $requirements, $budget, $address, $company_name, $position, $logo_path, $skills_str);
        $stmt->execute();
    } else {
        echo "Failed to upload logo.";
    }
}

// Execute and check for errors
if ($stmt->execute()) {
    echo "Job posted successfully!";
    header("refresh:3;url=manage_work.php"); // Redirect after 3 seconds
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();


    $filter_skills = isset($_GET['skills']) ? $_GET['skills'] : "";
    if (!empty($filter_skills)) {
        $skills_array = explode(",", $filter_skills);
        $placeholders = implode(",", array_fill(0, count($skills_array), "?"));
        $stmt = $conn->prepare("SELECT * FROM freelancers WHERE FIND_IN_SET(?, skills)");
        $stmt->bind_param(str_repeat("s", count($skills_array)), ...$skills_array);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Post a Job</title>
</head>
<style>
.modal-body img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
}

</style>

<body>
    <div class="container mt-5">
        <h2>Post a Job</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <?php if (!empty($row['logo_path'])): ?>
        <div class="text-center mb-4">
            <img src="<?php echo htmlspecialchars($row['logo_path']); ?>" alt="Company Logo" class="img-fluid" style="max-height: 150px;">
        </div>
    <?php endif; ?>
            <div class="mb-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="budget">Budget</label>
                <input type="number" id="budget" name="budget" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="position">Available Position</label>
                <input type="text" id="position" name="position" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="skills">Required Skills</label>
                <div id="skills">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="smm" name="skills[]" value="Social Media Marketing">
                        <label class="form-check-label" for="skill_smm">Social Media Marketing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_seo" name="skills[]" value="Search Engine Optimization">
                        <label class="form-check-label" for="skill_seo">Search Engine Optimization (SEO)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_pae" name="skills[]" value="Photography and Editing">
                        <label class="form-check-label" for="skill_pae">Photography and Editing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_block" name="skills[]" value="Blockchain">
                        <label class="form-check-label" for="skill_block">Blockchain</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_gd" name="skills[]" value="Graphic Design">
                        <label class="form-check-label" for="skill_gd">Graphic Design</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_wdd" name="skills[]" value="Web Designing and Developing">
                        <label class="form-check-label" for="skill_wdd">Web Designing and Developing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_cs" name="skills[]" value="Customer Service">
                        <label class="form-check-label" for="skill_cs">Customer Service</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skill_am" name="skills[]" value="Affiliate Marketing">
                        <label class="form-check-label" for="skill_am">Affiliate Marketing</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="logo">Company Logo</label>
                <input type="file" id="logo" name="logo" class="form-control" accept="image/*" required>
            </div>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
            <button type="submit" class="btn btn-primary">Post Job</button>
        </form>
    </div>
   
    <script>
    function goBack() {
        window.history.back();
    }
</script>
   
</body>

</html>
