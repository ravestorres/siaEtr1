<?php
require_once 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

// Get the user role and user ID
$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Check if the search query is submitted
if (isset($_GET['search_query'])) {
    $search_query = trim($_GET['search_query']);
    if ($user_role === 'freelancer') {
        $stmt = $conn->prepare(
            "SELECT * FROM jobs 
            WHERE (title LIKE ? OR description LIKE ?) 
            AND status = 'open'"
        );
        $search_term = '%' . $search_query . '%';
        $stmt->bind_param("ss", $search_term, $search_term);
    } elseif ($user_role === 'client') {
        $stmt = $conn->prepare(
            "SELECT * FROM jobs 
            WHERE (title LIKE ? OR description LIKE ?) 
            AND client_id = ?"
        );
        $search_term = '%' . $search_query . '%';
        $stmt->bind_param("ssi", $search_term, $search_term, $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "No search query provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Search Results</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Budget</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>$<?= number_format($row['budget'], 2) ?></td>
                        <td>
                            <?php if ($user_role === 'freelancer'): ?>
                                <a href="submit_proposal.php?job_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Apply</a>
                                <form action="save_job.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="job_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">Save</button>
                                </form>
                            <?php elseif ($user_role === 'client'): ?>
                                <a href="edit_job.php?job_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form action="delete_job.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="job_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No results found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
