<?php
include_once (__DIR__ . "/bootstrap.php");

if($_SESSION['loggedin'] !== true){
    header('Location: notloggedin.php');
}

$categories = \PrompTopia\Framework\Prompt::categories();

if(!empty($_POST)){
    if(isset($_FILES['img'])){
        try{
            $img = new \PrompTopia\Framework\Img($cloudinary);
            $imgName = $img->upload($_FILES['img']);
            $prompt = new \PrompTopia\Framework\Prompt();
            $prompt->setTitle($_POST["title"]);
            $prompt->setPrompt($_POST["prompt"]);
            $prompt->setImg($imgName);
            $prompt->setPrice($_POST["price"]);
            $prompt->setType($_POST["type"]);
            $prompt->setTags($_POST["tags"]);
            $prompt->setUserId($_SESSION['id']);
            $prompt->save();

            $promptId = $prompt->getId();
            echo $promptId;
            //header("Location: promptdetail.php?id=$promptId");
            //exit();

        }
        catch (Throwable $e) {
            $error = $e->getMessage();
        }
        
    } else {
        $error = "Please upload an image";
    }
   
}


$isAdmin = false;
if (isset($_SESSION['id'])) {
    $isAdmin = \PrompTopia\Framework\User::isAdmin($_SESSION['id']);
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
<h1 style="margin:100px;">Uploadzone</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?> 
        <div class="prompt_field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title">
        </div>
        <div class="prompt_field"> 
            <label for="prompt">Prompt</label>
            <input type="text" id="prompt" name="prompt">
        </div>
        <div class="prompt_field">
            <label for="img">Selecteer een bestand:</label>
            <input type="file" name="img" id="img">
        </div>
        <div class="prompt_field"> 
            <label for="price">Prijs</label>
            <select name="price" id="price">
                <option value="free">Free</option>
                <option value="1credit">1 credit</option>
                <option value="2credits">2 credits</option>
            </select>
        </div>
       <div class="prompt_field">  
            <label for="type">Waar voerde je dit prompt in?</label>
            <input type="text" id="type" name="type">
        </div>
       <div class="prompt_field">
            <label for="tags">Free tags</label>
            <input type="text" id="tags" name="tags">
        </div>
        <!-- <ul class="items">
        <?php foreach($categories as $categorie):?>
            <li>
                <input name="categories[]" type="checkbox" value="<?php echo $categorie["id"]?>" /> 
                <?php echo $categorie["name"]?> 
            </li>
        <?php endforeach;?>
        </ul> -->
        <div class="prompt_field" >
            <input type="submit" value="Post" class="btn">
        </div>
        
    </form>

</body>
</html>