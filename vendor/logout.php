<?php
    require "db.php";

// Удаляем куки
setcookie('login', '', time() - 3600, '/');

// Перенаправляем пользователя на страницу авторизации
header('Location: ../auth index.php');
exit();
?>
