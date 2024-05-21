<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/thread.css">
    <title>スレッド作成</title>
</head>

<body>
    <?php include '../templates/header.php' ?>
    <div class="threadContainer">
        <form action="makeThread.php" method="POST">
            <div class="title-control">
                <label>タイトル:</label>
                <input type="text" name="title">
            </div>

            <div class="content-control">
                <label>内容:</label>
                <textarea name="content" class="textArea" cols="30" rows="10"></textarea>
            </div>

            <button type="submit">作成</button>
        </form>
    </div>
</body>

</html>
