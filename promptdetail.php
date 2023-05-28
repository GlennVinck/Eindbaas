<?php
include_once (__DIR__ . "/bootstrap.php");

use Cloudinary\Transformation\Resize; //voor het resizen van de afbeelding

// Check if the ID is present in the URL
if (isset($_GET['id'])) {
    $promptId = $_GET['id'];
    $prompt = \PrompTopia\Framework\Prompt::getPromptDetails($promptId);
    $comments = \PrompTopia\Framework\Comment::getComments($promptId);
} else {
    // Handle the case when the ID is not present in the URL
    echo "Prompt ID is missing from the URL.";
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
<h1 style="margin:100px;">Promptdetail</h1>

<div class="prompt_wrap">
    <div class="prompt_details" data-promptid="<?php echo $prompt['id']; ?>">
        <h4><a href="otherUser.php?username=<?php echo htmlspecialchars($prompt["username"]);?>"><?php echo htmlspecialchars($prompt["username"]);?></a></h4> 
        <h2><?php echo htmlspecialchars( $prompt["title"]); ?></h2>
        <h3><?php echo htmlspecialchars( $prompt["prompt"]); ?></h3>
        <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl();?>" alt="">
        <p><?php echo $prompt["price"]; ?></p>
        <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
        <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
    </div>
    <a class="favourite-btn" style="color: yellow" data-promptid="<?php echo $prompt['id']; ?>">FAVOURITE</a>
    <a class="like-btn" style="color: blue" data-promptid="<?php echo $prompt['id']; ?>">LIKE</a>
</div>
<div class="comments-wrap">
    <div class="comments-form">
        <input type="text" name="comment" id="comment" placeholder="Write a comment">
        <a href="" class="comment-btn" id="btnAddComment">Add comment</a>
    </div>
    <div class="comments-list">
        <ul id="comments">
            <?php foreach ($comments as $comment): ?>
                <li>
                    <h4><?php echo htmlspecialchars($comment["username"]); ?></h4>
                    <p><?php echo htmlspecialchars($comment["comment"]); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
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

document.querySelector("#btnAddComment").addEventListener("click", (e) => {
    e.preventDefault();

    let comment = document.querySelector("#comment").value;
    let promptId = document.querySelector(".prompt_details").dataset.promptid;
    let userId = <?php echo $_SESSION['id']; ?>;

    console.log(comment);
    console.log(promptId);
    console.log(userId);

    let formData = new FormData();
    formData.append("comment", comment);
    formData.append("promptId", promptId);
    formData.append("userId", userId);

    async function upload(formData) {
        try {
            const response = await fetch("ajax/addcomment.php", {
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
</script>

</body>
</html>
