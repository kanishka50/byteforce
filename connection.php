<?php
class Database {
    public static $connection;

    public static function setUpConnection() {
        if (!isset(Database::$connection)) {
            // Define the SSL CA parameter
            $sslCA = 'ca-cert.pem';

            // Initialize the connection
            Database::$connection = new mysqli(
                "byteforceinventory.mysql.database.azure.com", 
                "teki", 
                "Hacker@119", 
                "inventorymgt", 
                "3306"
            );

            // Check for connection errors
            if (Database::$connection->connect_error) {
                die("Connection failed: " . Database::$connection->connect_error);
            }

            // Set the SSL CA option
            Database::$connection->ssl_set(NULL, NULL, $sslCA, NULL, NULL);

            // Enable SSL verification of the server certificate
            if (!Database::$connection->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true)) {
                die("Failed to set SSL verification mode");
            }

            // Reconnect with SSL enforced
            if (!Database::$connection->real_connect(
                "byteforceinventory.mysql.database.azure.com", 
                "teki", 
                "Hacker@119", 
                "inventorymgt", 
                "3306", 
                NULL, 
                MYSQLI_CLIENT_SSL
            )) {
                die("SSL connection failed: " . mysqli_connect_error());
            }
        }
    }

    public static function iud($q) {
        Database::setUpConnection();
        Database::$connection->query($q);
    }

    public static function search($q) {
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
