<?php 
include 'config.php'; 
include 'includes/auth.php';
include 'includes/db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirect to single dashboard
    header("Location: pages/dashboard.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<h2>Welcome to the Asset Management System</h2>
<p>Please <a href="login.php">login</a> to continue.</p>

<?php include 'includes/footer.php'; ?>