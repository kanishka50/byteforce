<?php

class Database{

    public static $connection;

    public static function setUpConnection(){
        if(!isset(Database::$connection)){
            Database::$connection = new mysqli("byteforceinventory.mysql.database.azure.com","teki","Hacker@119","inventorymgt","3306");
        }
    }

    public static function iud($q){
        Database::setUpConnection();
        Database::$connection->query($q);
    }

    public static function search($q){
        Database::setUpConnection();
        $resultset = Database::$connection->query($q);
        return $resultset;
    }

    public static function getConnection() {
        Database::setUpConnection();
        return Database::$connection;
    }

}

?>
