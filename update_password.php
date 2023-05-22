<?php
include_once (__DIR__ . "/bootstrap.php");

$conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
    $token = $_POST["token"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // validate the password
    if ($password != $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit;
    }

    if (strlen($password) < 8) {
        echo "Password should be at least 8 characters long. Please try again.";
        exit;
    }

    // hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiration = NULL WHERE reset_token = ?");
    $stmt->execute([$hashed_password, $token]);

    echo "Your password has been updated successfully!";
} else {
    echo "Invalid request. Please try again.";
}?> <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password has been reset!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	        <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> 
	        <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include_once "assets/topnav.php"; ?>
    <h1 style="margin:100px;">Reset Your Password</h1>
    
</body>
</html>