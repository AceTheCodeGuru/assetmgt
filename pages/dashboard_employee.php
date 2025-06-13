<?php 
include '../config.php';
include '../includes/auth.php';
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<h2>Employee Dashboard</h2>
<p>Welcome, <?= $_SESSION['full_name'] ?> (<?= $_SESSION['role'] ?>)</p>

<a href="../logout.php" class="btn btn-danger">Logout</a>

<?php include '../includes/footer.php'; ?>