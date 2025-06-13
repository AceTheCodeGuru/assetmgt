<?php
include '../config.php';
include '../includes/db.php';
include '../includes/auth.php'; // User must be logged in
include '../includes/logger.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php if (isset($_GET['removed'])): ?>
    <div class="alert alert-success">Assignment removed successfully.</div>
<?php endif; ?>

<?php if (isset($_GET['reassigned'])): ?>
    <div class="alert alert-success">Asset reassigned successfully.</div>
<?php endif; ?>

<div class="container mt-4" style="margin-left: 220px;">
    <h4>Assigned Assets</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Asset Name</th>
                <th>Asset Type</th>
                <th>Serial Number</th>
                <th>Assigned To</th>
                <th>Assigned By</th>
                <th>Assigned Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "
            SELECT 
                aa.id,
                a.asset_name,
                a.asset_type,
                a.serial_number,
                u1.full_name AS assigned_to,
                u2.full_name AS assigned_by,
                aa.assigned_date
            FROM asset_assignments aa
            INNER JOIN assets a ON aa.asset_id = a.id
            INNER JOIN users u1 ON aa.user_id = u1.id
            INNER JOIN users u2 ON aa.assigned_by = u2.id
            WHERE aa.status = 'active'
            ORDER BY aa.assigned_date DESC
        ";

        $result = $conn->query($query);
        $count = 1;

        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= htmlspecialchars($row['asset_name']) ?></td>
                <td><?= htmlspecialchars($row['asset_type']) ?></td>
                <td><?= htmlspecialchars($row['serial_number']) ?></td>
                <td><?= htmlspecialchars($row['assigned_to']) ?></td>
                <td><?= htmlspecialchars($row['assigned_by']) ?></td>
                <td><?= htmlspecialchars(date('d M Y, H:i', strtotime($row['assigned_date']))) ?></td>
                <td>
                    <a href="edit_assignment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="deactivate_assignment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this assignment?');">Remove</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
