<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$assignment_id = $_GET['id'] ?? null;

if (!$assignment_id) {
    header("Location: view_assigned_assets.php");
    exit;
}

$stmt = $conn->prepare("UPDATE asset_assignments SET status = 'inactive' WHERE id = ?");
$stmt->bind_param("i", $assignment_id);
$stmt->execute();

logAction($conn,$_SESSION['user_id'], "Deactivate Assignment", "Deactivated assignment ID $assignment_id");

header("Location: view_assigned_assets.php?removed=1");
exit;
