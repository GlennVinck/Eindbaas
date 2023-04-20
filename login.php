<?php
function canLogin ($email, $password) {
    $conn = new PDO('mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas', "ID394672_eindbaas", "Eindbaas123");

    $statement = $conn->prepare("SELECT * FROM users WHERE username = :email");
    $statement->bindValue(":email", $email);
    $statement->execute();
    $user = $statement->fetch();
    $hash = $user['password'];

    if (password_verify($password, $hash)) {
        return true;
    } else {
        return false;
    }
}

if (!empty($_POST)) {
    $email = $_POST['username'];
    $password = $_POST['password'];

    if (canLogin($email, $password)) {
        session_start();
        $_SESSION['username'] = $email;
        /*header('Location: dashboard.php');*/
        echo "You are logged in";
    } else {
        $error = true;
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
		<div class="form form--register">
			<form action="" method="post">
				<h2 form__title>Log in</h2>

				<?php if( isset($error) ):?>
					<div class="form__error">
						<p>
							Sorry, we can't log you in with that email address and password. Can you try again?
						</p>
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
					<input type="submit" value="Log in" class="btn btn--primary">	
				</div>
			</form>
		</div>
	</div>
</body>
</html>