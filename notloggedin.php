<?php

include_once(__DIR__ . "/bootstrap.php");
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

//als de gerbruiker toch is ingelogd bv met bladwijzer verwijst hij door naar index.php
if (isset($_SESSION["loggedin"])) {
    header('location: index.php');
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * 10;
$prompts = \PrompTopia\Framework\Prompt::getAll($offset);
$totalPrompts = \PrompTopia\Framework\Prompt::countAll();
$totalPages = ceil($totalPrompts / 10);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="css/style.css">
    <title>Notloggedin Promptopia</title>
</head>
<body>
<?php include_once "assets/topnav.php"; ?>
    <div class="hero-wrapper">
        <h2>Dall-E, Stable Diffusion, Midjourney</h2>
        <h1>Prompt sharing platform for <br> <span>Experience Design</span> students</h1>
        <div>
            <a href="marketplace.php">Search prompts</a>
            <a href="login.php">Log In</a>
        </div>
        <p class="login-disclaimer">*login to see full prompt details.</p>

    </div>
<div class="prompts">
        <?php foreach($prompts as $prompt): ?>
            <div class="prompt">
                <h2><?php echo htmlspecialchars( $prompt["title"]); ?></h2> <!--htmlspecialchars om de tekst te beveiligen-->
                <h3><?php echo htmlspecialchars((substr($prompt["prompt"], 0, 20)) . '...');?></h3> <!--beperken van de tekst-->
                <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl();?>" alt="">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Teller om van pagina te veranderen voor volgende prompts te zien -->
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo  $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <?php if ($i === $page) : ?>
                <span><?php echo $i; ?></span>
            <?php else : ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages) : ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>

</body>
</html>