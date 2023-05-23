<?php

namespace PrompTopia\Framework;

class Like{
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
        $statement = $conn->prepare("insert into likes (user_id, prompt_id) values (:userid, :promptid)");
        $statement->bindValue(":userid", $this->getUserId());
        $statement->bindValue(":promptid", $this->getPromptId());
        return $statement->execute();
    }

    public static function removeLike($promptId, $userId){
       
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM likes WHERE prompt_id = :promptid AND user_id = :userid");
        $statement->bindValue(":promptid", $promptId);
        $statement->bindValue(":userid", $userId);
        return $statement->execute();
    }

    public static function getLike($promptId, $userId){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM likes WHERE prompt_id = :promptid AND user_id = :userid");
        $statement->bindValue(":promptid", $promptId);
        $statement->bindValue(":userid", $userId);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}