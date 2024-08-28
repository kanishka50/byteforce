<?php
    $servername = "byteforce.postgres.database.azure.com:3306";
    $username = "ojtpbsqpkk";
    $password = "$3mKiQHq6N2tO6Ij";
    $dbname = "inventoryMgt";

    //Create Connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Check Connection
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

?>
