<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$notApproved = \PrompTopia\Framework\Prompt::notApproved()

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
    <h1 style="margin:100px;">Dit is de admin page</h1>
        <h2>Prompts to approve</h2>
            <div class="prompts">
            <?php if (!empty($notApproved)) :
                foreach ($notApproved as $prompt) :?>
                <div class="prompt">
                    <h2><?php echo htmlspecialchars($prompt["title"]); ?></h2>
                    <h3><?php echo htmlspecialchars($prompt["prompt"]); ?></h3>
                    <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl(); ?>" alt="">
                    <p><?php echo $prompt["price"]; ?></p>
                    <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
                    <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
                </div>
            <?php endforeach;
            else: ?>
                <h3>There are no prompts to approve</h3>
            <?php endif; ?>
            </div>
</body>
</html>