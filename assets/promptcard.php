<?php
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
?>

<div class="prompt">
    <div class="detail" data-promptid="<?php echo $prompt['id']; ?>">
        <h2><?php echo htmlspecialchars( $prompt["title"]); ?></h2>
        <h3><?php echo htmlspecialchars( $prompt["prompt"]); ?></h3>
        <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl();?>" alt="">
        <p><?php echo $prompt["price"]; ?></p>
        <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
        <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
    </div>
    <a class="favourite-btn" style="color: yellow" data-promptid="<?php echo $prompt['id']; ?>">FAVOURITE</a>
    <a class="like-btn" style="color: blue" data-promptid="<?php echo $prompt['id']; ?>">LIKE</a>
</div>
