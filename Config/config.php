<?php
    $servername = "localhost:3306";
    $username = "root";
    $password = "2001kkkK@@";
    $dbname = "InventoryMgt";

    //Create Connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Check Connection
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

?>