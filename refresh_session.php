<?php
include 'config.php';
include 'includes/auth.php';

// This file just needs to include auth.php to refresh the session
// The auth.php file will update the last_activity timestamp
echo json_encode(['status' => 'refreshed']);