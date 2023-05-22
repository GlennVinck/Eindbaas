<?php
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST['email'])) {
    $email = $_POST['email'];

    if (\PrompTopia\Framework\User::emailExists($email)) {
        echo 'exists';
    } else {
        echo 'available';
    }
}
