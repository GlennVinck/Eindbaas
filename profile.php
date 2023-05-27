<?php 
require_once __DIR__ . "/bootstrap.php";


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
    $imageFileType = strtolower(pathinfo($_FILES['newProfilePicture']['name'], PATHINFO_EXTENSION));
    $targetDir = "profile_pictures/";
    $targetFile = $targetDir . uniqid() . '.' . $imageFileType;
    $uploadOk = 1;

    $check = getimagesize($_FILES['newProfilePicture']['tmp_name']);
    if ($check === false) {
        $error = "Invalid image file.";
        $uploadOk = 0;
    }

    if (file_exists($targetFile)) {
        $error = "File already exists.";
        $uploadOk = 0;
    }

    if ($_FILES['newProfilePicture']['size'] > 500000) {
        $error = "File is too large. Please choose a smaller image.";
        $uploadOk = 0;
    }

    $allowedFormats = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowedFormats)) {
        $error = "Only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk) {
        $user = new \PrompTopia\Framework\User();
        if (move_uploaded_file($_FILES['newProfilePicture']['tmp_name'], $targetFile)) {
            $user->setProfilePicture($targetFile);
            $success = "Profile picture successfully changed.";
        } else {
            $error = "Failed to upload the image.";
        }
    }
}

$user = new \PrompTopia\Framework\User();
$profilePicture = $user->getProfilePicture();

if (empty($profilePicture)) {
    $profilePicture = "default_profile_picture.jpg";
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
    } catch (\Exception $e) {
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
        <h1 style="margin:100px;">Hello <?php echo $_SESSION['username'];?></h1>
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

    <!-- Error and Success Messages -->
    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: 800;">Error: <?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: lime; font-weight: 800;">Success: <?php echo $success; ?></p>
    <?php endif; ?>
    <!-- Error and Success Messages -->

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
        <form class="form-right" action="" method="POST">
            <label for="oldPassword">Old Password:</label>
            <input type="password" id="oldPassword" name="oldPassword" required>
            <label for="newPassword1">New Password:</label>
            <input type="password" id="newPassword1" name="newPassword1" required>
            <label for="newPassword2">Confirm New Password:</label>
            <input type="password" id="newPassword2" name="newPassword2" required>
            <button type="submit" name="changePassword">Change</button>
        </form>
    </div>
    <!-- Password -->

    <!-- Delete Account -->
    <div class="form"> 
        <form class="form-right" action="" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <button type="submit" name="deleteUser" style="background-color: red;">Delete Account</button>
        </form>
    </div>
    <!-- Delete Account -->

    <script src="js/main.js"></script>
</body>
</html>
