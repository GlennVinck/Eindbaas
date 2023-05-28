<?php
include_once (__DIR__ . "/bootstrap.php");

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

// Create a new instance of Cloudinary
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $config['cloud_name'],
        'api_key' => $config['api_key'],
        'api_secret' => $config['api_secret'],
    ],
]);

$notApproved = \PrompTopia\Framework\Prompt::notApproved();

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'accept_') !== false) {
            $id = substr($key, strlen('accept_'));
            
            $prompt = new \PrompTopia\Framework\Prompt();
            $prompt->approvePrompt($id);
            header("Location: admin.php");
            exit();

            echo "Accepted prompt with ID: " . $id;
        } elseif (strpos($key, 'reject_') !== false) {
            $id = substr($key, strlen('reject_'));
            
            //write rejected logic (reason and notification)
            echo "Rejected prompt with ID: " . $id;
        }
    }
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
    <h1 style="margin:100px;">Dit is de admin page</h1>
    <h2>Prompts to approve</h2>
    <form method="POST">
        <div class="prompts">
            <?php if (!empty($notApproved)) :
                foreach ($notApproved as $prompt) :?>
                    <div class="prompt">
                        <h2><?php echo htmlspecialchars($prompt["id"]); ?></h2>
                        <h2><?php echo htmlspecialchars($prompt["title"]); ?></h2>
                        <h3><?php echo htmlspecialchars($prompt["prompt"]); ?></h3>
                        <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl(); ?>" alt="">
                        <p><?php echo $prompt["price"]; ?></p>
                        <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
                        <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
                        <input type="submit" name="accept_<?php echo $prompt['id']; ?>" value="accept">
                        <input type="submit" name="reject_<?php echo $prompt['id']; ?>" value="reject">
                    </div>
                <?php endforeach;
            else: ?>
                <h3>There are no prompts to approve</h3>
            <?php endif; ?>
        </div>
    </form>
</body>
</html>
