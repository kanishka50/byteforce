<?php


require "sessionCheck.php";


// Include the config file for database connection
require('Config/config.php');

// Handle form submission for adding order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $date_added = $_POST['date_added'];

    // Initialize the total amount
    $amount = 0;

    // Start a transaction
    $conn->begin_transaction();

    // Initialize the order_id variable
    $order_id = null;

    try {
        // Insert the new order into the database
        $sqlOrder = "INSERT INTO Orders (CustomerID, DateAdded, Amount) VALUES (?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("isd", $customer_id, $date_added, $amount);

        if ($stmtOrder->execute()) {
            // Get the last inserted OrderID
            $order_id = $stmtOrder->insert_id;
            $stmtOrder->close();
        } else {
            throw new Exception("Error adding order: " . $conn->error);
        }

        // Handle the insertion of items into ItemHasOrder
        $item_ids = $_POST['item_id'];
        $quantities = $_POST['quantity'];

        // Calculate the total amount
        for ($i = 0; $i < count($item_ids); $i++) {
            $item_id = $item_ids[$i];
            $quantity = $quantities[$i];

            // Validate the requested quantity
            $sqlCheckQuantity = "SELECT Quantity, SellingPrice FROM Item WHERE ItemID = ?";
            $stmtCheckQuantity = $conn->prepare($sqlCheckQuantity);
            $stmtCheckQuantity->bind_param("i", $item_id);
            $stmtCheckQuantity->execute();
            $stmtCheckQuantity->bind_result($available_quantity, $selling_price);
            $stmtCheckQuantity->fetch();
            $stmtCheckQuantity->close();

            if ($quantity > $available_quantity) {
                throw new Exception("Requested quantity for item ID $item_id exceeds available stock.");
            }

            if ($quantity <= 0) {
                throw new Exception("Quantity must be a positive number.");
            }

            // Calculate the item total price
            $amount += $selling_price * $quantity;

            // Insert the item into ItemHasOrder
            $sqlItemOrder = "INSERT INTO ItemHasOrder (ItemID, OrderID, Quantity) VALUES (?, ?, ?)";
            $stmtItemOrder = $conn->prepare($sqlItemOrder);
            $stmtItemOrder->bind_param("iii", $item_id, $order_id, $quantity);
            $stmtItemOrder->execute();
            $stmtItemOrder->close();

            // Update the Item table to subtract the ordered quantity
            $sqlUpdateItem = "UPDATE Item SET Quantity = Quantity - ? WHERE ItemID = ?";
            $stmtUpdateItem = $conn->prepare($sqlUpdateItem);
            $stmtUpdateItem->bind_param("ii", $quantity, $item_id);
            $stmtUpdateItem->execute();
            $stmtUpdateItem->close();
        }

        // Update the order amount
        $sqlUpdateOrderAmount = "UPDATE Orders SET Amount = ? WHERE OrderID = ?";
        $stmtUpdateOrderAmount = $conn->prepare($sqlUpdateOrderAmount);
        $stmtUpdateOrderAmount->bind_param("di", $amount, $order_id);
        $stmtUpdateOrderAmount->execute();
        $stmtUpdateOrderAmount->close();

        // Commit the transaction
        $conn->commit();

        echo "<script>alert('Order and items added successfully.'); window.location.href='order.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction if there is an error
        $conn->rollback();
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }

    // Close the connection
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styleSideBar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="images/electroKeep_favicon.ico" type="image/x-icon" style="height: 32px;width: 32px;" />
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            padding-top: 80px;
            padding-bottom: 50px;
        }
        .form-control {
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        .btn-primary {
            border-radius: 0.25rem;
            font-weight: 600;
            padding: 10px 20px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: linear-gradient(90deg, #007bff, #0056b3);
            border: 1px solid transparent;
            color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #004085);
            border-color: #004085;
            box-shadow: 0 6px 8px rgba(0,0,0,0.2);
        }
        h1 {
            margin-bottom: 2rem;
            font-weight: 500;
            font-style:bold;
        }
    
    </style>

</head>

<body>
    <div class="wrapper">
      
            <?php
            include "sideBar.php";

            ?>


        <!-- content-->
        <div class="main p-3">



        <div class="container">
        <h2>Add Order</h2>

        <!-- Form to add order -->
        <form action="" method="POST" id="order-form">
            <div class="form-group">
                <label for="customer_id">Customer Name:</label>
                <select class="form-control" id="customer_id" name="customer_id" required>
                    <option value="">Select a Customer</option>
                    <?php
                    // Fetch customer names and IDs from the Customer table
                    $sql = "SELECT CustomerID, Name FROM Customer ORDER BY Name";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['CustomerID']) . "'>" . htmlspecialchars($row['Name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No customers found</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date_added">Date Added:</label>
                <input type="datetime-local" class="form-control" id="date_added" name="date_added" required>
            </div>
            
            <!-- Add items section -->
            <div class="form-group">
                <label for="item_id">Item Name:</label>
                <select class="form-control" id="item_id" name="item_id[]" required>
                    <option value="">Select an Item</option>
                    <?php
                    // Fetch item names and IDs from the Item table
                    $sql = "SELECT ItemID, Name FROM Item ORDER BY Name";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['ItemID']) . "'>" . htmlspecialchars($row['Name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No items found</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity[]" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Add Order</button>
            <a href="order.php" class="btn btn-default btn-cancel">Cancel</a>
        </form>
    </div>
   
    <script>
    $(document).ready(function() {
        $('#order-form').on('change', 'select, input', function() {
            let totalAmount = 0;
            $('select[name="item_id[]"]').each(function(index) {
                let itemId = $(this).val();
                let quantity = $('input[name="quantity[]"]').eq(index).val();
                if (itemId && quantity) {
                    $.ajax({
                        url: 'orderAddetItemPrice.php',
                        type: 'POST',
                        data: { item_id: itemId },
                        dataType: 'json',
                        success: function(response) {
                            let price = response.selling_price;
                            totalAmount += (price * quantity);
                            $('#amount').val(totalAmount.toFixed(2));
                        }
                    });
                }
            });
        });
    });
    </script>
    
          


          <!-- Include category view cards -->
   


            </div>
        </div>
    </div>
   

   

   
    <script src="script.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script> -->
   
</body>

</html>