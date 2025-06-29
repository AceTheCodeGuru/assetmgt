<?php
include '../config.php';
include '../includes/db.php';
include '../includes/auth.php'; // User must be logged in
include '../includes/logger.php';
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4" style="margin-left: 220px;">
  <h3>All Registered Assets</h3>

  <table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Asset Name</th>
        <th>Type</th>
        <th>Serial Number</th>
        <th>Purchase Date</th>
        <th>Status</th>
        <th>Location</th>
        <th>Assigned To</th>
        <th>Added By</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT a.*, 
                     u.full_name AS added_by_name,
                     aa.user_id AS assigned_user_id,
                     au.full_name AS assigned_to_name,
                     CASE 
                       WHEN aa.user_id IS NOT NULL AND aa.status = 'active' THEN 'Assigned'
                       ELSE 'Available'
                     END AS current_status
              FROM assets a 
              LEFT JOIN users u ON a.added_by = u.id 
              LEFT JOIN asset_assignments aa ON a.id = aa.asset_id AND aa.status = 'active'
              LEFT JOIN users au ON aa.user_id = au.id
              ORDER BY a.id DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['asset_name']) ?></td>
            <td><?= htmlspecialchars($row['asset_type']) ?></td>
            <td><?= htmlspecialchars($row['serial_number']) ?></td>
            <td><?= htmlspecialchars($row['purchase_date']) ?></td>
            <td>
              <span class="badge <?= $row['current_status'] === 'Assigned' ? 'bg-warning' : 'bg-success' ?>">
                <?= htmlspecialchars($row['current_status']) ?>
              </span>
            </td>
            <!-- <td><?= htmlspecialchars($row['status']) ?></td> -->
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= $row['assigned_to_name'] ? htmlspecialchars($row['assigned_to_name']) : '-' ?></td>            
            <td><?= htmlspecialchars($row['added_by_name']) ?></td>
          </tr>
      <?php
        endwhile;
      else:
        echo "<tr><td colspan='8' class='text-center'>No assets found.</td></tr>";
      endif;
      ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
