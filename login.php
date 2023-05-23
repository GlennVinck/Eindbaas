<?php
include_once (__DIR__ . "/bootstrap.php");


if(!empty($_POST)) {
    try {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $user = \PrompTopia\Framework\User::login($email, $password);
            if($user) {
                exit();
            } else {
                $error = "Username or password is incorrect";
            }
	} catch (\Throwable $th) {
		$error = $th->getMessage();
	}
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PrompTopia</title>
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
			<form class="form-right" action="" method="post">
				<h2 class="form-title">Log In</h2>
				<h3 class="form-subtitle">Enter your credentials to log in to your account</h3>

				<?php if( isset($error) ):?>
					<div class="form__error">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>

				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" id="email" name="email" required>
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" id="password" name="password" required>
				</div>

				<div class="form__field">
					<input type="submit" value="Log In" class="btn btn--primary">	
				</div>
                <a href="forgot_password.php">Forgot Password?</a>
			</form>
		</div>
	</div>
</body>
</html>