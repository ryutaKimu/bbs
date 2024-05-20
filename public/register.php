<?php
session_start();
require('../config/library.php');



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fields = ['username', "password"];
    $form = getFilteringPostData($fields);

    $hashPassword = password_hash($form['password'], PASSWORD_DEFAULT);

    $dbc = dbConnect();
    $query = "INSERT INTO users(username,password)VALUES(:username,:password)";
    $stmt = $dbc->prepare($query);
    $stmt->bindParam(':username', $form['username'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
    $success = $stmt->execute();

    if ($success) {
        $_SESSION['form']['username'] = $form['username'];
        header('Location:check.php');
        exit();
    } else {
        echo "登録に失敗しました。";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <title>Document</title>
</head>

<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="registerContainer">
            <h1>新規登録</h1>
            <form method="post" action="register.php">
                <?php if ((isset($form['username']) && validate($form['username']) === "blank") || isset($form['password']) && validate($form['password']) === "blank") : ?>
                    <p class="error-message">登録には名前とパスワードが必要です。</p>
                <?php endif; ?>
                <div class="form-group">
                    <label>名前:</label>
                    <input type="text" name="username" value="">
                </div>

                <div class="form-group">
                    <label>password:</label>
                    <input type="password" name="password" value="">
                </div>
                <button type="submit" name="submit">登録画面へ進む</button>
            </form>

        </div>
    </main>
</body>

</html>