<?php
require_once('include/sql.php');

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure session variable `userid` is set
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: index.php");
    exit;
}

// Get the current date
$date = date('Y-m-d H:i:s'); // Using `date()` instead of deprecated `strftime`

// Update the user's status in the database
global $db; // Assuming $db is your database connection object
$sql = "UPDATE users 
        SET status = 0 
        WHERE id = '{$userId}' 
        LIMIT 1";

$result = $db->query($sql);
if (!$result) {
    die("Database query failed: " . $db->error);
}

// Unset session variables and destroy the session
if (isset($_SESSION['current_user'])) {
    unset($_SESSION['current_user']);
}
session_unset();
session_destroy();

// Redirect to login page or home page
header("Location: index.php");
exit;
?>