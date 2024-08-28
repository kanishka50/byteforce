<?php
require "sessionCheck.php";

// Include the config file for database connection
require('Config/config.php');

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the data from the form
    $categoryID = isset($_POST['categoryID']) ? $_POST['categoryID'] : '';
    $categoryName = isset($_POST['categoryName']) ? $_POST['categoryName'] : '';
    $categoryDescription = isset($_POST['categoryDescription']) ? $_POST['categoryDescription'] : '';

    // Validate inputs
    if (empty($categoryID) || empty($categoryName) || empty($categoryDescription)) {
        echo "All fields are required.";
        exit();
    }

    // Prepare an SQL statement for updating the category
    $sql = "UPDATE Category SET Name = ?, Description = ? WHERE CategoryID = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $categoryName, $categoryDescription, $categoryID);

        if ($stmt->execute()) {
            // Redirect to viewCategory page with a success message
            header("Location: viewCategory.php?update=success");
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
    // Redirect to viewCategory page if accessed without POST request
    header("Location: viewCategory.php");
}
?>
