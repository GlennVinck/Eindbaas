<?php
include_once (__DIR__ . "/bootstrap.php");
$config = parse_ini_file( "config/config.ini");
$api_key = $config['sendgrid_api_key'];

if (!empty($_POST)) {
	try {

		$username = $_POST["username"]; // Retrieve the username from $_POST
        $email = $_POST["email"]; // Retrieve the email from $_POST


// Check if username already exists
if (\PrompTopia\Framework\User::usernameExists($username)) {
	throw new \Exception("Username already exists. Please choose a different username.");
}

// Check if email already exists
if (\PrompTopia\Framework\User::emailExists($email)) {
	throw new \Exception("Email already exists. Please use a different email address.");
}


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
$sendgrid = new \SendGrid($api_key);
try {
    $response = $sendgrid->send($email);
    $responseData = $response;
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

	<!-- ... Live validation of email and username availability ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	// ensure that the code runs only the page (web page) has finished loading
$(document).ready(function() {
  // event handlers - check username availability (value)
  $('#username').on('input', function() {
    var username = $(this).val();
	// stuur username naar server via AJAX
    $.ajax({
      type: 'POST',
      url: 'check_username.php',
      data: { username: username },
      success: function(response) {
		//add simpele unavailable class if 'exists' 
        if (response === 'exists') {
          $('#username-availability').text('Username already exists. Please choose a different username.').addClass('unavailable');
        } else {
          $('#username-availability').text('').removeClass('unavailable');
        }
      }
    });
  });

  // event handlers - check email availability (value)
  $('#email').on('input', function() {
    var email = $(this).val();
	// stuur email naar server via AJAX
    $.ajax({
      type: 'POST',
      url: 'check_email.php',
      data: { email: email },
      success: function(response) {
		//add simpele unavailable class if 'exists' 
        if (response === 'exists') {
          $('#email-availability').text('Email already exists. Please use a different email address.').addClass('unavailable');
        } else {
          $('#email-availability').text('').removeClass('unavailable');
        }
      }
    });
  });
});
</script>
<style>
  .unavailable {
    color: red;
  }
</style>

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
					<input type="text" id="username" name="username" required>	
					<div id="username-availability" class="availability"></div>
				</div>

				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" id="email" name="email">
					<div id="email-availability" class="availability" required></div>
				</div>

				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" id="password" name="password" required>
				</div>

				<div class="form__field">
					<input type="submit" value="Sign Up" class="btn btn--primary">	
				</div>
			</form>
		</div>
	</div>
</body>
</html>