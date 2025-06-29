<?php
// Make sure the session is started and the user's role is set
$role = $_SESSION['role'] ?? 'user'; // Default to 'user' if not set
?>

<div class="d-flex">
<nav class="bg-dark text-white vh-100 p-3" style="width: 220px; position: fixed; top: 0; left: 0; z-index: 1000;">
<h4 class="mb-4">AMS</h4>
<ul class="nav flex-column">

<li class="nav-item mb-2">
<a href="dashboard.php" class="nav-link text-white">Dashboard</a>
</li>

<?php if ($role === 'admin'): ?>
    <li class="nav-item mb-2">
        <a href="manage_users.php" class="nav-link text-white">Manage Users</a>
    </li>
    <li class="nav-item mb-2">
        <a href="register_user.php" class="nav-link text-white">Register User</a>
    </li>
    <li class="nav-item mb-2">
        <a href="view_assets.php" class="nav-link text-white">View Assets</a>  
    </li>      
    <li class="nav-item mb-2">
        <a href="register_asset.php" class="nav-link text-white">Register Asset</a>
    </li>  
    <li class="nav-item mb-2">
        <a href="assign_asset.php" class="nav-link text-white">Assign Assets</a>
    </li>
    <li class="nav-item mb-2">
        <a href="view_assigned_assets.php" class="nav-link text-white">View Assigned Assets</a>
    </li>
    <li class="nav-item mb-2">
        <a href="view_requests.php" class="nav-link text-white">View Requests</a>
    </li>
    <li class="nav-item mb-2">
        <a href="logs.php" class="nav-link text-white">Activity Logs</a>
    </li> 
    
    <?php endif; ?>
    
    <li class="nav-item mb-2">
    <a href="assets.php" class="nav-link text-white">My Assets</a>
    </li>
    <li class="nav-item mb-2">
    <a href="maintenance.php" class="nav-link text-white">Maintenance</a>
    </li>
    <li class="nav-item mb-2">
    <a href="request_asset.php" class="nav-link text-white">Request Asset</a>
    </li>

    <li class="nav-item mt-4">
    <a href="../logout.php" class="nav-link text-danger">Logout</a>
    </li>
    </ul>
    </nav>
    
    <div class="flex-grow-1 p-4" style="margin-left: 220px;">