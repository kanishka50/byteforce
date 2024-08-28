<?php
require "sessionCheck.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
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
            margin-top:10px;
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
                    <h1 class="mb-4">Customers List</h1>
                    
                    <form action="viewCustomer.php" method="POST" class="mb-4 d-flex">
                        <button class="btn btn-csv" type="button" onclick="exportTableToCSV('customerslist.csv')">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchTerm" placeholder="Search by Name or Email">
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

                    // Modify the SQL query to include a search condition for Name or Email
                    $sql = "
                        SELECT Customer.CustomerID, Customer.Name, Customer.Email, Customer.Telephone
                        FROM Customer
                    ";

                    if ($searchTerm != '') {
                        $sql .= " WHERE Customer.Name LIKE ? OR Customer.Email LIKE ?";
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
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Loop through the result set and display the customers
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['CustomerID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Telephone']) . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-action' data-bs-toggle='modal' data-bs-target='#updateModal' data-customerid='" . htmlspecialchars($row['CustomerID']) . "' data-customername='" . htmlspecialchars($row['Name']) . "' data-customermail='" . htmlspecialchars($row['Email']) . "' data-customerphone='" . htmlspecialchars($row['Telephone']) . "' style='background-color: #007bff; color: #ffffff;'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <a href='customerDelete.php?id=" . htmlspecialchars($row['CustomerID']) . "' class='btn btn-action' title='Delete' style='background-color: #dc3545; color: #ffffff;'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No customers found</td></tr>";
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
                    <h5 class="modal-title" id="updateModalLabel">Update Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="updateCustomer.php" method="POST">
                        <input type="hidden" name="customerID" id="customerID">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customerEmail" name="customerEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customerPhone" name="customerPhone" pattern="\d{10}" title="Phone number must be 10 digits" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate modal fields with customer data
        document.addEventListener('DOMContentLoaded', function () {
            var updateModal = document.getElementById('updateModal');
            updateModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var customerID = button.getAttribute('data-customerid');
                var customerName = button.getAttribute('data-customername');
                var customerEmail = button.getAttribute('data-customermail');
                var customerPhone = button.getAttribute('data-customerphone');

                var modal = updateModal.querySelector('.modal-body #customerID');
                var modalName = updateModal.querySelector('.modal-body #customerName');
                var modalEmail = updateModal.querySelector('.modal-body #customerEmail');
                var modalPhone = updateModal.querySelector('.modal-body #customerPhone');

                modal.value = customerID;
                modalName.value = customerName;
                modalEmail.value = customerEmail;
                modalPhone.value = customerPhone;
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
