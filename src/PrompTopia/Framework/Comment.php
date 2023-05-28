<?php

namespace PrompTopia\Framework;

class Comment{
    private $userId;
    private $promptId;
    private $comment;

    
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

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        /*if(empty($comment)) {
            throw new \Exception("You need to enter a comment");
        }*/

        $this->comment = $comment;
        return $this;
    }

    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into comments (user_id, prompt_id, comment) values (:userid, :promptid, :comment)");
        $statement->bindValue(":userid", $this->getUserId());
        $statement->bindValue(":promptid", $this->getPromptId());
        $statement->bindValue(":comment", $this->getComment());
        $result = $statement->execute();

        if ($result) {
            // Update user credits after successful comment
            $userId = $this->getUserId();
            self::updateUserCreditsOnComment($userId, 0.5);
        }
    
        return $result;
    }

    /*public static function removeFavourite($promptId, $userId){
       
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM favourites WHERE prompt_id = :promptid AND user_id = :userid");
        $statement->bindValue(":promptid", $promptId);
        $statement->bindValue(":userid", $userId);
        return $statement->execute();
    }*/

    public static function getComments($promptId){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.prompt_id = :promptid");
        $statement->bindValue(":promptid", $promptId);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function updateUserCreditsOnComment($userId, $credits)
{
    $conn = Db::getInstance();
    $statement = $conn->prepare("UPDATE credits SET balance = balance + :credits WHERE user_id = :userId");
    $statement->bindValue(":credits", $credits);
    $statement->bindValue(":userId", $userId);
    $statement->execute();
}
}