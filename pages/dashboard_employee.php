<?php include '../config.php'; ?>
<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>

<h2>Admin Dashboard</h2>
<p>Welcome, <?= $_SESSION['full_name'] ?> (<?= $_SESSION['role'] ?>)</p>

<a href="../register.php" class="btn btn-outline-primary">Add New User</a>

<a href="../logout.php" class="btn btn-danger">Logout</a>

<?php include '../includes/footer.php'; ?>