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
            header("Location: pages/dashboard.php");
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

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <img src="assets/images/zambian-coat-of-arms.png" alt="Zambian Coat of Arms" class="img-fluid" style="max-width: 120px; height: auto;">
                    <h4 class="mt-3">MFL Asset Management System</h4>
                    <p class="text-muted">Please login to continue</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class=""form-label">Email</label>
                        <input name="email" type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class=""form-label">Password</label>
                        <input name="password" type="password" class="form-control" id="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}
.form-control {
    border-radius: 5px;
    padding: 12px;
}
.btn-primary {
    border-radius: 5px;
    padding: 12px;
}
</style>

<?php include 'includes/footer.php'; ?>