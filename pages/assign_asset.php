<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

// Only admins allowed
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asset_id = $_POST['asset_id'];
    $user_id = $_POST['user_id'];
    $assigned_by = $_SESSION['user_id'];
    $assigned_date = date('Y-m-d H:i:s');

    // Insert into asset_assignments with assigned_by
    $stmt = $conn->prepare("INSERT INTO asset_assignments (asset_id, user_id, assigned_by, assigned_date, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("iiis", $asset_id, $user_id, $assigned_by, $assigned_date);
    $stmt->execute();

    // Update asset status
    $conn->query("UPDATE assets SET status = 'assigned' WHERE id = $asset_id");

    // Log the assignment
    logAction($conn,$assigned_by, "Assign Asset", "Assigned asset ID $asset_id to user ID $user_id");

    header("Location: assign_asset.php?success=1");
    exit;
}

// Fetch assets not yet assigned
$assets = $conn->query("SELECT * FROM assets 
                                    WHERE id NOT IN (
                                        SELECT asset_id FROM asset_assignments WHERE status = 'active'
                                        )
                                    ");
$users = $conn->query("SELECT id, full_name FROM users");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4" style="margin-left: 220px;">
    <h4>Assign Asset to User</h4>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Asset assigned successfully!</div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="asset_id" class="form-label">Select Asset</label>
            <select name="asset_id" id="asset_id" class="form-control" required>
                <option value="">-- Choose Asset --</option>
                <?php while ($row = $assets->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['asset_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">-- Choose User --</option>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Assign Asset</button>
    </form>
</div>
