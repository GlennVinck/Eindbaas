<?php
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize; // for resizing the image

// Creating Cloudinary instance
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $config['cloud_name'],
        'api_key' => $config['api_key'],
        'api_secret' => $config['api_secret'],
    ],
]);
?>

<div class="prompt-gradient">
<div class="prompt">
    <div class="detail" data-promptid="<?php echo $prompt['id']; ?>">
        <div class="prompt-header">
            <div>
                <a class="favourite-btn" onclick="event.stopPropagation()" style="color: yellow" data-promptid="<?php echo $prompt['id']; ?>"></a>
            </div>
            <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl(); ?>" alt="">
        </div>
        <div class="prompt-details">
            <h2><?php echo htmlspecialchars($prompt["title"]); ?></h2>
            <p><?php echo $prompt["price"]; ?> cr</p>
        </div>
        <p class="tags"><?php echo htmlspecialchars($prompt["type"]); ?></p>
    </div>
    <input type="submit" name="accept_<?php echo $prompt['id']; ?>" value="accept">
                        <input type="submit" name="reject_<?php echo $prompt['id']; ?>" value="reject">
</div>
</div>
