<?php
// Start the session
session_start();

// Clear the specific session variable
if (isset($_SESSION['current_user'])) {
    unset($_SESSION['current_user']);
}

// Optionally, clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page or home page
header("Location: index.php");
exit;
?>