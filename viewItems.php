<?php
require "sessionCheck.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
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

        h1 {
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .input-group .btn {
            border-radius: 0 0.25rem 0.25rem 0;
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
            display: block;
            margin-bottom: 0.5rem;
        }

        .modal-body .form-control {
            margin-bottom: 1rem;
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
                    <h1 class="mb-4" style="font-weight:bold;">Items List</h1>
                    
                    <form action="viewItems.php" method="POST" class="mb-4 d-flex">
                        <button class="btn btn-csv" type="button" onclick="exportTableToCSV('itemslist.csv')">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchTerm" placeholder="Search by Name or Brand">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <?php
                    // Include the config file for database connection
                    require('Config/config.php');

                    // Capture the search term if available
                    $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

                    // Modify the SQL query to include a search condition for Name or Brand
                    $sql = "
                    SELECT Item.ItemID, Item.Name, Brand.BrandID, Brand.Name AS BrandName, Item.SellingPrice, 
                           Item.PurchasePrice, Item.Quantity, Item.Status, Item.Description, Category.CategoryID, Category.Name AS CategoryName
                    FROM Item
                    LEFT JOIN Category ON Item.CategoryID = Category.CategoryID
                    LEFT JOIN Brand ON Item.BrandID = Brand.BrandID
                ";

                    if ($searchTerm != '') {
                        $sql .= " WHERE Item.Name LIKE ? OR Brand.Name LIKE ?";
                    }

                    // Prepare and execute the query
                    $stmt = $conn->prepare($sql);

                    if ($searchTerm != '') {
                        $searchTerm = "%" . $searchTerm . "%";
                        $stmt->bind_param("ss", $searchTerm, $searchTerm);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Selling Price</th>
                                <th>Purchase Price</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Loop through the result set and display the items
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ItemID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['BrandName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['SellingPrice']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['PurchasePrice']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                echo "<td>" . ($row['Status'] ? 'Yes' : 'No') . "</td>";
                                echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CategoryName']) . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-action' data-bs-toggle='modal' data-bs-target='#updateModal' data-itemid='" . htmlspecialchars($row['ItemID']) . "' data-itemname='" . htmlspecialchars($row['Name']) . "' data-itembrand='" . htmlspecialchars($row['BrandID']) . "' data-itemcategory='" . htmlspecialchars($row['CategoryID']) . "' data-itemsellingprice='" . htmlspecialchars($row['SellingPrice']) . "' data-itemquantity='" . htmlspecialchars($row['Quantity']) . "' data-itemdescription='" . htmlspecialchars($row['Description']) . "' style='background-color: #007bff; color: #ffffff;'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <a href='itemDelete.php?id=" . htmlspecialchars($row['ItemID']) . "' class='btn btn-action' title='Delete' style='background-color: #dc3545; color: #ffffff;'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                        <a href='itemAddQuantity.php?id=" . htmlspecialchars($row['ItemID']) . "' class='btn btn-action' title='Add Quantity' style='background-color: #28a745; color: #ffffff;'>
                                            <i class='fas fa-plus'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No items found</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Item Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateItemForm" action="updateItem.php" method="POST">
                        <input type="hidden" name="itemId" id="itemId">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="itemName" name="itemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemBrand" class="form-label">Brand</label>
                            <select class="form-select" id="itemBrand" name="itemBrand" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="itemCategory" class="form-label">Category</label>
                            <select class="form-select" id="itemCategory" name="itemCategory" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="itemSellingPrice" class="form-label">Selling Price</label>
                            <input type="number" step="0.01" class="form-control" id="itemSellingPrice" name="itemSellingPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="itemQuantity" name="itemQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="itemDescription" name="itemDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to populate modal with item data
        document.addEventListener('DOMContentLoaded', function () {
            var updateModal = document.getElementById('updateModal');
            updateModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var itemId = button.getAttribute('data-itemid');
                var itemName = button.getAttribute('data-itemname');
                var itemBrand = button.getAttribute('data-itembrand');
                var itemCategory = button.getAttribute('data-itemcategory');
                var itemSellingPrice = button.getAttribute('data-itemsellingprice');
                var itemQuantity = button.getAttribute('data-itemquantity');
                var itemDescription = button.getAttribute('data-itemdescription');

                var modalItemId = updateModal.querySelector('#itemId');
                var modalItemName = updateModal.querySelector('#itemName');
                var modalItemBrand = updateModal.querySelector('#itemBrand');
                var modalItemCategory = updateModal.querySelector('#itemCategory');
                var modalItemSellingPrice = updateModal.querySelector('#itemSellingPrice');
                var modalItemQuantity = updateModal.querySelector('#itemQuantity');
                var modalItemDescription = updateModal.querySelector('#itemDescription');

                modalItemId.value = itemId;
                modalItemName.value = itemName;
                modalItemSellingPrice.value = itemSellingPrice;
                modalItemQuantity.value = itemQuantity;
                modalItemDescription.value = itemDescription;

                // Fetch brands and categories from the database and set selected values
                fetch('getBrands.php')
                    .then(response => response.json())
                    .then(data => {
                        modalItemBrand.innerHTML = data.map(brand => 
                            `<option value="${brand.BrandID}" ${brand.BrandID == itemBrand ? 'selected' : ''}>${brand.Name}</option>`
                        ).join('');
                    });

                fetch('getCategories.php')
                    .then(response => response.json())
                    .then(data => {
                        modalItemCategory.innerHTML = data.map(category => 
                            `<option value="${category.CategoryID}" ${category.CategoryID == itemCategory ? 'selected' : ''}>${category.Name}</option>`
                        ).join('');
                    });
            });
        });


        function downloadCSV(csv, filename) {
            let csvFile;
            let downloadLink;

            // CSV file
            csvFile = new Blob([csv], { type: 'text/csv' });

            // Download link
            downloadLink = document.createElement('a');

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = 'none';

            // Add the link to the DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();
        }

        function exportTableToCSV(filename) {
            let csv = [];
            let rows = document.querySelectorAll('table tr');

            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');

                for (let j = 0; j < cols.length - 1; j++) 
                    row.push(cols[j].innerText);

                csv.push(row.join(','));
            }

            // Download CSV file
            downloadCSV(csv.join('\n'), filename);
        }
    </script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>
