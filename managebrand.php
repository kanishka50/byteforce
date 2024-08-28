<?php
require "sessionCheck.php";
require "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands List</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styleSideBar.css">
    <link rel="icon" href="images/electroKeep_favicon.ico" type="image/x-icon" style="height: 32px;width: 32px;" />
    <style>
        body {
            background-color: #f8f9fa;
            font-size: 0.8rem;
        }

        .container {
            padding-top: 80px;
            padding-bottom: 50px;
        }

        .form-control {
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #004085);
            border-color: #004085;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-csv {
            border-radius: 0.25rem;
            font-weight: 600;
            padding: 10px 20px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: linear-gradient(90deg, #28a745, #218838);
            border: 1px solid transparent;
            color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-csv:hover {
            background: linear-gradient(90deg, #218838, #1e7e34);
            border-color: #1e7e34;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-body label {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include "sideBar.php"; ?>

        <!-- Content-->
        <div class="main p-3">
            <div class="text-center">
                <div class="container">
                    <h1 class="mb-4">Manage Brands</h1>
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#brandModal">Add Brand</button>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Brand ID</th>
                                <th>Brand Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $brand_rs = Database::search("SELECT * FROM `brand`");
                            while ($brand_data = $brand_rs->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($brand_data['BrandID']) . "</td>";
                                echo "<td>" . htmlspecialchars($brand_data['Name']) . "</td>";
                                echo "<td>
                                        <button class='btn ' data-bs-toggle='modal' data-bs-target='#brandModal' data-id='" . htmlspecialchars($brand_data['BrandID']) . "' data-name='" . htmlspecialchars($brand_data['Name']) . "'style='background-color: #007bff; color: #ffffff;'> <i class='fas fa-edit'></i></button>
                                        <button class='btn btn-danger ' onclick='deleteBrand(" . htmlspecialchars($brand_data['BrandID']) . ")'><i class='fas fa-trash'></i></button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Modal -->
    <div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">Add/Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="brandForm" action="saveBrand.php" method="POST">
                        <input type="hidden" name="brandID" id="brandID">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="brandName" name="brandName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate modal fields with brand data
        document.addEventListener('DOMContentLoaded', function () {
            var brandModal = document.getElementById('brandModal');
            brandModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var brandID = button.getAttribute('data-id');
                var brandName = button.getAttribute('data-name');

                var modalID = brandModal.querySelector('.modal-body #brandID');
                var modalName = brandModal.querySelector('.modal-body #brandName');

                modalID.value = brandID;
                modalName.value = brandName;
            });
        });

        function deleteBrand(brandID) {
            if (confirm('Are you sure you want to delete this brand?')) {
                window.location.href = 'deleteBrand.php?id=' + brandID;
            }
        }
    </script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>