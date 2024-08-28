<?php
require "sessionCheck.php";

// Include the config file for database connection
require('Config/config.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the form data
    $customerID = $_POST['customerID'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $customerPhone = $_POST['customerPhone'];

    // Validate inputs
    if (empty($customerID) || empty($customerName) || empty($customerEmail) || empty($customerPhone)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare the SQL query
    $sql = "UPDATE Customer SET Name = ?, Email = ?, Telephone = ? WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param("sssi", $customerName, $customerEmail, $customerPhone, $customerID);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect or send success message
        header("Location: viewCustomer.php?update=success");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
