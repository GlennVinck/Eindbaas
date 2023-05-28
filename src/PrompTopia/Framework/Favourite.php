<?php

namespace PrompTopia\Framework;

class Favourite{
    private $userId;
    private $promptId;

    
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
        return $this;
    }

    public function getPromptId()
    {
        return $this->promptId;
    }

    public function setPromptId($promptId){
        $this->promptId = $promptId;
        return $this;
    }

    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into favourites (user_id, prompt_id) values (:userid, :promptid)");
        $statement->bindValue(":userid", $this->getUserId());
        $statement->bindValue(":promptid", $this->getPromptId());
        return $statement->execute();
    }

    public static function removeFavourite($promptId, $userId){
       
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM favourites WHERE prompt_id = :promptid AND user_id = :userid");
        $statement->bindValue(":promptid", $promptId);
        $statement->bindValue(":userid", $userId);
        return $statement->execute();
    }

    public static function getFavourites($promptId, $userId){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM favourites WHERE prompt_id = :promptid AND user_id = :userid");
        $statement->bindValue(":promptid", $promptId);
        $statement->bindValue(":userid", $userId);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getFavouritesByUser($userId){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM favourites WHERE user_id = :userid");
        $statement->bindValue(":userid", $userId);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}