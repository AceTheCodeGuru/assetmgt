<?php
include '../config.php';
include '../includes/auth.php';
include '../includes/logger.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$assignment_id = $_GET['id'] ?? null;
if (!$assignment_id) {
    header("Location: view_assigned_assets.php");
    exit;
}

// Fetch current assignment
$stmt = $conn->prepare("
    SELECT aa.id, aa.asset_id, aa.user_id, a.asset_name
    FROM asset_assignments aa
    JOIN assets a ON aa.asset_id = a.id
    WHERE aa.id = ?
");
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) {
    echo "Assignment not found.";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user_id = $_POST['user_id'];
    $assigned_by = $_SESSION['user_id'];
    $assigned_date = date('Y-m-d H:i:s');

    $update = $conn->prepare("UPDATE asset_assignments SET user_id = ?, assigned_by = ?, assigned_date = ? WHERE id = ?");
    $update->bind_param("iisi", $new_user_id, $assigned_by, $assigned_date, $assignment_id);
    $update->execute();

    logAction($conn, $assigned_by, "Reassign Asset", "Asset ID {$assignment['asset_id']} reassigned to user ID $new_user_id");

    header("Location: view_assigned_assets.php?reassigned=1");
    exit;
}

// Fetch users for dropdown
$users = $conn->query("SELECT id, full_name FROM users");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4" style="margin-left: 220px;">
    <h4>Edit Assignment - <?= htmlspecialchars($assignment['asset_name']) ?></h4>

    <form method="POST">
        <div class="mb-3">
            <label for="user_id" class="form-label">Reassign To:</label>
            <select name="user_id" class="form-control" required>
                <?php while ($u = $users->fetch_assoc()): ?>
                    <option value="<?= $u['id'] ?>" <?= $assignment['user_id'] == $u['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['full_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Assignment</button>
        <a href="view_assigned_assets.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
