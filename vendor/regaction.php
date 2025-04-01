<?php
    require "db.php";

    $login = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));
    $phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS));

//INSERT
$sql = 'INSERT INTO users(login, password, phone, email) VALUES(?, ?, ?, ?)';
$query = $pdo->prepare($sql); 
$query->execute([$login, $password, $phone, $email]);

header('Location: ../index.php');
exit();