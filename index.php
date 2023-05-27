<?php
include_once (__DIR__ . "/bootstrap.php");
$config = parse_ini_file( "config/config.ini");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SESSION['loggedin'] !== true){
    header('Location: notloggedin.php');
}

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize; //voor het resizen van de afbeelding

//aanmaken van cloudinary
$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name'=> $config['cloud_name'],
            'api_key'=> $config['api_key'],
            'api_secret'=> $config['api_secret'],
        ],
    ]
);

$categories = \PrompTopia\Framework\Prompt::categories();

if(!empty($_POST)){
    if(isset($_FILES['img'])){
        try{
            $img = new \PrompTopia\Framework\Img($cloudinary);
            $imgName = $img->upload($_FILES['img']);
            $prompt = new \PrompTopia\Framework\Prompt();
            $prompt->setTitle($_POST["title"]);
            $prompt->setPrompt($_POST["prompt"]);
            $prompt->setImg($imgName);
            $prompt->setPrice($_POST["price"]);
            $prompt->setType($_POST["type"]);
            $prompt->setTags($_POST["tags"]);
            $prompt->setUserId($_SESSION['id']);
            $prompt->save();
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * 10;
            $prompts = \PrompTopia\Framework\Prompt::getAll($offset);
            $totalPrompts = \PrompTopia\Framework\Prompt::countAll();
            $totalPages = ceil($totalPrompts / 10);
        }
        catch (Throwable $e) {
            $error = $e->getMessage();
        }
        
    } else {
        $error = "Please upload an image";
    }
   
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * 10;
$prompts = \PrompTopia\Framework\Prompt::getAll($offset);
$totalPrompts = \PrompTopia\Framework\Prompt::countAll();
$totalPages = ceil($totalPrompts / 10);

$isAdmin = false;
if (isset($_SESSION['id'])) {
    $isAdmin = \PrompTopia\Framework\User::isAdmin($_SESSION['id']);
}

if (isset($_GET['filter'])) {
    $prompts = \PrompTopia\Framework\Prompt::getFiltered($_GET['filter']);
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
<h1 style="margin:100px;">Dit is de Homepage</h1>

    <?php
    // Display the admin status if the user is logged in
    if ($isAdmin) {
        echo "<p>Welcome, you are an admin!</p>";
    } else {
        echo "<p>Welcome, you are not an admin.</p>";
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?> 
        <div class="prompt_field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title">
        </div>
        <div class="prompt_field"> 
            <label for="prompt">Prompt</label>
            <input type="text" id="prompt" name="prompt">
        </div>
        <div class="prompt_field">
            <label for="img">Selecteer een bestand:</label>
            <input type="file" name="img" id="img">
        </div>
        <div class="prompt_field"> 
            <label for="price">Prijs</label>
            <select name="price" id="price">
                <option value="free">Free</option>
                <option value="1credit">1 credit</option>
                <option value="2credits">2 credits</option>
            </select>
        </div>
       <div class="prompt_field">  
            <label for="type">Waar voerde je dit prompt in?</label>
            <input type="text" id="type" name="type">
        </div>
       <div class="prompt_field">
            <label for="tags">Free tags</label>
            <input type="text" id="tags" name="tags">
        </div>
        <ul class="items">
        <?php foreach($categories as $categorie):?>
            <li>
                <input name="categories[]" type="checkbox" value="<?php echo $categorie["id"]?>" /> 
                <?php echo $categorie["name"]?> 
            </li>
        <?php endforeach;?>
    </ul>
        <div class="prompt_field" >
            <input type="submit" value="Post" class="btn">
        </div>
        
    </form>

    <form action="" method="get">
    <label for="filter">Filter on price</label>
    <select name="filter">
        <option value="0">Free</option>
        <option value="1">1 credit</option>
        <option value="2">2 credits</option>
    </select>
    <input type="submit" value="Filter" class="btn btn--primary btn--filter">
</form>

<?php
    if (isset($_GET['filter'])) {
        $selected_value = $_GET['filter'];
        $label = "";
        
        switch ($selected_value) {
            case "0":
                $label = "Free";
                break;
            case "1":
                $label = "1 credit";
                break;
            case "2":
                $label = "2 credits";
                break;
            default:
                $label = "Unknown";
                break;
        }
        
        echo "You selected: " . $label;
    }
    ?> 
   
   <?php foreach ($prompts as $prompt): ?>
    <div class="prompt">

        <h4><a href="otherUser.php?username=<?php echo htmlspecialchars($prompt["username"]);?>"><?php echo htmlspecialchars($prompt["username"]);?></a></h4> 

        <h2><?php echo htmlspecialchars($prompt["title"]); ?></h2>
        <h3><?php echo htmlspecialchars($prompt["prompt"]); ?></h3>
        <img src="<?php echo $cloudinary->image($prompt["img"])->resize(Resize::fill(300, 150))->toUrl();?>" alt="">
        <p><?php echo $prompt["price"]; ?></p>
        <p><?php echo htmlspecialchars($prompt["type"]); ?></p>
        <p><?php echo htmlspecialchars($prompt["tags"]); ?></p>
        <a class="favourite-btn" style="color: yellow" data-promptid="<?php echo $prompt['id']; ?>">FAVOURITE</a>
        <a class="like-btn" style="color: blue" data-promptid="<?php echo $prompt['id']; ?>">LIKE</a>
    </div>
<?php endforeach; ?>


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
    </script>
</body>
</html>