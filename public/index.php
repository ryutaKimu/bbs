<?php
require("../config/library.php");
session_start();

$username = $_SESSION['form']['username'];
$id = $_SESSION['form']['id'];

if($username === null || $id === null){
    header('Location:login.php');
}


if (isset($username) && empty($username) && isset($id) && empty($id)){
    header('Location:login.php');
    exit();
}




$dbh = dbConnect();
$selectQuery = "select t.id,t.title,t.created_at,p.content,p.image_path,u.username
from threads t inner join posts p on t.id = p.thread_id inner join users u on t.user_id = u.id order by t.id desc";
$stmt = $dbh->prepare($selectQuery);
$stmt->execute();
$results = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bbs.css">
    <title>Document</title>
</head>

<body>
    <header>
        <?php include '../templates/header.php'; ?>
        <hr />
        <p>ようこそ、<?php echo $username ?>さん</p>
    </header>
    <main>
        <section class="board">
            <a href="makeThread.php" class="newThreadLink">新規スレッド作成</a> <!-- 新規スレッド作成ページへのリンク -->
            <ul>
                <?php foreach ($results as $result) : ?>
                    <li>
                        <h2><a href="#"><?php echo $result['title']; ?></a></h2>
                        <!--substrを使うと中途半端な文字を切り取るので、文字化けを起こす -->
                        <p class="content"><?php echo mb_substr($result['content'], 0, 90); ?></p>
                        <p>作成日時: <?php echo $result['created_at']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>

</html>