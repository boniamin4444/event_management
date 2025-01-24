<?php
session_start();

// Destroy all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect to the homepage or login page
header("Location: index.php"); // Change index.php to your desired page
exit;
?>
