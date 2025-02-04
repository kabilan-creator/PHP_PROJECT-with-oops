<?php

class Database {
    private static $db = null;

    
    public static function getConnection() {
        if (self::$db === null) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "php_project";

            
            self::$db = new mysqli($servername, $username, $password, $dbname);

            
            if (self::$db->connect_error) {
                die("Connection failed: " . self::$db->connect_error);
            }
        }
        return self::$db;
    }
}


$db = Database::getConnection();


?>