<?php
include_once "../bootstrap.php";

if (!empty($_POST)) {

    $isliked = \PrompTopia\Framework\Like::getLike($_POST['promptId'], $_POST['userId']);

    if(!empty($isliked)){
        \PrompTopia\Framework\Like::removeLike($_POST['promptId'], $_POST['userId']);
        $response = [
            'status' => 'success',
            'message' => 'Like removed'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $like = new \PrompTopia\Framework\Like();
        $like->setUserId($_POST['userId']);
        $like->setPromptId($_POST['promptId']);
        $like->save();
    
        $response = [
            'status' => 'success',
            'message' => 'Like saved'
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
