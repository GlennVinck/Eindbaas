<?php
$conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Check if the token exists and is not expired
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiration > ?");
    $stmt->execute([$token, date("Y-m-d H:i:s")]);
    $user = $stmt->fetch();

    if ($user) {
        // Token is valid, show the reset password form
        ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Your Password</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
	        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	        <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> 
	        <link rel="stylesheet" href="css/style.css">
        </head>
        <body>
            
        <?php include_once "assets/topnav.php"; ?>
    <h1 style="margin:100px;">Reset Your Password</h1>
        
        <form method="post" action="update_password.php">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            <br><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <br><br>
            <input type="submit" value="Reset Password">
        </form>
        <?php
    } else {
        // Token is invalid or expired, show an error message
        echo "Invalid or expired token. Please try again.";
    }
} else {
    // Token is not present, show an error message
    echo "Invalid request. Please try again.";
}?>
</body>
</html>
