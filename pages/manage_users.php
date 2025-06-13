<?php
include '../config.php';
include '../includes/auth.php';
include '../includes/logger.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}
?>

<h2>Manage Users</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>First Login</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM users");
        $count = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $count++ ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['role'] ?></td>
            <td><?= $row['first_login'] ? 'Yes' : 'No' ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="reset_user_password.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning">Reset Password</a>
                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
