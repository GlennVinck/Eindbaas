<?php 
include_once (__DIR__ . "/bootstrap.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: notloggedin.php');
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * 10;
$prompts = \PrompTopia\Framework\Prompt::getAll($offset);
$totalPrompts = \PrompTopia\Framework\Prompt::countAll();
$totalPages = ceil($totalPrompts / 10);

// Check if a search query is submitted
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $prompts = \PrompTopia\Framework\Prompt::searchPrompts($searchQuery);
} else {
    $prompts = \PrompTopia\Framework\Prompt::getAll();
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
<h1 style="margin:100px;">Dit is de marktplaats</h1>

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
    const detailBtns = document.querySelectorAll('.detail');
    Array.from(detailBtns).forEach((btn) => {
        btn.addEventListener('click', () => {
            let promptId = btn.dataset.promptid;
            window.location.href = `promptdetail.php?id=${promptId}`;
        });
    });
    </script>


</body>
</html>
