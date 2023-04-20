<?php
if (!empty($_POST)) {
    $email = $_POST["username"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = true;
    }

    $options = [
        'cost' => 12,
    ];
    $password = password_hash($password, PASSWORD_DEFAULT, $options);

    try {
        $conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");
        
		
		if (!isset($error)) {
            $query = $conn->prepare("insert into users (username, password) values (:email, :password)");
            $query->bindValue(":email", $email);
            $query->bindValue(":password", $password);
            $query->execute();

            header("Location: login.php");
        }  
	} catch (Throwable $error) {
        echo $error->getMessage();
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
						<p>Please enter both email and password</p>
					</div>
				<?php endif; ?>

				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" id="username" name="username">
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