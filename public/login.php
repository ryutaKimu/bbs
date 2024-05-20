<?php
session_start();
require("../config/library.php");

$fields = ['username', "password"];
$form = getFilteringPostData($fields);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $authenticationResult = authenticateUser($form);
    if ($authenticationResult === "missMatch") {
        $error['login'] = "missMatch";
    }

    // 認証成功の場合はセッションを設定し、リダイレクト
    if ($authenticationResult === true) {
        $_SESSION['form']['username'] = $form['username'];
        header('Location:index.php');
        exit(); // リダイレクト後にスクリプトを停止するために exit() を使用
    }
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="./css/login.css"> <!-- CSSファイルのリンク -->
</head>

<body>
    <?php include '../templates/header.php'; ?>
    <div>
        <a class="toRegister" href="register.php">新規登録</a>
    </div>
    <div class="login-container">
        <h1>ログイン</h1>
        <form action="login.php" method="post">

            <?php if ((isset($form['username']) && validate($form['username']) === 'blank') || (isset($form['password']) && validate($form['password']) === 'blank')) : ?>
                <p class="error-message">ユーザー名とパスワードは必須です</p>
            <?php endif; ?>

            <?php if (isset($error['login']) && $error['login'] === "missMatch") : ?>
                <p class="error-message">メールアドレスかパスワードが間違っています</p>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" value="<?php if (isset($form['username'])) hsc($form['username']); ?>">
            </div>
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" name="submit">ログイン</button>
        </form>
    </div>
</body>

</html>