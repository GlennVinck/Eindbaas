<?php
include_once (__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
	try {
		$user = new \PrompTopia\Framework\User();
		$user->setUsername($_POST["username"]);
		$user->setEmail($_POST["email"]);
		$user->setPassword($_POST["password"]);
	
		$user->save();

		$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("promptopia6@gmail.com", "PrompTopia");
$email->setSubject("Welcome to PrompTopia");
$email->addTo($_POST["email"]);
$email->addContent(
    "text/html",
    "Hi there,<br><br>Thank you for registering on Promptopia! We're excited to have you on board.<br><br>Best,<br>PrompTopia");
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}

		header('Location: login.php');
	} catch (\Throwable $th) {
		$error = $th->getMessage();
	}
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | PrompTopia</title>
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
				<h2 class="form-title">Create Your Account</h2>
				<h3 class="form-subtitle">Enter your credentials to create your account</h2>


				<?php if( isset($error) ):?>
					<div class="form__error">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>

				<div class="form__field">
					<label for="Username">Username</label>
					<input type="text" id="username" name="username">	

				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" id="email" name="email">
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" id="password" name="password">
				</div>

				<div class="form__field">
					<input type="submit" value="Sign Up" class="btn btn--primary">	
				</div>
			</form>
		</div>
	</div>
</body>
</html>