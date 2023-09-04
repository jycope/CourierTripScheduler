<?php

namespace App;

class MySQLConnection {
    public static function connect ()
    {
        $conn = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']); 
        $conn->set_charset("utf8");
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn; 
    }
}