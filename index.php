<?php
include_once (__DIR__ . "/bootstrap.php");
$config = parse_ini_file( "config/config.ini");

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize; //voor het resizen van de afbeelding

//aanmaken van cloudinary
$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name'=> $config['cloud_name'],
            'api_key'=> $config['api_key'],
            'api_secret'=> $config['api_secret'],
        ],
    ]
);

$cloudinary->uploadApi()->upload(
    'https://upload.wikimedia.org/wikipedia/commons/a/ae/Olympic_flag.jpg',
    ['public_id' => 'olympic_flag']
);



if(!empty($_POST)){
    $prompt = new \PrompTopia\Framework\Prompt();
    $prompt->setTitle($_POST["title"]);
    $prompt->setPrompt($_POST["prompt"]);
    $prompt->setImg($_POST["img"]);
    $prompt->setPrice($_POST["price"]);
    $prompt->setType($_POST["type"]);
    $prompt->setTags($_POST["tags"]);
    $prompt->save();
    $prompt -> getAll();
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
        <label for="img">Selecteer een bestand:</label>
        <input type="file" name="img" id="img">
        <label for="price">Prijs</label>
        <input type="valuta" id="price" name="price">
        <label for="type">Waar voerde je dit prompt in?</label>
        <input type="text" id="type" name="type">
        <label for="tags">Free tags</label>
        <input type="text" id="tags" name="tags">
        <input type="submit" value="Post" class="btn">
    </form>
   
    <div class="prompts">
        <?php foreach($prompts as $prompt): ?>
            <div class="prompt">
                <h2><?php echo $prompt["title"]; ?></h2>
                <h3><?php echo $prompt["prompt"]; ?></h3>
                <img src="<?php echo $cloudinary->image('olympic_flag')->resize(Resize::fill(100, 150))->toUrl();?>" alt="">
                <p><?php echo $prompt["price"]; ?></p>
                <p><?php echo $prompt["type"]; ?></p>
                <p><?php echo $prompt["tags"]; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>