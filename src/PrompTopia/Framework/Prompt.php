<?php

namespace PrompTopia\Framework;

class Prompt
{
    private $id;
    private $title;
    private $prompt;
    private $img;
    private $price;
    private $type;
    private $tags;
    private $categories;
    private $user_id;

    
    public function getUser_id()
    {
        return $this->user_id;
    }

    public function getId()
    {
        return $this->id;
    }

 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public static function getAll($offset = 0)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select prompts.*, users.username from prompts JOIN users ON prompts.user_id = users.id ORDER BY id DESC LIMIT 10 OFFSET :offset");
        $statement->bindValue(":offset", (int) $offset, \PDO::PARAM_INT); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select count(*) from prompts");
        $statement->execute();
        return $statement->fetchColumn();
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        if(empty($title)) {
            throw new \Exception("Title cannot be empty");
        } else {
            $this->title = $title;
            return $this;
        }

    }

    public function getPrompt()
    {
        return $this->prompt;
    }

    public function setPrompt($prompt)
    {
        if(empty($prompt)) {
            throw new \Exception("Prompt cannot be empty");
        } else {
            $this->prompt = $prompt;
            return $this;
        }

    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img)
    {
        if(empty($img)) {
            throw new \Exception("Image cannot be empty");
        } else {
            $this->img = $img;
            return $this;
        }

    }

public function getPrice()
{
    return $this->price;
}

    public function setPrice($price)
    {
        if(empty($price)) {
            throw new \Exception("Price cannot be empty");
        } else {
            $this->price = $price;
            return $this;
        }

    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

 
    public function getCategories()
    {
        return $this->categories;
    }


    public function setCategories($categories)
    {
        if(empty($categories)) {
            throw new \Exception("Please choose a category");
        } else {
            $this->categories = $categories;
        }

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }


    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into prompts (user_id, title, prompt, img, price, type, tags) values (:userId, :title, :prompt, :img, :price, :type, :tags)");
        $statement->bindValue(":title", $this->getTitle());
        $statement->bindValue(":prompt", $this->getPrompt());
        $statement->bindValue(":img", $this->getImg());
        $statement->bindValue(":price", $this->getPrice());
        $statement->bindValue(":type", $this->getType());
        $statement->bindValue(":tags", $this->getTags());
        $statement->bindValue(":userId", $this->getUserId());
        $result = $statement->execute();
        
        if ($result) {
            // Update user credits (+2)
            $userId = $this->getUserId();
            $statement = $conn->prepare("UPDATE credits SET balance = balance + 2 WHERE user_id = :userId");
            $statement->bindValue(":userId", $userId);
            $statement->execute();
    
            return true;
        } else {
            return false;
        }
    }

    public static function notApproved()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE approved = 0");
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }  

    public static function approvePrompt($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET approved = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public static function categories()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM categories");
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

        public static function searchPrompts($searchQuery)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE title LIKE :searchQuery OR prompt LIKE :searchQuery OR tags LIKE :searchQuery OR type LIKE :searchQuery");
        $statement->bindValue(":searchQuery", '%' . $searchQuery . '%');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getFiltered($filter){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE price LIKE :filter");
        $statement->bindValue(":filter", '%' . $filter . '%');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getPromptByUserId($username, $promptId)
{
    $conn = Db::getInstance();
    $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = (SELECT id FROM users WHERE username = :username) AND id = :promptId");
    $statement->bindValue(":username", $username);
    $statement->bindValue(":promptId", $promptId);
    $statement->execute();
    return $statement->fetchAll(\PDO::FETCH_ASSOC);
}

    
    public static function getAllFromUser($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT p.*, u.username FROM prompts AS p INNER JOIN users AS u ON p.user_id = u.id WHERE u.username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function deletePrompt($promptId)
{
    $conn = Db::getInstance();
    $statement = $conn->prepare("DELETE FROM prompts WHERE id = :id");
    $statement->bindValue(":id", $promptId);
    $statement->execute();
}





}