<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

$proposal_id = $_GET['proposal_id'];

// Fetch the proposal data from the database
$stmt = $conn->prepare("SELECT * FROM proposals WHERE id = ? AND freelancer_id = ?");
$stmt->bind_param("ii", $proposal_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$proposal = $result->fetch_assoc();

// If no proposal is found, redirect to proposals page
if (!$proposal) {
    header("Location: proposals.php");
    exit;
}

// Handle form submission for updating the proposal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_description = $_POST['description'];
    $new_budget = $_POST['budget'];

    $update_stmt = $conn->prepare("UPDATE proposals SET proposal_details = ?, proposed_rate = ? WHERE id = ? AND freelancer_id = ?");
    $update_stmt->bind_param("sdii", $new_description, $new_budget, $proposal_id, $user_id);
    $update_stmt->execute();

    // Redirect back to proposals page after update
    header("Location: proposals.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Proposal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Proposal for <?php echo htmlspecialchars($proposal['job_id']); ?></h3>

        <form method="POST">
            <div class="mb-3">
                <label for="description" class="form-label">Proposal Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($proposal['proposal_details']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="budget" class="form-label">Budget</label>
                <input type="number" class="form-control" id="budget" name="budget" value="<?php echo htmlspecialchars($proposal['proposed_rate']); ?>" step="0.01">
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
