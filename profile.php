<?php 
include_once (__DIR__ . "/bootstrap.php");

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['deleteUser'])) {
    $user = new \PrompTopia\Framework\User();
    $user->deleteUser();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrompTopia</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include_once "assets/topnav.php"; ?>
<h1 style="margin:100px;">Dit is jouw profiel</h1>
<form action="" method="POST">
<button class="" name="deleteUser" type="submit" formmethod="post" value="deleteUser">Delete your account</button>
</form>
</body>
</html>