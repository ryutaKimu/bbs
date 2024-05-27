<?php
session_start();
require('../config/library.php');




if (
    isset($_SESSION['form']['user_id']) && !empty($_SESSION['form']['user_id']) &&
    isset($_SESSION['form']['username']) && !empty($_SESSION['form']['username'])
) {
    $user_id = $_SESSION['form']['user_id'];
    $username = $_SESSION['form']['username'];
} else {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $thread_id = $_GET['id'];
    $_SESSION['thread_id'] = $thread_id;
} else {
    if (isset($_SESSION['thread_id'])) {
        $thread_id = $_SESSION['thread_id'];
    } else {
        header('Location: index.php');
        exit();
    }
}

$form = ['comment' => ""];
$error = [];

$dbh = dbConnect();
$selectQuery = "SELECT t.id, t.title, t.created_at, p.content AS initial_post, p.image_path AS initial_image, u.username 
                FROM threads t 
                INNER JOIN posts p ON t.id = p.thread_id 
                INNER JOIN users u ON t.user_id = u.id 
                WHERE t.id = :thread_id 
                LIMIT 1";
$threadStmt = $dbh->prepare($selectQuery);
$threadStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
$threadStmt->execute();
$thread = $threadStmt->fetch(PDO::FETCH_ASSOC);

$otherQuery = "SELECT p.content, p.created_at, p.image_path, u.username 
               FROM posts p 
               INNER JOIN users u ON p.user_id = u.id 
               WHERE p.thread_id = :thread_id AND p.content != :initial_post
               ORDER BY p.created_at ASC";
$otherPostStmt = $dbh->prepare($otherQuery);
$otherPostStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
$otherPostStmt->bindParam(':initial_post', $thread['initial_post'], PDO::PARAM_STR);
$otherPostStmt->execute();
$otherPosts = $otherPostStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $field = ['comment'];
    $postData = getFilteringPostData($field);
    if (empty($postData['comment'])) {
        $error['comment'] = "blank";
    } else {
        $form = $postData;
        $image_path = isset($_FILES['image']) ? "images/" . uniqid() . "_" . $_FILES['image']['name'] : null;
        $postQuery = "INSERT INTO posts (thread_id, user_id, content, image_path, created_at)
                      VALUES (:thread_id, :user_id, :content, :image_path, NOW())";
        $postStmt = $dbh->prepare($postQuery);
        $postStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
        $postStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $postStmt->bindParam(':content', $form['comment'], PDO::PARAM_STR);
        $postStmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
        if ($postStmt->execute()) {
            $_SESSION['form'] = array(
                'username' => $username,
                'user_id' => $user_id
            );
            session_write_close();
            header("Location: thread.php?id=" . $thread_id);
            exit();
        } else {
            echo "Error: " . $postStmt->errorInfo()[2];
        }
    }
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
            <h2><?php if (isset($thread['title'])) echo htmlspecialchars($thread['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>作者: <span class="authorName"><?php if (isset($thread['username'])) echo htmlspecialchars($thread['username'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>作成日時: <span class="createdAt"><?php if (isset($thread['created_at'])) echo htmlspecialchars($thread['created_at'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <div class="initialPost">
                <p><?php if (isset($thread['initial_post'])) echo htmlspecialchars($thread['initial_post'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if (isset($thread['initial_image'])) : ?>
                    <img src="<?php echo htmlspecialchars($thread['initial_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="image">
                <?php endif; ?>
            </div>
        </div>
        <div class="otherContainer">
            <ul>
                <?php foreach ($otherPosts as $post) : ?>
                    <li>
                        <div class="post">
                            <p>ユーザー: <span class="authorName"><?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                            <p><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <span class="commentDate"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if (!empty($post['image_path'])) : ?>
                                <img src="<?php echo htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="image">
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="postForm">
            <form action="thread.php?id=<?php echo htmlspecialchars($thread_id, ENT_QUOTES, 'UTF-8'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php if (isset($_SESSION['id'])) echo htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <label for="content">コメント:</label>
                    <textarea name="comment" id="content" rows="5" required><?php echo htmlspecialchars($form['comment'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <button type="submit">投稿</button>
            </form>
        </div>
    </div>
</body>

</html>