<?php
namespace PrompTopia\Framework;

abstract class Db
{
    private static $conn;

    public static function getInstance(){
        if(self::$conn === null){
            self::$conn = new \PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");
        }
        return self::$conn;
    }
}