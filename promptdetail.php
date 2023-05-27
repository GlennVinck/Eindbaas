<?php 
include_once (__DIR__ . "/bootstrap.php");
$config = parse_ini_file( "config/config.ini");

// Check if the ID is present in the URL
if (isset($_GET['id'])) {
    $promptId = $_GET['id'];
    echo $promptId;
    // Use the $promptId to fetch the details of the prompt from your data source
    // ...
    // Your code to fetch the prompt details based on the ID
    // ...

    // Example usage:
    // echo "Prompt ID: " . $promptId;
    // Display the details of the prompt on the detail page
    // ...
    // Your code to display the prompt details
    // ...
} else {
    // Handle the case when the ID is not present in the URL
    echo "Prompt ID is missing from the URL.";
};


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrompTopia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include_once "assets/topnav.php"; ?>
<h1 style="margin:100px;">Dit is de marktplaats</h1>




</body>
</html>
