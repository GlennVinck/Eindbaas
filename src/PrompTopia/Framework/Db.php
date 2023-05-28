<?php
namespace PrompTopia\Framework;

abstract class Db
{
    private static $conn;

    private static function getConfig()
    {
        // get the config file
        return parse_ini_file("config/config.ini");
    }

    public static function getInstance()
    {
        if(self::$conn != null) {
            return self::$conn;
        } else {
            $config = self::getConfig();
            $host = $config['host'];
            $database = $config['database'];
            $user = $config['user'];
            $password = $config['password'];
    
            self::$conn = new \PDO("mysql:host=$host;dbname=".$database, $user, $password);
            return self::$conn;
        }
    
    }
}