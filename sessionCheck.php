<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["u"]) || empty($_SESSION["u"])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

?>