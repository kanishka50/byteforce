<?php
require "sessionCheck.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories List</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styleSideBar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
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

        .text-center{
            margin-top:5px;
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

        <!-- Content -->
        <div class="main p-3">
            <div class="text-center">
                <div class="container">
                    <h1 class="mb-4">Categories List</h1>

                    <form action="viewCategory.php" method="POST" class="mb-4 d-flex">
                        <button class="btn btn-csv" type="button" onclick="exportTableToCSV('categorieslist.csv')">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchTerm" placeholder="Search by Name or Description">
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

                    // Modify the SQL query to include a search condition for Name or Description
                    $sql = "
                        SELECT Category.CategoryID, Category.Name, Category.Description
                        FROM Category
                    ";

                    if ($searchTerm != '') {
                        $sql .= " WHERE Category.Name LIKE ? OR Category.Description LIKE ?";
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
                                <th>Category ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Loop through the result set and display the categories
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['CategoryID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-action' data-bs-toggle='modal' data-bs-target='#updateModal' data-categoryid='" . htmlspecialchars($row['CategoryID']) . "' data-categoryname='" . htmlspecialchars($row['Name']) . "' data-categorydescription='" . htmlspecialchars($row['Description']) . "' style='background-color: #007bff; color: #ffffff;'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <a href='categoryDelete.php?id=" . htmlspecialchars($row['CategoryID']) . "' class='btn btn-danger' title='Delete'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No categories found</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="updateCategory.php" method="POST">
                        <input type="hidden" name="categoryID" id="categoryID">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate modal fields with category data
        document.addEventListener('DOMContentLoaded', function () {
            var updateModal = document.getElementById('updateModal');
            updateModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var categoryID = button.getAttribute('data-categoryid');
                var categoryName = button.getAttribute('data-categoryname');
                var categoryDescription = button.getAttribute('data-categorydescription');

                var modal = updateModal.querySelector('.modal-body #categoryID');
                var modalName = updateModal.querySelector('.modal-body #categoryName');
                var modalDescription = updateModal.querySelector('.modal-body #categoryDescription');

                modal.value = categoryID;
                modalName.value = categoryName;
                modalDescription.value = categoryDescription;
            });
        });

        // CSV export function
        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length - 1; j++) {
                    row.push(cols[j].innerText);
                }

                csv.push(row.join(","));
            }

            var csvFile;
            var downloadLink;

            // Create CSV file
            csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

            // Create a link to download it
            downloadLink = document.createElement("a");

            // Set the file name
            downloadLink.download = filename;

            // Create a link to the CSV file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Make the link clickable
            downloadLink.click();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="script.js"></script>
</body>

</html>
