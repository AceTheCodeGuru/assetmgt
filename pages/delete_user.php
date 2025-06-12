<?php
include '../config.php';
include '../includes/auth.php';
include '../includes/logger.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: Prevent admin from deleting themselves
    if ($_SESSION['user_id'] == $id) {
        die("You cannot delete your own account.");
    }

    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        logAction($conn, $_SESSION['user_id'], "Deleted User", "Deleted user ID $id");
    }
}

header("Location: manage_users.php");
exit;