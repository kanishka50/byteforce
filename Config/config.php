<?php
    $servername = "byteforceinventory.mysql.database.azure.com:3306";
    $username = "teki";
    $password = "Hacker@119";
    $dbname = "inventorymgt";

    //Create Connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Check Connection
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

?>
