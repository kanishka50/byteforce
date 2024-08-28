<?php

require "sessionCheck.php";

require "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brandID = $_POST['brandID'];
    $brandName = $_POST['brandName'];

    if ($brandID) {
        // Update brand
        Database::iud("UPDATE `brand` SET `Name` = '{$brandName}' WHERE `BrandID` = {$brandID}");
    } else {
        // Add new brand
        Database::iud("INSERT INTO `brand` (`Name`) VALUES ('{$brandName}')");
    }
    header("Location: managebrand.php");
    exit();
}

if (isset($_GET['delete'])) {
    $brandID = $_GET['delete'];
    Database::iud("DELETE FROM `brand` WHERE `BrandID` = {$brandID}");
    header("Location: managebrand.php");
    exit();
}
?>