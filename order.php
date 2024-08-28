<?php
require "sessionCheck.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styleSideBar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="images/electroKeep_favicon.ico" type="image/x-icon" style="height: 32px;width: 32px;" />
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
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
        <?php include "sideBar.php"; ?>

        <!-- content-->
        <div class="main p-3">
            <div class="row mb-3">
                <!-- Manage Inventory button aligned to the left -->
                <div class="col text-start">
                    <a href="orderDownload.php" class="btn btn-success">Download CSV</a>
                </div>
                
                <!-- Add Order button aligned to the right -->
                <div class="col text-end">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='orderAdd.php';">
                        Add Order
                    </button>
                </div>
            </div>
          
            <!-- Include category view cards -->
            <?php include_once "orderView.php"; ?>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>
