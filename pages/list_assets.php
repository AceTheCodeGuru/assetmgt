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
        <th>Added By</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT a.*, u.full_name AS added_by_name 
              FROM assets a 
              LEFT JOIN users u ON a.added_by = u.id 
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
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
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
