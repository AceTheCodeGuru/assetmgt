<?php
include 'config.php';
include 'includes/auth.php';
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Both fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, first_login = 0 WHERE id = ?");
        $stmt->bind_param("si", $hashed, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $success = "Password updated. Redirecting...";
            header("refresh:2;url=pages/dashboard_" . $_SESSION['role'] . ".php");
            exit;
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<h3>Set a New Password</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>New Password</label>
        <input name="new_password" type="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Confirm Password</label>
        <input name="confirm_password" type="password" class="form-control" required>
    </div>
    <button class="btn btn-primary">Set Password</button>
</form>

<?php include 'includes/footer.php'; ?>