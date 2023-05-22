<?php
include_once "../bootstrap.php";

if (!empty($_POST)) {
    $favourite = new \PrompTopia\Framework\Favourite();
    $favourite->setUserId($_POST['userId']);
    $favourite->setPromptId($_POST['promptId']);
    $favourite->save();

    $response = [
        'status' => 'success',
        'message' => 'Favourite saved'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'No data received'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
