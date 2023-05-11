<?php

namespace PrompTopia\Framework;

class Prompt
{
    private $title;
    private $prompt;

    public static function getAll(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from prompts ORDER BY id DESC");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }



}

