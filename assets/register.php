<?php
  if(!empty($_POST)){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $options = [
      'cost' => 12,
    ];

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);

    $conn = new mysqli("ID394672_eindbaas.db.webhosting.be", "ID394672_eindbaas", "Eindbaas123", "ID394672_eindbaas");
    $result = $conn->query("insert into users (username, password) values ('".$conn->real_escape_string($username)."', '".$conn->real_escape_string($password)."')");
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrompTopia: Register</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
	<div class="PromptopiaRegister">
		<div class="form form--register">
			<form action="" method="post">
				<h2 form__title>Register</h2>

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
					<input type="submit" value="Sign Up" class="btn btn--primary">	
				</div>
			</form>
		</div>
	</div>
</body>
</html>