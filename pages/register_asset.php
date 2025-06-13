<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';
include '../includes/header.php';
include '../includes/sidebar.php';

if ($_SESSION['role'] !== 'admin') {
  header("Location: dashboard.php");
  exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $asset_name = trim($_POST['asset_name']);
  $asset_type = trim($_POST['asset_type']);
  $serial_number = trim($_POST['serial_number']);
  $purchase_date = trim($_POST['purchase_date']);
  $status = trim($_POST['status']);
  $location = trim($_POST['location']);
  $added_by = $_SESSION['user_id'];

  if ($asset_name && $serial_number) {
    $stmt = $conn->prepare("INSERT INTO assets (asset_name, asset_type, serial_number, purchase_date, status, location, added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $asset_name, $asset_type, $serial_number, $purchase_date, $status, $location, $added_by);

    if ($stmt->execute()) {
      logAction($conn, $added_by, 'Register Asset', "Added asset: $asset_name ($serial_number)");
      $success = "Asset registered successfully.";
    } else {
      $error = "Error: Could not register asset.";
    }
  } else {
    $error = "Asset name and serial number are required.";
  }
}
?>

<div class="container mt-4" style="margin-left: 220px;">
  <h3>Register New Asset</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Asset Name *</label>
      <input type="text" name="asset_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Asset Type</label>
      <input type="text" name="asset_type" class="form-control">
    </div>
    <div class="mb-3">
      <label>Serial Number *</label>
      <input type="text" name="serial_number" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Purchase Date</label>
      <input type="date" name="purchase_date" class="form-control">
    </div>
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="unassigned">Unassigned</option>
        <option value="assigned">Assigned</option>
        <option value="maintenance">Maintenance</option>
        <option value="disposed">Disposed</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Register Asset</button>
  </form>
</div>