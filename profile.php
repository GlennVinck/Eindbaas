<?php 
include_once (__DIR__ . "/bootstrap.php");

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// credits
$user = new \PrompTopia\Framework\User();
$credits = $user->getCredits();
//Profile Picture
$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();
// Biography
$user = new \PrompTopia\Framework\User();
$biography = $user->getBiography();


?><!DOCTYPE html>
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

<div class="edit_profile_btn">
    <a href="edit_profile.php">Edit Profile</a>
</div>

<!-- Credits -->
<div class="credits">
    <p style="font-size: 20px;" class="credits-display">Credits: <?php echo $credits['balance']; ?></p>
</div>
<!-- Credits -->

<!-- Username -->
<div class="username-profile">
    <h1 style="margin:100px;">Hello <?php echo $_SESSION['username']; ?>, this is your Profile</h1>
</div>
<!-- Username -->



<!-- Profile Picture -->
    <img id="profilePicture-display" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="200">
<!-- Profile Picture -->

<!-- Biography -->

    <p><?php echo $biography; ?></p>
<!-- Biography -->

</body>
</html>