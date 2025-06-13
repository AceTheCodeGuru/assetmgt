<?php
include '../config.php';
include '../includes/db.php';
include '../includes/auth.php'; // User must be logged in
include '../includes/logger.php';


//Only allow admins to register new users
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php?error=unauthorized");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Check for existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $full_name, $email, $hashed_password, $role);

            if ($insert->execute()) {
                $success = "User registered successfully.";

                    // Log the action
                    $admin_id = $_SESSION['user_id'];
                    $registered_email = $email;
                    logAction($conn, $admin_id, "User Registration", "Registered new user: $registered_email");
            } else {
                $error = "Registration failed.";
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<h3>User Registration</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Full Name</label>
        <input name="full_name" type="text" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="ict">ICT Staff</option>
            <option value="employee">Employee</option>
        </select>
    </div>
    <button class="btn btn-success">Register</button>
</form>

<?php include '../includes/footer.php'; ?>
