<?php 
session_start();
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';
include '../includes/header.php';
include '../includes/sidebar.php'; 
?>

<h2>Admin Dashboard</h2>
<p>Welcome, <?= $_SESSION['full_name'] ?> (<?= $_SESSION['role'] ?>)</p>


<a href="../register.php" class="btn btn-outline-primary">Add New User</a>
<a href="manage_users.php" class="btn btn-outline-secondary">Manage Users</a>

<a href="../logout.php" class="btn btn-danger">Logout</a>

<?php include '../includes/footer.php'; ?>