<?php
session_start();
require('../config/library.php');


if (isset($_SESSION['form'])) {
    $form = $_SESSION['form'];
} else {
    header('Location:register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $hashPassword = password_hash($form['password'], PASSWORD_DEFAULT);
    $dbc = dbConnect();
    $query = "INSERT INTO users(username,password)VALUES(:username,:password)";
    $stmt = $dbc->prepare($query);
    $stmt->bindParam(':username', $form['username'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
    $success = $stmt->execute();

    if ($success) {
        unset($_SESSION['form']);
        header('Location:registerComplete.php');
        exit();
    } else {
        echo "登録に失敗しました。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/registerFlow.css">
    <title>登録確認</title>
</head>

<body>

    <main>
        <?php include '../templates/header.php'; ?>
        <div class="registerContainer">
            <h1>確認画面</h1>
            <form action="check.php" method="post">
                <dl>
                    <dt>名前</dt>
                    <dd><?php echo hsc($form['username']) ?></dd>
                    <dt>パスワード</dt>
                    <dd>[表示できません]</dd>
                </dl>
                <button type="submit" name="submit">登録する</button>
            </form>

        </div>
    </main>
</body>

</html>