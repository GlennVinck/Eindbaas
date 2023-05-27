<?php
require_once __DIR__ . "/bootstrap.php";

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promptId = $_POST['prompt_id'];
    $username = $_SESSION['username'];

    $prompt = \PrompTopia\Framework\Prompt::getPromptByUserId($username, $promptId);

    // Check if the prompt exists and belongs to the current user
    if (!empty($prompt)) {
        // Delete the prompt
        \PrompTopia\Framework\Prompt::deletePrompt($promptId);

        // Redirect back to the profile page
        header("Location: profile.php");
        exit;
    }
}

// Redirect back to the profile page if the request method is not POST or the prompt doesn't exist
header("Location: profile.php");
exit;
?>
