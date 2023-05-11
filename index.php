<?php
include_once (__DIR__ . "/bootstrap.php");

if(!empty($_POST)){
    //prompt uit de form halen
    $title = $_POST["title"];
    $prompt = $_POST["prompt"];

    $conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");
    $statement = $conn->prepare("insert into prompts (title, prompt) values (:title, :prompt)");
    $statement->bindValue(":title", $title);
    $statement->bindValue(":prompt", $prompt);
    $statement->execute();
    $prompts = \PrompTopia\Framework\Prompt::getAll();
}

$prompts = \PrompTopia\Framework\Prompt::getAll();


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
<h1 style="margin:100px;">Dit is de Homepage</h1>

    <form action="" method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title">
        <label for="prompt">Prompt</label>
        <input type="text" id="prompt" name="prompt">
        <input type="submit" value="Post" class="btn">
    </form>
   
    <div class="prompts">
        <?php foreach($prompts as $prompt): ?>
            <div class="prompt">
                <h2><?php echo $prompt["title"]; ?></h2>
                <p><?php echo $prompt["prompt"]; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>