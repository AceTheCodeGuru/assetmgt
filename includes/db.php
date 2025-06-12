<?php
$host = 'localhost';
$db   = 'asset_db';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
