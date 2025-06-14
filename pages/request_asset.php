<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asset_id = $_POST['asset_id'];
    $reason = $_POST['reason'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO asset_requests (user_id, asset_id, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $asset_id, $reason);
    $stmt->execute();

    logAction($conn, $user_id, "Requested Asset", "Requested asset ID $asset_id");

    header("Location: request_asset.php?success=1");
    exit;
}

$availableAssets = $conn->query("
    SELECT * FROM assets 
    WHERE id NOT IN (
        SELECT asset_id FROM asset_assignments WHERE status = 'active'
    )
");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<h3>Request Asset</h3>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Request submitted!</div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Select Asset</label>
        <select name="asset_id" class="form-control" required>
            <option value="">-- Select --</option>
            <?php while ($row = $availableAssets->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['asset_name'] ?> (<?= $row['serial_number'] ?>)</option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Reason</label>
        <textarea name="reason" class="form-control" required></textarea>
    </div>
    <button class="btn btn-primary">Submit Request</button>
</form>
