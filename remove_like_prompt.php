<?php
require_once __DIR__ . "/bootstrap.php";

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the prompt ID from the form data
    $promptId = $_POST['prompt_id'];

    // Get the currently logged-in user's ID
    $userId = $_SESSION['username'];

    // Remove the like
    \PrompTopia\Framework\Like::removeLike($username, $promptId);

    // Redirect back to the profile page
    header("Location: profile.php");
    exit;
}