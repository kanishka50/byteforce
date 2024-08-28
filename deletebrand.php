<?php

require "sessionCheck.php";


require "connection.php";

if (isset($_GET['id'])) {
    $brandID = $_GET['id'];

    // Delete the brand from the database
    Database::iud("DELETE FROM `brand` WHERE `BrandID` = {$brandID}");

    // Redirect back to the manage brands page
    header("Location: managebrand.php");
    exit();
} else {
    // If no ID is provided, redirect back to the manage brands page
    header("Location: managebrand.php");
    exit();
}
?>