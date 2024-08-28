<?php

class Database{

    public static $connection;

    public static function setUpConnection(){
        if(!isset(Database::$connection)){
            Database::$connection = new mysqli("byteforceinventory.mysql.database.azure.com","teki","Hacker@119","inventorymgt","3306");
        }
    }

    public static function iud($q){
        $ssl_ca = '/home/site/wwwroot/ca-cert.pem';
        Database::$connection->ssl_set(null, null, $ssl_ca, null, null);
        Database::$connection->real_connect("byteforceinventory.mysql.database.azure.com", "teki", "Hacker@119", "inventorymgt", 3306, null, MYSQLI_CLIENT_SSL);
        Database::$connection->query($q);
    }

    public static function search($q){
         $ssl_ca = '/home/site/wwwroot/ca-cert.pem';
        Database::$connection->ssl_set(null, null, $ssl_ca, null, null);
        Database::$connection->real_connect("byteforceinventory.mysql.database.azure.com", "teki", "Hacker@119", "inventorymgt", 3306, null, MYSQLI_CLIENT_SSL);
        $resultset = Database::$connection->query($q);
        return $resultset;
    }

    public static function getConnection() {
        Database::setUpConnection();
        return Database::$connection;
    }

}

?>
