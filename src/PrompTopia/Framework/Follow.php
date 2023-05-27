<?php

namespace PrompTopia\Framework;


class Follow
{
    private $userId;
    private $followId;


    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getFollowId()
    {
        return $this->followId;
    }

    public function setFollowId($followId)
    {
        $this->followId = $followId;
        return $this;
    }

    public function save()
    {

        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_follows WHERE user_id = :userid AND follow_id = :followedid");
        $statement->bindValue(":userid", $this->getUserId());
        $statement->bindValue(":followedid", $this->getFollowId());
        $statement->execute();
        $result = $statement->fetch();

        if (!$result) {
            $statement = $conn->prepare("INSERT INTO user_follows (user_id, follow_id) VALUES (:userid, :followedid)");
            $statement->bindValue(":userid", $this->getUserId());
            $statement->bindValue(":followedid", $this->getFollowId());
            return $statement->execute();
        } else {
            $statement = $conn->prepare("DELETE FROM user_follows WHERE user_id = :userid AND follow_id = :followedid");
            $statement->bindValue(":userid", $this->getUserId());
            $statement->bindValue(":followedid", $this->getFollowId());
            return $statement->execute();
        }
    }

    public function checkIfFollowing($userId, $followedId)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_follows WHERE user_id = :userid AND follow_id = :followedid");
        $statement->bindValue(":userid", $userId);
        $statement->bindValue(":followedid", $followedId);
        $statement->execute();
        $result = $statement->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}

?>