<?php 
include_once (__DIR__ . "/bootstrap.php");

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}




if (isset($_POST['deleteUser'])) {
    $user = new \PrompTopia\Framework\User();
    $user->deleteUser();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['changeUsername'])) {
    $user = new \PrompTopia\Framework\User();
    $newUsername = $_POST['newUsername'];
    try {
        $user->changeUsername($newUsername);
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}



if (isset($_POST['changeProfilePicture'])) {
    $imageFileType = strtolower(pathinfo($_FILES['newProfilePicture']['name'], PATHINFO_EXTENSION));
    $targetDir = "profile_pictures/";
    $targetFile = $targetDir . uniqid() . '.' . $imageFileType;
    $uploadOk = 1;

    // Check if the uploaded file is an image
    $check = getimagesize($_FILES['newProfilePicture']['tmp_name']);
    if ($check === false) {
        $error = "Invalid image file.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $error = "File already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can adjust the size limit as needed)
    if ($_FILES['newProfilePicture']['size'] > 500000) {
        $error = "File is too large. Please choose a smaller image.";
        $uploadOk = 0;
    }

    // Allow only specific file formats (you can modify the allowed formats as needed)
    $allowedFormats = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowedFormats)) {
        $error = "Only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    // If all checks pass, move the uploaded file to the target directory
    if ($uploadOk) {
        $user = new \PrompTopia\Framework\User();
        if (move_uploaded_file($_FILES['newProfilePicture']['tmp_name'], $targetFile)) {
            $user->setProfilePicture($targetFile);
        } else {
            $error = "Failed to upload the image.";
        }
    }
}
$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();
if (empty($profilePicture)) {
    $profilePicture = "default_profile_picture.jpg"; // Replace with the path to your default profile picture
}


$user = new \PrompTopia\Framework\User();
$biography = $user->getBiography();

if (isset($_POST['saveBiography'])) {
    $user = new \PrompTopia\Framework\User();
    $newBiography = $_POST['biography'];

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
<h1 style="margin:100px;">Dit is jouw profiel</h1>
<h1 style="margin:100px;">Hello <?php echo $_SESSION["username"];?></h1>
<form action="profile.php" method="POST">
    <label for="newUsername">Change Username:</label>
    <input type="text" id="newUsername" name="newUsername" required>
    <button type="submit" name="changeUsername">Change</button>
</form>



<img src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="200">

<form action="" method="POST" enctype="multipart/form-data">
    <label for="newProfilePicture">Change Profile Picture:</label>
    <input type="file" id="newProfilePicture" name="newProfilePicture" accept="image/*" required>
    <button type="submit" name="changeProfilePicture">Upload</button>
</form>



<form action="" method="POST">
    <label for="biography">Biography:</label>
    <textarea id="biography" name="biography"><?php echo $biography; ?></textarea>
    <button type="submit" name="saveBiography">Change</button>
</form>

</body>




<?php if (isset($error)): ?>
    <p>Error: <?php echo $error; ?></p>
<?php endif; ?>


<form action="" method="POST">
<button class="" name="deleteUser" type="submit" formmethod="post" value="deleteUser">Delete your account</button>
</form>
</body>
</html>