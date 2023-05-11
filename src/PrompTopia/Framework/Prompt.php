<?php

namespace PrompTopia\Framework;

class Prompt
{
    private $title;
    private $prompt;
    private $img;
    private $price;
    private $type;
    private $tags;

    public static function getAll()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from prompts ORDER BY id DESC");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
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


    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into prompts (title, prompt, img, price, type, tags) values (:title, :prompt, :img, :price, :type, :tags)");
        $statement->bindValue(":title", $this->getTitle());
        $statement->bindValue(":prompt", $this->getPrompt());
        $statement->bindValue(":img", $this->getImg());
        $statement->bindValue(":price", $this->getPrice());
        $statement->bindValue(":type", $this->getType());
        $statement->bindValue(":tags", $this->getTags());
        $result = $statement->execute();
        return $result;
    }
}