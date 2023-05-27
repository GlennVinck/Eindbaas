<?php

namespace PrompTopia\Framework;

class User
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $prompt_id;
    

    private $profilePicture;

    private $biography;



    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
    
    public function setProfilePicture($imagePath)
    {
        // Update the profile picture in the database
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET profile_picture = :profilePicture WHERE id = :id");
        $statement->bindValue(":profilePicture", $imagePath);
        $statement->bindValue(":id", $_SESSION['id']);
        $result = $statement->execute();

        return $result;
    }

    public function getProfilePicture()
    {
        // Get the profile picture from the database
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT profile_picture FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['id']);

        $statement->execute();
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return $row ? $row['profile_picture'] : null;
    }

    public function getBiography()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT biography FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['id']);

        $statement->execute();
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return $row ? $row['biography'] : null;
    }

    public function setBiography($biography)
    {
    $conn = Db::getInstance();
    $statement = $conn->prepare("UPDATE users SET biography = :biography WHERE id = :id");
    $statement->bindValue(":biography", $biography);
    $statement->bindValue(":id", $_SESSION['id']);
    $result = $statement->execute();

    if (!$result) {
        throw new \Exception("Failed to update biography in the database.");
    }

    $this->biography = $biography;
    return $this;
}

    public function save() {
        // Get the database connection-
        $conn = Db::getInstance();



        // Check if username or email already exists
    $statement = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $statement->bindValue(":username", $this->getUsername());
    $statement->bindValue(":email", $this->getEmail());
    $statement->execute();
    $existingUser = $statement->fetch(\PDO::FETCH_ASSOC);

    if ($existingUser) {
        throw new \Exception("Username or email already exists. Please choose a different one.");
    }

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

    public function changeUsername($newUsername)
    {
        if (empty($newUsername)) {
            throw new \Exception("Username cannot be empty");
        }

        // Update the username in the database
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET username = :newUsername WHERE id = :id");
        $statement->bindValue(":newUsername", $newUsername);
        $statement->bindValue(":id", $_SESSION['id']);
        $result = $statement->execute();

        if ($result) {
            $this->username = $newUsername;
            return true;
        } else {
            return false;
        }
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
                    $_SESSION['username'] = $user["username"];
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



    public static function usernameExists($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //retourneert [TRUE] als er meer dan 0 zijn. Anders [FALSE] === geen users met deze username in database
        return $result['count'] > 0;
    }

    public static function emailExists($email)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //retourneert [TRUE] als er maar dan 0 zijn. Anders [FALSE] === geen users met deze email in database
        return $result['count'] > 0;
    }

    public function checkPassword($oldPassword)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['id']);

        $result = $statement->execute();

        if($result) {
            $user = $statement->fetch(\PDO::FETCH_ASSOC);
            echo $user['password'];
            if($user) {
                $hash = $user['password'];
                if(password_verify($oldPassword, $hash)) {
                    return true;
                } else {
                    throw new \Exception("Old password is not correct.");
                }
            } else {
                return false;
            }
        } else {
            return false;

        }
    }

    public function changePassword($newPassword1, $newPassword2)
    {
        if ($newPassword1 !== $newPassword2) {
            throw new \Exception("The new passwords do not match.");
        }

        $user = new User();
        $user->setPassword($newPassword1);

        // Get the hashed password using the getPassword method
        $newPasswordHash = $user->getPassword();

        // Update the password in the database
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :newPassword WHERE id = :id");
        $statement->bindValue(":newPassword", $newPasswordHash);
        $statement->bindValue(":id", $_SESSION['id']);
        $result = $statement->execute();

        if (!$result) {
            throw new \Exception("Failed to update the password in the database.");
        }
    }


    public function checkResetToken($token) {
            $conn = Db::getInstance(); // Get the database connection
        
            // Code to check if the token exists and is not expired
            $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiration > ?");
            $stmt->execute([$token, date("Y-m-d H:i:s")]);
            $user = $stmt->fetch();
        
            // Return the fetched user or null if not found
            return $user;
        }
    
    public static function getByUsername($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getById($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
}