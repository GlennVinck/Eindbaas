<?php
include_once "../bootstrap.php";

if (!empty($_POST)) {
        $comment = new \PrompTopia\Framework\Comment();
        $comment->setUserId($_POST['userId']);
        $comment->setPromptId($_POST['promptId']);
        $comment->setComment($_POST['comment']);
        $comment->save();
    
        $response = [
            'status' => 'success',
            'body' => htmlspecialchars($comment->getComment()),
            'message' => 'comment saved'
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
