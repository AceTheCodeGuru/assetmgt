<?php

// Define session timeout (in seconds) - 30 minutes = 1800 seconds
define('SESSION_TIMEOUT', 1800);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check for session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    // Session has timed out
    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit;
}


// Update last activity timestamp
$_SESSION['last_activity'] = time();
