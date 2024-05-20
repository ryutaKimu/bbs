<?php

function dbConnect()
{
    $localhost = "localhost";
    $dbname = "Thread_bbs";
    $user = "root";
    $password = "root";
    try {
        $pdo = new PDO("mysql:host=$localhost;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
};


function authenticateUser($form)
{
    $dbh = dbConnect();

    //Hash化したpasswordはwhere条件に含めない(当然、検索されない)
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':username', $form['username'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "missMatch";
    }
    if ($form['username'] !== $user['username'] || !password_verify($form['password'], $user['password'])) {
        return "missMatch";
    }
    return true;
}




function hsc($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}


function getFilteringPostData($fields)
{
    return filter_input_array(INPUT_POST, array_fill_keys($fields, FILTER_DEFAULT));
}

function validate($username, $password = null)
{
    if (empty($username) && (is_null($password) || empty($password))) {
        return "blank";
    }
}
