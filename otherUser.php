<?php
require_once __DIR__ . "/bootstrap.php";

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize; //voor het resizen van de afbeelding

// aanmaken van cloudinary
$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name' => $config['cloud_name'],
            'api_key' => $config['api_key'],
            'api_secret' => $config['api_secret'],
        ],
    ]
);

// Check if the username is set
if (!isset($_GET['username'])) {
    header('location: index.php');
    exit();
}

$userInfo = \PrompTopia\Framework\User::getByUsername($_GET['username']);

$user = \PrompTopia\Framework\User::getById($_SESSION['id']);
if ($_GET['username'] == $user['username']) {
    header('location: profile.php');
}


$prompts = \PrompTopia\Framework\Prompt::getAllFromUser($_GET['username']);

if (empty($profilePicture)) {
    $profilePicture = "default_profile_picture.jpg";
}

$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();
$biography = $user->getBiography();


if (!empty($_POST)) {
    $follow = new \PrompTopia\Framework\Follow();
    $follow->setUserId($_SESSION['id']);
    $followedId = \PrompTopia\Framework\User::getByUsername($_GET['username']);
    $follow->setFollowId($followedId['id']);
    $follow->save();
}

$follow = new \PrompTopia\Framework\Follow();
$followedId = \PrompTopia\Framework\User::getByUsername($_GET['username']);
if ($follow->checkIfFollowing($_SESSION['id'], $followedId['id']) == true) {
    $followed = true;
} else {
    $followed = false;
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

    <!-- Username -->
    <div class="username-profile">
        <h1 style="margin:100px;"><?php echo htmlspecialchars($userInfo["username"]);?></h1>
    </div>
    <!-- Username -->

    <!-- Profile Picture -->
    <div class="form">
        <img id="profilePicture-display" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="200">
    </div>
    <!-- Profile Picture -->

    <!-- follow button -->
    <?php if ($followed == true) : ?>
                <form action="" method="post">
                    <button type="submit" name="follow" class="follow-button">Unfollow</button>
                </form>
            <?php else : ?>
                <form action="" method="post">
                    <button type="submit" name="follow" class="follow-button">Follow</button>
                </form>
            <?php endif; ?>
    <!-- follow button -->

    <!-- Error and Success Messages -->
    <?php if (isset($error)) : ?>
        <p style="color: red; font-weight: 800;">Error: <?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)) : ?>
        <p style="color: lime; font-weight: 800;">Success: <?php echo $success; ?></p>
    <?php endif; ?>
    <!-- Error and Success Messages -->

    <!-- Show prompts from the user-->
    <div class="prompts">
        <?php foreach ($prompts as $prompt) : ?>
            <?php include "assets/promptcard.php"; ?>
        <?php endforeach; ?>
    </div>

    <script src="js/main.js"></script>
</body>

</html>
