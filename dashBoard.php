<?php
require "sessionCheck.php";

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteForce - Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styleSideBar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="images/electroKeep_favicon.ico" type="image/x-icon" style="height: 32px;width: 32px;" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            padding-top: 80px;
            padding-bottom: 50px;
        }

        .card {
            border-radius: 0.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: #fff;
            text-align: center;
            padding:20px;
    
            }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #fff;
        }

        .card-body {
            font-size: 0.9rem;
            color: #fff;
        }

        .card-title {
            font-size: 1.25rem;
        }

        .list-group-item {
            border: none;
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .stats-card {
            background: #007bff;
            color: #fff;
            text-align: center;
            border-radius: 0.25rem;
            padding: 15px;
        }

        .stats-card h5 {
            margin: 0;
            font-size: 1.5rem;
        }

        .stats-card p {
            font-size: 2rem;
            margin: 0;
        }

        /* Custom gradients for each card */
        .card-categories {
            background: linear-gradient(135deg, #3a3a3a, #1f1f1f);

        }

        .card-items {
            background: linear-gradient(135deg, #3a3a3a, #1f1f1f);

        }

        .card-suppliers {
            background: linear-gradient(135deg, #3a3a3a, #1f1f1f);


        }

        .card-customers {
            background: linear-gradient(135deg, #3a3a3a, #1f1f1f);


        }
    </style>
</head>

<body>
<div class="wrapper">
    <?php include "sideBar.php"; ?>

    <!-- content-->
    <div class="main p-3">
    <h1 class="text-center mb-4">Byte Force - Dashboard</h1>
        <div class="container">
           

            <div class="row mb-4">
                <?php
                require('Config/config.php');

                // Define queries to get counts
                $queries = [
                    'Categories' => 'SELECT COUNT(*) AS count FROM Category',
                    'Items' => 'SELECT COUNT(*) AS count FROM Item',
                    'Suppliers' => 'SELECT COUNT(*) AS count FROM Supplier',
                    'Customers' => 'SELECT COUNT(*) AS count FROM Customer'
                ];

                $cardClasses = [
                    'Categories' => 'card-categories',
                    'Items' => 'card-items',
                    'Suppliers' => 'card-suppliers',
                    'Customers' => 'card-customers'
                ];

                foreach ($queries as $title => $query) {
                    $result = $conn->query($query);
                    $count = $result->fetch_assoc()['count'];
                ?>
                    <div class="col-md-3 mb-4">
                        <div class="card <?php echo $cardClasses[$title]; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $title; ?></h5>
                               <h2 class="card-text"><?php echo $count; ?></h2>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Latest Categories</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Query to get the latest categories
                            $sql = "SELECT Name, Description FROM Category ORDER BY CategoryID DESC LIMIT 5";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<ul class="list-group">';
                                while ($row = $result->fetch_assoc()) {
                                    echo '<li class="list-group-item">';
                                    echo '<strong>' . htmlspecialchars($row['Name']) . '</strong>: ' . htmlspecialchars($row['Description']);
                                    echo '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<p>No categories found.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Latest Brands</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Query to get the latest brands
                            $sql = "SELECT Name FROM Brand ORDER BY BrandID DESC LIMIT 5";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<ul class="list-group">';
                                while ($row = $result->fetch_assoc()) {
                                    echo '<li class="list-group-item">';
                                    echo htmlspecialchars($row['Name']);
                                    echo '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<p>No brands found.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Total Sales</h5>
                        </div>
                        <div class="card-body" style="color:black;">
                            <?php
                            Query to get the total sales amount
                            $sql = "SELECT SUM(Amount) as total_sales FROM orders";
                            $result = $conn->query($sql);
                            $total_sales = $result->fetch_assoc()['total_sales'];
                            echo '<h3>$' . number_format($total_sales, 2) . '</h3>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>

</html>
