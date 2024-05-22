<?php
session_start();
require('../config/library.php');

if(isset($_SESSION['user'])){
    $username = $_SESSION['user']['username'];
    $id = $_SESSION['user']['id'];
}


$form = [
    'title' => "",
    'content' => "",
];
$error = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fields = ['title', 'content'];
    $postData = getFilteringPostData($fields);

    // タイトルと内容のバリデーション
    if (validate($postData['title']) === "blank" || validate($postData['content']) === "blank") {
        $error['post'] = "blank";
    } else {
        // バリデーション成功時の処理
        // ここでデータベースにスレッドを保存するなどの処理を行います
        $form = $postData;
        if(empty($error)){
            $_SESSION['form'] = $form;
            $username = $_SESSION['user']['username'];
            $id = $_SESSION['user']['id'];
            header(('Location:CreateThreadCheck.php'));
            exit();
        }
        
    }

    
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/thread.css">
    <title>スレッド作成</title>
</head>

<body>
    <?php include '../templates/header.php'; ?>
    <div class="threadContainer">
        <h1>スレッド作成</h1>
        <form action="makeThread.php" method="POST">
            <?php if (isset($error['post']) && $error['post'] === 'blank') : ?>
                <p class="error-message">空白がないようにしてください</p>
            <?php endif; ?>
            <div class="title-control">
                <label>タイトル:</label>
                <input type="text" name="title" value="<?php if (isset($form['title'])) echo hsc($form['title']); ?>">
            </div>

            <div class="content-control">
                <label>内容:</label>
                <textarea name="content" class="textArea" cols="30" rows="10"><?php if (isset($form['content'])) echo hsc($form['content']); ?></textarea>
            </div>

            <button type="submit">作成</button>
        </form>
    </div>
</body>

</html>
