<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php'; 
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<h2><?= ucfirst($_SESSION['role']) ?> Dashboard</h2>
<p>Welcome, <?= $_SESSION['full_name'] ?> (<?= $_SESSION['role'] ?>)</p>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <!-- Admin specific content -->
    <div class="admin-section">
        <h3>Admin Functions</h3>
        <a href="../register.php" class="btn btn-outline-primary">Add New User</a>
        <a href="manage_users.php" class="btn btn-outline-secondary">Manage Users</a>
        <!-- Add other admin-specific links/content here -->
    </div>
<?php elseif ($_SESSION['role'] === 'ict'): ?>
    <!-- ICT specific content -->
    <div class="ict-section">
        <h3>ICT Functions</h3>
        <!-- Add ICT-specific links/content here -->
    </div>
<?php else: ?>
    <!-- Employee specific content -->
    <div class="employee-section">
        <h3>Employee Functions</h3>
        <!-- Add employee-specific links/content here -->
    </div>
<?php endif; ?>


<?php include '../includes/footer.php'; ?>