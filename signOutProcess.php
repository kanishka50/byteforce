<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Clear cookies if any
// setcookie("email", "", time() - 3600);
// setcookie("password", "", time() - 3600);

// Redirect to login page
header("Location: index.php");
exit();
?>
