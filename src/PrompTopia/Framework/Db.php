<?php
namespace PrompTopia\Framework;

abstract class Db
{
    private static $conn;

    private static function getConfig()
    {
        // get the config file
        return parse_ini_file(dirname(dirname(dirname(__DIR__))) . '/config.ini');
    }

    public static function getInstance()
    {
        if(self::$conn === null) {
            $config = self::getConfig();
            $host = $config['host'];
            $database = $config['database'];
            $user = $config['user'];
            $password = $config['password'];
    
            self::$conn = new \PDO("mysql:host=$host;dbname=".$database, $user, $password);
            return self::$conn;
        } else {
            return self::$conn;
        }
    }
}