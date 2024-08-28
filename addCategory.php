<?php
require "sessionCheck.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styleSideBar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="images/electroKeep_favicon.ico" type="image/x-icon" style="height: 32px;width: 32px;" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            padding-top: 50px;
            max-width: 600px;
            margin-top:40px;
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #004085);
            border-color: #004085;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
<div class="wrapper">
    <?php include "sideBar.php"; ?>

    <!-- content-->
    <div class="main p-3">
        <div class="container">
            <h1 class="text-center mb-4">Add Category</h1>
            <form action="addCategory.php" method="POST">
                <?php

                    // check the request method
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    // include configuration file
                    require('Config/config.php');

                    // Capture form data
                    $categoryName = $_POST['categoryName'];
                    $categoryDescription = $_POST['categoryDescription'];

                    // Prepare and execute the SQL query
                    $sql = "INSERT INTO Category (Name, Description) VALUES (?, ?)";          /*$sql is an SQL statement that uses placeholders (?) for the values that will be inserted into the Category table.*/
                    $stmt = $conn->prepare($sql);                                            /*This method is part of the mysqli extension in PHP, which provides a safer way to execute SQL queries.*/
                    $stmt->bind_param("ss", $categoryName, $categoryDescription);            /* binds the form data to the SQL query parameters:*/

                    if ($stmt->execute()) {                                                  /*executes the prepared statement. */
                        echo "<div class='alert alert-success mt-4' id='success-alert'>Category added successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-4' id='danger-alert'>Error adding category: " . $stmt->error . "</div>";
                    }

                    $stmt->close();                                                            /*closes the prepared statement to free up resources. */
                    $conn->close();                                                           /*closes the database connection. */
                }
                ?>

                <div class="form-group mb-3">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                </div>
                <div class="form-group mb-3">
                    <label for="categoryDescription" class="form-label">Category Description</label>
                    <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Add Category</button>
            </form>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
    }, 3000);

    setTimeout(function() {
        var dangerAlert = document.getElementById('danger-alert');
        if (dangerAlert) {
            dangerAlert.style.display = 'none';
        }
    }, 3000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>
