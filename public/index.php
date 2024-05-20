<?php
require("../config/library.php");
session_start();


if (!empty($_SESSION['form']['username'])) {
    $username = $_SESSION['form']['username'];
} else {
    header('Location:login.php');
    exit();
}


dbConnect();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bbs.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1>掲示板</h1>
        <hr />
        <p>ようこそ、<?php echo $username ?>さん</p>
    </header>
    <main>
        <section class="board">
            <a href="public/create_thread.php" class="newThreadLink">新規スレッド作成</a> <!-- 新規スレッド作成ページへのリンク -->
            <ul>
                <li>
                    <h2><a href="#">ダミースレッド1</a></h2>
                    <p>このスレッドはダミーです。実際の投稿はありません。</p>
                    <p>作成日時: 2024-05-20</p>
                </li>
                <li>
                    <h2><a href="#">ダミースレッド2</a></h2>
                    <p>このスレッドもダミーです。投稿内容はありません。</p>
                    <p>作成日時: 2024-05-19</p>
                </li>
                <li>
                    <h2><a href="#">ダミースレッド2</a></h2>
                    <p>このスレッドもダミーです。投稿内容はありません。</p>
                    <p>作成日時: 2024-05-19</p>
                </li>
                <li>
                    <h2><a href="#">ダミースレッド2</a></h2>
                    <p>このスレッドもダミーです。投稿内容はありません。</p>
                    <p>作成日時: 2024-05-19</p>
                </li>
                <li>
                    <h2><a href="#">ダミースレッド2</a></h2>
                    <p>このスレッドもダミーです。投稿内容はありません。</p>
                    <p>作成日時: 2024-05-19</p>
                </li>
            </ul>
        </section>
    </main>
</body>

</html>