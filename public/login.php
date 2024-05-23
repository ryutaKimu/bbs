<?php
session_start();
require("../config/library.php");


$form = [
    'username' => "",
    'password' => "",
];


$error = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fields = ['username', "password"];
    $postData = getFilteringPostData($fields);

    $authenticationResult = authenticateUser($postData);

    if (validate($postData['username']) === "blank" || validate($postData['password']) === "blank") {
        $error['login'] = 'blank';
    } elseif ($authenticationResult === "missMatch") {
        $error['login'] = "missMatch";
    }


    if ($authenticationResult !== "missMatch" && isset($authenticationResult['id'])) {
        $_SESSION['form']['username'] = $postData['username'];
        $_SESSION['form']['id'] = $authenticationResult['id'];
        var_dump($_SESSION['form']['id']);
        header('Location:index.php');
        exit();
    }


    $form = $postData;
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

            <?php if (isset($error['login']) && $error['login'] === 'blank') : ?>
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