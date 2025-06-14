<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php'; 
include '../includes/header.php';
include '../includes/sidebar.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$full_name = $_SESSION['full_name'];

// Fetch user-specific data based on role
if ($role === 'admin') {
    $totalAssets = $conn->query("SELECT COUNT(*) AS count FROM assets")->fetch_assoc()['count'];
    $assignedAssets = $conn->query("SELECT COUNT(*) AS count FROM asset_assignments WHERE status = 'active'")->fetch_assoc()['count'];
    $unassignedAssets = $conn->query("SELECT COUNT(*) AS count FROM assets WHERE id NOT IN (SELECT asset_id FROM asset_assignments WHERE status = 'active')")->fetch_assoc()['count'];
    $pendingRequests = $conn->query("SELECT COUNT(*) AS count FROM asset_requests WHERE status = 'pending'")->fetch_assoc()['count'];
    $totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
} else {
    // Regular user: fetch personal data
    $myAssigned = $conn->query("SELECT COUNT(*) AS count FROM asset_assignments WHERE user_id = $user_id AND status = 'active'")->fetch_assoc()['count'];
    $myRequests = $conn->query("SELECT COUNT(*) AS count FROM asset_requests WHERE user_id = $user_id")->fetch_assoc()['count'];
}

?>

<h2>Welcome, <?= $full_name ?>! (<?= $role ?>)</h2>

<div class="d-flex flex-wrap gap-3 mt-4">

<?php if ($role === 'admin'): ?>
    <!-- Admin specific content -->
    <a href="view_assets.php" class="text-decoration-none">
        <div class="card text-bg-primary" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">Total Assets</h6>
                <p class="fs-3"><?= $totalAssets ?></p>
            </div>
        </div>
    </a>
    <a href="view_assigned_assets.php" class="text-decoration-none">
        <div class="card text-bg-success" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">Assigned Assets</h6>
                <p class="fs-3"><?= $assignedAssets ?></p>
            </div>
        </div>
    </a>
    <a href="unassigned_assets.php" class="text-decoration-none">
        <div class="card text-bg-warning" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">Unassigned Assets</h6>
                <p class="fs-3"><?= $unassignedAssets ?></p>
            </div>
        </div>
    </a>
    <a href="view_requests.php" class="text-decoration-none">
        <div class="card text-bg-danger" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">Pending Requests</h6>
                <p class="fs-3"><?= $pendingRequests ?></p>
            </div>
        </div>
    </a>
    <a href="manage_users.php" class="text-decoration-none">
        <div class="card text-bg-info" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">Registered Users</h6>
                <p class="fs-3"><?= $totalUsers ?></p>
            </div>
        </div>
    </a>

<?php elseif ($role === 'ict'): ?>
    <!-- ICT specific content -->
    <div class="ict-section">
        <h3>ICT Functions</h3>
        <!-- Add ICT-specific links/content here -->
    </div>
<?php else: ?>
    <!-- Employee specific content -->
    <a href="my_assets.php" class="text-decoration-none">
        <div class="card text-bg-success" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">My Assigned Assets</h6>
                <p class="fs-3"><?= $myAssigned ?></p>
            </div>
        </div>
    </a>
    <a href="view_requests.php" class="text-decoration-none">
        <div class="card text-bg-info" style="width: 200px;">
            <div class="card-body">
                <h6 class="card-title">My Asset Requests</h6>
                <p class="fs-3"><?= $myRequests ?></p>
            </div>
        </div>
    </a>
<?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
