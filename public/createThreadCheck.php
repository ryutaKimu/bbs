<?php
session_start();
require('../config/library.php');

if (!isset($_SESSION['form']) || empty($_SESSION['form'])) {
    header('Location:index.php');
    exit();
}

$id = $_SESSION['form']['user_id'];
$username = $_SESSION['form']['username'];
$title = $_SESSION['form']['title'];
$content = $_SESSION['form']['content'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $dbh = dbConnect();
    $image_path = isset($_FILES['image']) ? "images/" . uniqid() . "_" . $_FILES['image']['name'] : null;
    $query = "INSERT INTO threads(title,user_id,created_at)VALUES(:title,:user_id,NOW())";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $thread_id = $dbh->lastInsertId();

    $postQuery = "INSERT INTO posts(thread_id,user_id,content,image_path,created_at)VALUES(:thread_id,:user_id,:content,:image_path,NOW())";
    $insert = $dbh->prepare($postQuery);
    $insert->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
    $insert->bindParam(':user_id', $id, PDO::PARAM_INT);
    $insert->bindParam(':content', $content, PDO::PARAM_STR);
    $insert->bindParam(':image_path', $image_path, PDO::PARAM_STR);

    $result = $insert->execute();
    if ($result) {
        $_SESSION['form']['id'] = $id;
        $_SESSION['form']['username'] = $username;
        header('Location:index.php');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/preview.css">
    <title>プレビュー</title>
</head>

<body>
    <?php include('../templates/header.php') ?>
    <form method="post" action="createThreadCheck.php">
        <div class="thread-preview">
            <h2><?php echo $title; ?></h2>
            <hr />
            <p><?php echo nl2br($content); ?></p>
        </div>
        <button type="submit">作成</button>
    </form>

</body>

</html>