<?php
session_start();
require('../config/library.php');


// セッションチェック
if ((isset($_SESSION['form']['id']) && !empty($_SESSION['form']['id'])) ||
    (isset($_SESSION['form']['username']) && !empty($_SESSION['form']['username']))
) {
    $user_id = $_SESSION['form']['id'];
    $username = $_SESSION['form']['username'];
} else {
    header('Location:login.php');
}


// GETリクエストでIDを取得
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $thread_id = $_GET['id'];
    $_SESSION['id'] = $thread_id;
}






$form = [
    'comment' => ""
];
$error = [];

$dbh = dbConnect();
$selectQuery = "SELECT t.id,t.title,t.created_at,p.content,u.username from threads t inner join posts p on t.id = p.thread_id inner join users u on t.user_id = u.id where t.id = :thread_id limit 1 ";
$threadStmt = $dbh->prepare($selectQuery);
$threadStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
$threadStmt->execute();
$thread = $threadStmt->fetch(PDO::FETCH_ASSOC);

$otherQuery = "SELECT p.content, p.created_at, p.image_path, u.username 
FROM posts p 
INNER JOIN users u ON p.user_id = u.id 
WHERE p.thread_id = :thread_id 
ORDER BY p.created_at ASC 
LIMIT 1000 OFFSET 1";
$otherPostStmt = $dbh->prepare($otherQuery);
$otherPostStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
$otherPostStmt->execute();
$otherPost =  $otherPostStmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $field = ['comment'];
    $postData = getFilteringPostData($field);
    if (validate($postData['comment'])) {
        $error['comment'] = "blank";
    }


    $form = $postData;
    var_dump($form['comment']);


    $_SESSION['form'] =  $_SESSION['form'];




    $image_path = isset($_FILES['image']) ? "images/" . uniqid() . "_" . $_FILES['image']['name'] : null;
    $postQuery = "INSERT INTO posts(thread_id,user_id,content,image_path,created_at)VALUES(:thread_id,:user_id,:content,:image_path,NOW())";
    $postStmt = $dbh->prepare($postQuery);
    $postStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
    $postStmt->bindParam(':user_id', $_SESSION['form']['id'], PDO::PARAM_INT);
    $postStmt->bindParam(':content', $form['comment'], PDO::PARAM_STR);
    $postStmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
    $postStmt->execute();
    $posts = $postStmt->fetchAll(PDO::FETCH_ASSOC);
}




?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/threadPosts.css">
    <title>スレッド</title>
</head>

<body>
    <?php include('../templates/header.php') ?>
    <a class="logOut" href="logOut.php">ログアウト</a>
    <div class="container">
        <div class="authorContainer">
            <h2><?php if (isset($thread['title'])) echo $thread['title']; ?></h2>
            <p>作者: <span class="authorName"><?php if (isset($thread['username'])) echo $thread['username']; ?></span></p>
            <p>作成日時: <span class="createdAt"><?php if (isset($thread['created_at'])) echo $thread['created_at']; ?></span></p>
            <div class="initialPost">
                <p><?php if (isset($thread['content'])) echo $thread['content']; ?></p>
            </div>
        </div>
        <div class="otherContainer">
            <ul>
                <li>
                    <div class="post">
                        <p>ユーザー: <span class="authorName">test</span></p>
                        <p>アホすぎる</p>
                        <span class="commentDate">2023-05-25</span>
                    </div>
                </li>
                <li>
                    <div class="post">
                        <p>ユーザー: <span class="authorName">test</span></p>
                        <p>なんか前もなくしてなかった？</p>
                        <span class="commentDate">2023-05-26</span>
                    </div>
                </li>
                <li>
                    <div class="post">
                        <p>ユーザー: <span class="authorName">test</span></p>
                        <p>流石にやらかしすぎでは？</p>
                        <span class="commentDate">2023-05-27</span>
                    </div>
                </li>
                <li>
                    <div class="post">
                        <p>ユーザー: <span class="authorName">test</span></p>
                        <p>大阪で落としてたよな。懲りないやつめ。早く届出出せよ。</p>
                        <span class="commentDate">2023-05-27</span>
                    </div>
                </li>
                <li>
                    <div class="post">
                        <p>ユーザー: <span class="authorName">test</span></p>
                        <p>ものは大切にしろよ</p>
                        <span class="commentDate">2023-05-27</span>
                    </div>
                </li>
            </ul>
        </div>
        <!-- コメント投稿フォーム -->
        <div class="postForm">
            <form action="thread.php?id=<?php echo $thread_id ?>" method="POST">
                <input type="hidden" name="id" value="<?php if (isset($_SESSION['id']['thread_id'])) echo $_SESSION['id']; ?>">
                <div class="form-group">
                    <label for="content">コメント:</label>
                    <textarea name="comment" id="content" rows="5" required><?php hsc($form['comment']) ?></textarea>
                </div>
                <button type="submit">投稿</button>
            </form>
        </div>
    </div>
</body>

</html>