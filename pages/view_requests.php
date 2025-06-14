<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$requests = $conn->query("
    SELECT ar.*, u.full_name, a.asset_name, a.serial_number 
    FROM asset_requests ar
    JOIN users u ON ar.user_id = u.id
    JOIN assets a ON ar.asset_id = a.id
    ORDER BY requested_at DESC
");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<h3>Asset Requests</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>User</th>
            <th>Asset</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Requested At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($r['full_name']) ?></td>
                <td><?= htmlspecialchars($r['asset_name']) ?> (<?= $r['serial_number'] ?>)</td>
                <td><?= nl2br(htmlspecialchars($r['reason'])) ?></td>
                <td><?= ucfirst($r['status']) ?></td>
                <td><?= $r['requested_at'] ?></td>
                <td>
                    <?php if ($r['status'] === 'pending'): ?>
                        <a href="approve_request.php?id=<?= $r['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="deny_request.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm">Deny</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
