<?php 

$conn = new mysqli("ID394672_eindbaas.db.webhosting.be", "ID394672_eindbaas", "Eindbaas123", "ID394672_eindbaas");
$result = $conn->query("SELECT * FROM users");

var_dump($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrompTopia</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="topnav">
		<a href="#">Logo</a>
		<form>
			<input type="text" placeholder="Search...">
			<button type="submit">Search</button>
		</form>
        <a href="#">Marketplace</a>
		<a href="#">Login</a>
		<a href="#">Contact</a>
    </div>
</body>
</html>