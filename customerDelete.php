<?php

require "sessionCheck.php";


require('Config/config.php');

if (isset($_GET['id'])) {
    // Capture the Customer ID from the URL
    $customerId = $_GET['id'];

    // Prepare and execute the SQL query to delete the customer
    $sql = "DELETE FROM Customer WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-4'>Customer deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger mt-4'>Error deleting customer: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
    header("Location: viewCustomer.php"); // Redirect back to the viewCustomer page
    exit();
} else {
    echo "<div class='alert alert-danger mt-4'>No Customer ID provided.</div>";
    header("Location: viewCustomer.php"); // Redirect back to the viewCustomer page
    exit();
}
?>
