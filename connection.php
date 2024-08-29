<?php

class Database {
    
    private static $connection;

    // Database configuration
    private static $host = 'byteforceinventory.mysql.database.azure.com';
    private static $port = 3306;
    private static $username = 'teki';
    private static $password = 'Hacker@119';
    private static $dbname = 'inventorymgt';
    private static $ssl_ca = 'ca-cert.pem'; // Path to SSL certificate

    public static function setUpConnection() {
        if (!isset(Database::$connection)) {
            Database::$connection = new mysqli();
            
            // Set up SSL
            if (!Database::$connection->ssl_set(null, null, self::$ssl_ca, null, null)) {
                echo json_encode(['status' => 'error', 'message' => 'SSL setup failed']);
                exit();
            }

            // Establish connection with SSL
            if (!Database::$connection->real_connect(self::$host, self::$username, self::$password, self::$dbname, self::$port, null, MYSQLI_CLIENT_SSL)) {
                echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
                exit();
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

    public static function closeConnection() {
        if (isset(Database::$connection)) {
            Database::$connection->close();
            Database::$connection = null;
        }
    }
}

// Example usage:
// Database::iud("INSERT INTO table_name (column1, column2) VALUES ('value1', 'value2')");
// $result = Database::search("SELECT * FROM table_name");
// while ($row = $result->fetch_assoc()) {
//     echo $row['column_name'];
// }
// Database::closeConnection();

?>
