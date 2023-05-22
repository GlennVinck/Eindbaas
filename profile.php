<?php 
include_once (__DIR__ . "/bootstrap.php");

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}



// Delete account
if (isset($_POST['deleteUser'])) {
    $user = new \PrompTopia\Framework\User();
    $user->deleteUser();
    session_destroy();
    header("Location: login.php");
    exit();
}




// Change Username
if (isset($_POST['changeUsername'])) {
    $user = new \PrompTopia\Framework\User();

    $newUsername = $_POST['newUsername'];
    $username = $_SESSION["username"];

    // Check if the new username already exists
    if ($newUsername != $username && \PrompTopia\Framework\User::usernameExists($newUsername)) {
        $error = "Username is already taken. Please choose a different username.";
    } else {
        try {
            $user->changeUsername($newUsername);
            $success = "Username changed successfully.";
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

    }
}




// Change password
if (isset($_POST['changePassword'])) {
    $user = new \PrompTopia\Framework\User();

    $oldPassword = $_POST['oldPassword'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    try {
        $user->checkPassword($oldPassword);
        $user->changePassword($newPassword1, $newPassword2);
        $success = "Password changed successfully.";
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}




// Profile picture
if (isset($_POST['changeProfilePicture'])) {

    // haal type bestand op 
    //strtolower -> string to lowercase
    // pathinfo() zorgt voor deze info (bestandsnaam & type )
    $imageFileType = strtolower(pathinfo($_FILES['newProfilePicture']['name'], PATHINFO_EXTENSION));
    // waar de files worden opgeslagen
    $targetDir = "profile_pictures/";
    // creeër unieke code om duplicates tegen te gaan
    $targetFile = $targetDir . uniqid() . '.' . $imageFileType;


    // toegestaan? 1 = ja / 0 = nee (boolean)
    $uploadOk = 1;

    // check of image een geldige afbeelding is getimagesize()
    $check = getimagesize($_FILES['newProfilePicture']['tmp_name']);
    if ($check === false) {
        $error = "Invalid image file.";
        $uploadOk = 0;
    }

    // check of file al bestaat
    if (file_exists($targetFile)) {
        $error = "File already exists.";
        $uploadOk = 0;
    }

    // check of file voldoet aan de size (in KB)
    if ($_FILES['newProfilePicture']['size'] > 500000) {
        $error = "File is too large. Please choose a smaller image.";
        $uploadOk = 0;
    }

    // accepteer alleen bepaalde bestandtypes
    $allowedFormats = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowedFormats)) {
        $error = "Only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    // als alles gecheckt is wordt de file getransporteerd naar de folder (profile_pictures)
    if ($uploadOk) {
        $user = new \PrompTopia\Framework\User();
        if (move_uploaded_file($_FILES['newProfilePicture']['tmp_name'], $targetFile)) {
            $user->setProfilePicture($targetFile);
            $success = "Profile picture succesfully changed.";
        } else {
            $error = "Failed to upload the image.";
        }
    }
}

$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();

// als de er geen profile picture is ingesteld === default profile picture
if (empty($profilePicture)) {
    $profilePicture = "default_profile_picture.jpg";
}






// Biography
$user = new \PrompTopia\Framework\User();
$biography = $user->getBiography();

// als de post van de bio is gezet
if (isset($_POST['saveBiography'])) {
    $user = new \PrompTopia\Framework\User();
    $newBiography = $_POST['biography'];

// maak nieuwe bio aan en refresh page
    try {
        $user->setBiography($newBiography);
        header("Location: profile.php");
        exit();
    }
    catch (\Exception $e) {
        $error = $e->getMessage();
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
<!-- Username -->
<div class="username-profile">
    <h1 style="margin:100px;">Hello <?php echo $_SESSION["username"];?></h1>
<form action="profile.php" method="POST">
    <label for="newUsername">Change Username:</label>
    <input type="text" id="newUsername" name="newUsername" required>
    <button type="submit" name="changeUsername">Change</button>
</form>
</div>
<!-- Username -->



<!-- Profile Picture -->
<div class="form"> 
    <img id="profilePicture-display" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="200">
<form class="profilePicture-profile" action="" method="POST" enctype="multipart/form-data">
    <label for="newProfilePicture">Change Profile Picture:</label>
    <input id="profilePicture-upload" type="file" name="newProfilePicture" accept="image/*" required>
    <button type="submit" name="changeProfilePicture">Upload</button>
</form>
</div>
<!-- Profile Picture -->


<!-- ⛔️ error and succes messages ⛔️ -->
<?php if (isset($error)): ?>
    <p style="color: red; font-weight: 800;">Error: <?php echo $error; ?></p>
<?php endif; ?>
<?php if (isset($success)): ?>
    <p style="color: lime; font-weight: 800;">Boom! <?php echo $success; ?></p>
<?php endif; ?>
<!-- ⛔️ error and succes messages ⛔️ -->

<!-- Biography -->
<h1 style="margin: 60px">Change your bio</h1>
<div class="form"> 
<div class="biography-profile">
<form class="form-right" action="" method="POST">
    <label for="biography">Biography:</label>
    <p id="bio-display"><?php echo $biography; ?></p>
    <textarea id="bio-textarea" name="biography"><?php echo $biography; ?></textarea>
    <button type="submit" name="saveBiography">Change</button>
</form>
</div>
</div>
<!-- Biography -->

<!-- Password -->
<h1 style="margin: 60px">Change your password</h1>
<div class="form"> 
<div class="changePassword-profile">
<form class="form-right" action="" method="POST">
    <label for="oldPassword">Old Password:</label>
    <input type="password" name="oldPassword" id="oldPassword" required>
  
    <label for="newPassword1">New Password:</label>
    <input type="password" name="newPassword1" id="newPassword1" required>
  
    <label for="newPassword2">Confirm New Password:</label>
    <input type="password" name="newPassword2" id="newPassword2" required>
  
    <button type="submit" name="changePassword">Change password</button>
</form>
</div>
</div>
<!-- Password -->


<!-- Delete Account -->
<div class="deleteAccount-profile">
<form class="form-right" action="" method="POST">
    <button class="" name="deleteUser" type="submit" formmethod="post" value="deleteUser">Delete your account</button>
</form>
</div>
<!-- Delete Account -->

</body>
</html>