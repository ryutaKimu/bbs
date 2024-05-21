<?php
session_start();
require('../config/library.php');



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fields = ['username', "password"];
    $form = getFilteringPostData($fields);

    $validateResult = validate($form['username'],$form['password']);
    if($validateResult != "blank"){
        $_SESSION['form'] = $form;
        header('Location:check.php');
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/registerFlow.css">
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
                    <label>パスワード:</label>
                    <input type="password" name="password" value="">
                </div>
                <button type="submit" name="submit">登録画面へ進む</button>
            </form>

        </div>
    </main>
</body>

</html>