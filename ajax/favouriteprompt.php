<?php
include_once "../bootstrap.php";

if (!empty($_POST)) {

    $isfavourite = \PrompTopia\Framework\Favourite::getFavourites($_POST['promptId'], $_POST['userId']);

    if(!empty($isfavourite)){
        \PrompTopia\Framework\Favourite::removeFavourite($_POST['promptId'], $_POST['userId']);
        $response = [
            'status' => 'success',
            'message' => 'Favourite removed'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $favourite = new \PrompTopia\Framework\Favourite();
        $favourite->setUserId($_POST['userId']);
        $favourite->setPromptId($_POST['promptId']);
        $favourite->save();
    
        $response = [
            'status' => 'success',
            'message' => 'Favourite saved'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No data received'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
