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

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * 10;
$prompts = \PrompTopia\Framework\Prompt::getAll($offset);
$totalPrompts = \PrompTopia\Framework\Prompt::countAll();
$totalPages = ceil($totalPrompts / 10);


// Check if a search query is submitted
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $prompts = \PrompTopia\Framework\Prompt::searchPrompts($searchQuery);
} else {
    $prompts = \PrompTopia\Framework\Prompt::getAll();
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
<h1 style="margin:100px;">Dit is de marktplaats</h1>






<div class="prompts">
        <?php foreach($prompts as $prompt): ?>
            <div class="prompt">
                <h2><?php echo htmlspecialchars( $prompt["title"]); ?></h2>
                <h3><?php echo htmlspecialchars( $prompt["prompt"]); ?></h3>
                <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl();?>" alt="">
                <p><?php echo $prompt["price"]; ?></p>
                <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
                <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
                <a class="favourite-btn" style="color: yellow" data-promptid="<?php echo $prompt['id']; ?>">FAVOURITE</a>
                <a class="like-btn" style="color: blue" data-promptid="<?php echo $prompt['id']; ?>">LIKE</a>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Teller om van pagina te veranderen voor volgende prompts te zien -->
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
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
