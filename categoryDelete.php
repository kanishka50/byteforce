<?php
require "sessionCheck.php";


// Include the config file for database connection
require('Config/config.php');

// Check if the category ID is provided in the query string
if (isset($_GET['id'])) {
    $categoryID = $_GET['id'];

    // Validate the category ID
    if (empty($categoryID) || !is_numeric($categoryID)) {
        echo "Invalid category ID.";
        exit();
    }

    // Prepare an SQL statement for deleting the category
    $sql = "DELETE FROM Category WHERE CategoryID = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $categoryID);

        if ($stmt->execute()) {
            // Redirect to viewCategory page with a success message
            header("Location: viewCategory.php?delete=success");
        } else {
            echo "Error: Could not execute the query.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to viewCategory page if accessed without an ID
    header("Location: viewCategory.php");
}
?>
