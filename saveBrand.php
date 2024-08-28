<?php
require "sessionCheck.php";
require "connection.php";

// Ensure the $conn variable is defined
$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brandID = $_POST['brandID'];
    $brandName = $_POST['brandName'];

    if (!empty($brandName)) {
        if ($brandID) {
            // Update existing brand
            $query = "UPDATE `brand` SET `Name` = ? WHERE `BrandID` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $brandName, $brandID);
        } else {
            // Add new brand
            $query = "INSERT INTO `brand` (`Name`) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $brandName);
        }

        if ($stmt->execute()) {
            header("Location: managebrand.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Brand name cannot be empty.";
    }
} else {
    header("Location: managebrand.php");
    exit();
}
?>