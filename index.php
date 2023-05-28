<?php
include_once (__DIR__ . "/bootstrap.php");

if($_SESSION['loggedin'] !== true){
    header('Location: notloggedin.php');
}

$categories = \PrompTopia\Framework\Prompt::categories();

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * 10;
$prompts = \PrompTopia\Framework\Prompt::getAll($offset);
$totalPrompts = \PrompTopia\Framework\Prompt::countAll();
$totalPages = ceil($totalPrompts / 10);

$isAdmin = false;
if (isset($_SESSION['id'])) {
    $isAdmin = \PrompTopia\Framework\User::isAdmin($_SESSION['id']);
}

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
    <div class="hero-wrapper">
        <h2>Dall-E, Stable Diffusion, Midjourney</h2>
        <h1>Prompt sharing platform for <br> <span>Experience Design</span> students</h1>
        <div>
            <a href="marketplace.php">Search prompts</a>
            <a href="upload.php">Upload a prompt</a>
        </div>
    </div>
   
    <div class="prompts">
        <?php foreach($prompts as $prompt): ?>
            <?php include "assets/promptcard.php"; ?>
        <?php endforeach; ?>
    </div>

    <!-- Teller om van pagina te veranderen voor volgende prompts te zien -->
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <?php if ($i === $page) : ?>
                <span><?php echo $i; ?></span>
            <?php else : ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages) : ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
    

<script>
const favouriteBtns = document.getElementsByClassName('favourite-btn');
Array.from(favouriteBtns).forEach((btn) => {
    btn.addEventListener('click', () => {

        let promptId = btn.dataset.promptid;
        let userId = <?php echo $_SESSION['id']; ?>;

        let formData = new FormData();
        formData.append("promptId", promptId);
        formData.append("userId", userId);

        async function upload(formData) {
            try {
                const response = await fetch("ajax/favouriteprompt.php", {
                method: "POST",
                body: formData,
                });
                const result = await response.json();
                console.log("Success:", result);
            } catch (error) {
                console.error("Error:", error);
            }
            }

        upload(formData);
    });
});

const likeBtns = document.getElementsByClassName('like-btn');
Array.from(likeBtns).forEach((btn) => {
    btn.addEventListener('click', () => {

        let promptId = btn.dataset.promptid;
        let userId = <?php echo $_SESSION['id']; ?>;

        let formData = new FormData();
        formData.append("promptId", promptId);
        formData.append("userId", userId);

        async function upload(formData) {
            try {
                const response = await fetch("ajax/likeprompt.php", {
                method: "POST",
                body: formData,
                });
                const result = await response.json();
                console.log("Success:", result);
            } catch (error) {
                console.error("Error:", error);
            }
            }

        upload(formData);
    });
});


const detailBtns = document.querySelectorAll('.detail');
Array.from(detailBtns).forEach((btn) => {
btn.addEventListener('click', () => {
    let promptId = btn.dataset.promptid;
    window.location.href = `promptdetail.php?id=${promptId}`;
});
});


</script>
</html>