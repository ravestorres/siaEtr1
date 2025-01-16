<?php
session_start();
require_once '../config.php';

// Fetch all users except admins with their report counts
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.role, u.created_at, 
        COUNT(DISTINCT r.id) + COUNT(DISTINCT rc.id) AS report_count,
        u.disabled, u.report_threshold
    FROM users u
    LEFT JOIN reports r ON u.id = r.freelancer_id OR u.id = r.user_id
    LEFT JOIN reports_client rc ON u.id = rc.client_id OR u.id = rc.user_id
    WHERE u.role != 'admin'
    GROUP BY u.id
");
$stmt->execute();
$users = $stmt->get_result();

// Prepare statement to disable users
$disableUsersStmt = $conn->prepare("UPDATE users SET disabled = 1 WHERE id = ?");

// Auto-disable users exceeding their report threshold
while ($user = $users->fetch_assoc()) {
    if ($user['report_count'] >= $user['report_threshold'] && !$user['disabled']) {
        $disableUsersStmt->bind_param("i", $user['id']);
        $disableUsersStmt->execute();
    }
}
$disableUsersStmt->close();

// Re-fetch users for display
$stmt->execute();
$users = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Manage Users</h2>
        <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Admin Dashboard</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Reports</th>
                    <th>Limit Reports</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <?php $rowClass = $row['report_count'] >= $row['report_threshold'] ? 'table-danger' : ''; ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['role'])) ?></td>
                        <td><?= $row['report_count'] ?></td>
                        <td><?= $row['report_threshold'] ?></td>
                        <td><?= $row['disabled'] ? 'Disabled' : 'Active' ?></td>
                        <td>
                            <form action="toggle_user_status.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="current_status" value="<?= $row['disabled'] ?>">
                                <button type="submit" class="btn btn-<?= $row['disabled'] ? 'success' : 'danger' ?>" 
                                        onclick="return confirm('Are you sure you want to <?= $row['disabled'] ? 'enable' : 'disable' ?> this user?')">
                                    <?= $row['disabled'] ? 'Enable' : 'Disable' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
