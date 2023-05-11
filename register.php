<?php
include_once (__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
	$test = $_POST["username"];
    $password = $_POST["password"];
    $errors = array();

	// Check if email and/or password are not empty
    if (empty($test) || empty($password)) {
        $errors[] = "Email and password are required.";
    }

    // Validate the email address
    if (!filter_var($test, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Check if the password has at least one capital letter
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one capital letter.";
    }

    $options = [
        'cost' => 12,
    ];
    $password = password_hash($password, PASSWORD_DEFAULT, $options);

    try {
        $conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");
        
		
		if (empty($errors)) {
            $query = $conn->prepare("insert into users (username, password) values (:email, :password)");
            $query->bindValue(":email", $test);
            $query->bindValue(":password", $password);
            $query->execute();

            header("Location: login.php");
        } 
	} catch (Throwable $error) {
        echo $error->getMessage();
    }

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("promptopia6@gmail.com", "PrompTopia");
$email->setSubject("Welcome to PrompTopia");
$email->addTo($_POST["username"]);
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

				<?php if (!empty($errors)): ?>
   					<div class="form__error">
        				<?php foreach ($errors as $error): ?>
            				<p><?php echo $error; ?></p>
						<?php endforeach; ?>
    				</div>
				<?php endif; ?>


				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" name="username">
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