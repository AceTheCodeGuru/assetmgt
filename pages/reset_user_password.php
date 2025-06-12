<?php
include '../config.php';
include '../includes/auth.php';
include '../includes/logger.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$user_id = $_GET['id'];
$default_password = "Lusaka123"; // Or generate one dynamically
$hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

// Update password and set first_login flag
$stmt = $conn->prepare("UPDATE users SET password = ?, first_login = 1 WHERE id = ?");
$stmt->bind_param("si", $hashed_password, $user_id);

if ($stmt->execute()) {
    logAction($conn, $_SESSION['user_id'], "Reset Password", "Reset password for user ID $user_id");
    header("Location: manage_users.php?success=Password+reset+to+default.");
} else {
    die("Failed to reset password.");
}

exit;
