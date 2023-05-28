<?php 
require_once __DIR__ . "/bootstrap.php";
$config = parse_ini_file( "config/config.ini");


if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Credits
$user = new \PrompTopia\Framework\User();
$credits = $user->getCredits();

//Profile Picture
$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();

// Prompts 
$prompts = \PrompTopia\Framework\Prompt::getAllFromUser($_SESSION['username']);

// Liked Prompts
$likedPrompts = \PrompTopia\Framework\Like::getLikedPromptsByUser($_SESSION['username']);

// Biography
$user = new \PrompTopia\Framework\User();
$biography = $user->getBiography();



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
<a href="edit_profile.php">Edit Profile</a>


<!-- Username -->
<div class="username-profile">
    <h1 style="margin:100px;">Hello <?php echo $_SESSION['username'];?></h1>
</div>
<!-- Username -->

<!-- Credits -->
<div class="credits">
    <?php if (is_array($credits)) : ?>
        <p style="font-size: 20px;" class="credits-display">Credits: <?php echo $credits['balance']; ?></p>
    <?php else : ?>
        <p style="font-size: 20px;" class="credits-display">Credits: N/A</p>
    <?php endif; ?>
</div>
<!-- Credits -->





<!-- Prompts -->
<h1 style="margin: 60px; text-align: center;">Your Prompts</h1>
<div class="prompts">
    <?php if (count($prompts) > 0): ?>
        <?php foreach($prompts as $prompt): ?>
            <div class="prompt-card">
                <?php include "assets/promptcard.php"; ?>
                <form action="remove_prompt.php" method="post" class="remove-prompt-form">
                    <input type="hidden" name="prompt_id" value="<?php echo $prompt['id']; ?>">
                    <button type="button" class="remove-prompt-button" onclick="confirmPromptRemoval(this)">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No prompts found here..</p>
        <a href="index.php">Upload new prompt</a>
    <?php endif; ?>
</div>
<!-- End of Prompts -->


<!-- Liked Prompts -->
<h1 style="margin: 60px; text-align: center;">Liked Prompts</h1>
<div class="prompts">
    <?php if (count($likedPrompts) > 0): ?>
        <?php foreach($likedPrompts as $prompt): ?>
            <div class="prompt-card">
                <?php include "assets/promptcard.php"; ?>
                <form action="remove_prompt.php" method="post" class="remove-prompt-form">
                    <input type="hidden" name="prompt_id" value="<?php echo $prompt['id']; ?>">
                    <button type="button" class="remove-prompt-button" onclick="confirmPromptRemoval(this)">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No liked prompts found here..</p>
    <?php endif; ?>
</div>
<!-- End of Liked Prompts -->

<script>
    function confirmPromptRemoval(button) {
        if (confirm("Are you sure you want to remove your prompt?")) {
            button.parentNode.submit();
        }
    }
</script>

<!-- Profile Picture -->
<div class="form"> 
    <img id="profilePicture-display" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="200">
</div>
<!-- Profile Picture -->


<!-- Biography -->
<h1 style="margin: 60px; text-align: center;">Your bio</h1>
<div class="biography-profile">
    <p id="bio-display"><?php echo $biography; ?></p>
</div>
<!-- Biography -->

</body>
</html>
