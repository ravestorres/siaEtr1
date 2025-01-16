<?php
include('config.php');

// Get search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL query to search for jobs based on the title or description
$sql = "SELECT * FROM jobs WHERE title LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// If there are results, output them
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="job-card">
            <h5><?php echo htmlspecialchars($row['title']); ?></h5>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>Budget:</strong> $<?php echo number_format($row['budget'], 2); ?></p>
            <!-- Add any other job details here -->
        </div>
        <?php
    }
} else {
    echo "No jobs found.";
}

$stmt->close();
?>
