<?php
include_once (__DIR__ . "/bootstrap.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

use SendGrid\Mail\Mail;

// DB connection
$conn = new PDO("mysql:host=ID394672_eindbaas.db.webhosting.be;dbname=ID394672_eindbaas", "ID394672_eindbaas", "Eindbaas123");

// check of form gesubmit is en de email is gezet (isset)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $username = $_POST["email"];
    
    // check if the user exists in de DB
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt ->bindParam(':email', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo "<p style='color:red;'>No user with that email address exists. Please try again.</p>";
        exit;
    }
    

    // generate a random token
    $token = bin2hex(random_bytes(32));

    // zet de token en expire date in DB
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
    $stmt->execute([$token, $user["id"]]);

    // send the password reset email met -SendGrid-
    $email = new Mail();
    $email->setFrom("promptopia6@gmail.com", "PrompTopia");
    $email->setSubject("Password Reset for PrompTopia");
    $email->addTo($username);
    $email->addContent(
        "text/html",
        "<html>
            <head>
                <style>
                    /* Add styles for the body of the email */
                    body {
                        font-family: Finlandica,sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #e7e7e7#;
                        background-color: #041e1b;
                    }
    
                    /* Add styles for headings */
                    h1, h2, h3 {
                        font-family: Finlandica,sans-serif;
                        color: #7ac4bb;
                        margin-bottom: 20px;
                    }
    
                    /* Add styles for links */
                    a {
                        color: white;
                        background-color: red;
                        padding: 1em;
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <h1>Password Reset Request</h1>
                <p>Hello $username,</p>
                <p>We received a request to reset the password for your Promptopia account. Your account is important to us, and we want to ensure that your information is secure.</p>
                <p>To reset your password, please click the link below:</p>
                <p><a href='http://localhost:8888/Promptopia/Eindbaas/reset_password.php?token=$token'>Reset Password</a></p>
                <p>If you did not request a password reset, please disregard this email.</p>
                <p>Thank you for being a valued member of our community.</p>
                <p>Best regards,<br>The Promptopia Team</p>
                <p>P.S. If you have any questions or concerns, please don't hesitate to contact us at <a href='mailto:promptopia6@gmail.com'>promptopia6@gmail.com</a> or <a href='tel:+32468210964'>+32 (0)468 21 09 64</a>.</p>
            </body>
        </html>"
    );
    
    

    $sendgrid = new \SendGrid('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');
    try {
        $response = $sendgrid->send($email);
        $success_message = "An email has been sent to your email address with instructions on how to reset your password.";

    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage() ."\n";
    }


} else {
        // echo "No user with that email address exists. Please try again.";
    // }
    // echo "Invalid request. Please try again.";
    // als de FORM niet gesubmit is of de email in nog niet gezet
    // $success_message = "invalid request. Please try again!!";



} ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    <form class="form-right" action="" method="POST">
            <h2 class="form-title">Reset your password</h2>
		    <h3 class="form-subtitle">Enter your email to recover your old password</h3>

        <div class="form__field">
    <label for="Email">Email address</label>
    <input type="text" id="password" name="email" required>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="sucesshaha">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div class="form__field">
    <input type="submit" value="Reset Password" class="btn btn--primary">
        </div>
                
            </div>
        </form>
    </div>
</div>
</div>
</div>

</body>
</html>