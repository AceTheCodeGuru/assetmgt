<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

if ($_SESSION['role'] !== 'admin') exit;

$request_id = $_GET['id'];
$admin_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE asset_requests SET status = 'denied', approved_on = NOW(), approved_by = ? WHERE id = ?");
$stmt->bind_param("ii", $admin_id, $request_id);
$stmt->execute();

logAction($conn, $admin_id, "Denied Request", "Denied request ID $request_id");

header("Location: view_requests.php?denied=1");
