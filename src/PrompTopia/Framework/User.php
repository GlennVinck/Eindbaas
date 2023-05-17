<?php

namespace PrompTopia\Framework;

class User
{
    private $username;
    private $email;
    private $password;


    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if(empty($username)) {
            throw new \Exception("Username cannot be empty");
        }

        $this->username = $username;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {

        if(empty($email)) {
            throw new \Exception("Email cannot be empty");
        }

        // Validate the email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email address.");
        }

        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {

        if(empty($password)) {
            throw new \Exception("Password cannot be empty");
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new \Exception("Password must contain at least one capital letter.");
        }

        $options = [
            'cost' => 12,
        ];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
        return $this;
    }

    public function save() {
        // Get the database connection-
        $conn = Db::getInstance();

        // Prepare the query
        $statement = $conn->prepare("insert into users (username, email, password) values (:username, :email, :password)");

        // Bind the parameters
        $username = $this->getUsername();
        $email = $this->getEmail();
        $password = $this->getPassword();

        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);

        // Execute the query
        $result = $statement->execute();

        return $result;
    }

    public static function login($email, $password)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        
        $result = $statement->execute();

        if($result) {
            $user = $statement->fetch(\PDO::FETCH_ASSOC);
            if($user) {
                $hash = $user['password'];
                if(password_verify($password, $hash)) {

                    session_start();

                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $email;
                    $_SESSION["id"] = $user["id"];

                    header("Location: index.php");
                    
                    exit();
                } else {
                    return false;

                }
            } else {
                return false;

            }
        } else {
            return false;

        }
    }
    
    
    public static function isAdmin()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT admin FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['id']);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);
    
        if ($user && $user['admin'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteUser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['id']);
        $statement->execute();
    }
}