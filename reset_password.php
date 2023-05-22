<?php
include_once (__DIR__ . "/bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];
    // Check if the token exists and is not expired
    $user = new \PrompTopia\Framework\User();
    $checkResetToken = $user->checkResetToken($token);


// Token is valid, show the reset password form
    if ($user) {
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
        
    <div class="PromptopiaRegister">
		<div class="form">
			<div class="form-left"></div>
        <form class="form-right" method="post" action="update_password.php">
        <h2 class="form-title">Reset your password</h2>
		<h3 class="form-subtitle">Enter your new password</h3>

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="form__field">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            </div>
            
            <div class="form__field">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="password" required>
            </div>

            <div class="form__field">
            <input type="submit" value="Reset Password" class="btn btn--primary">
            </div>

        </form>
        </div>
	</div>
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
