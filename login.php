<?php
include 'config.php';
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            if ($user['first_login'] == 1) {
                header("Location: reset_password.php");
                exit;
            }

            // Redirect based on role
            if ($_SESSION['role'] === 'admin') {
                header("Location: pages/dashboard_admin.php");
            } elseif ($_SESSION['role'] === 'ict') {
                header("Location: pages/dashboard_ict.php");
            } else {
                header("Location: pages/dashboard_employee.php");
            }
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<h3>Login</h3>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control" required>
    </div>
    <button class="btn btn-primary">Login</button>
</form>

<?php include 'includes/footer.php'; ?>