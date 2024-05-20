<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>セッション確認</title>
</head>

<body>
    <?php
    if (isset($_SESSION['form']['username'])) {
        echo "こんにちは、" . htmlspecialchars($_SESSION['form']['username'], ENT_QUOTES, 'UTF-8') . "さん";
    } else {
        echo "セッションが確立されていません。";
    }
    ?>
</body>

</html>