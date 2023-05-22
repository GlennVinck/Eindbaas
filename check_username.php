<?php
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST['username'])) {
    $username = $_POST['username'];

    if (\PrompTopia\Framework\User::usernameExists($username)) {
        echo 'exists';
    } else {
        echo 'available';
    }
}