<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

if ($_SESSION['role'] !== 'admin') exit;

$request_id = $_GET['id'];
$admin_id = $_SESSION['user_id'];

// Fetch asset and user
$stmt = $conn->prepare("SELECT user_id, asset_id FROM asset_requests WHERE id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$stmt->bind_result($user_id, $asset_id);
$stmt->fetch();
$stmt->close();

// Update request status
$stmt = $conn->prepare("UPDATE asset_requests SET status = 'approved', approved_on = NOW(), approved_by = ? WHERE id = ?");
$stmt->bind_param("ii", $admin_id, $request_id);
$stmt->execute();

// Assign asset
$stmt = $conn->prepare("INSERT INTO asset_assignments (asset_id, user_id, assigned_by, assigned_date, status) VALUES (?, ?, ?, NOW(), 'active')");
$stmt->bind_param("iii", $asset_id, $user_id, $admin_id);
$stmt->execute();

logAction($conn,$admin_id, "Approved Request", "Approved request ID $request_id and assigned asset ID $asset_id");

header("Location: view_requests.php?approved=1");
