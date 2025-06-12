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
$error = '';
$success = '';

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if (empty($full_name) || empty($email)) {
        $error = "Full name and email are required.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $email, $role, $user_id);

        if ($stmt->execute()) {
            $success = "User updated successfully.";
            logAction($conn, $_SESSION['user_id'], "Updated User", "Updated user ID $user_id");
        } else {
            $error = "Failed to update user.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit User</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php elseif ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Full Name</label>
        <input name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <button class="btn btn-primary">Update User</button>
    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>
