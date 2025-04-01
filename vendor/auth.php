<?php
session_start();

require "db.php";

// Функция для редиректа с сообщением
function redirectWithMessage($message, $location) {
    echo "<script>
        alert('$message');
        window.location.href = '$location';
    </script>";
    exit;
}

$login = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
$password = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));

try {
    $sql = 'SELECT id, isAdmin FROM users WHERE login = ? AND password = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$login, $password]);

    if ($query->rowCount() == 0) {
        redirectWithMessage('Такой пользователь не был найден. Пожалуйста проверьте корректность ввода данных', '../auth index.php');
    }

    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    setcookie('login', $login, time() + 3600 * 24, "/");
    $_SESSION['isAdmin'] = $user['isAdmin'];

    $redirectPage = $user['isAdmin'] == 1 ? '../admin.php' : '../account.php';
    header("Location: $redirectPage");
    exit;

} catch (PDOException $e) {
    redirectWithMessage('Произошла ошибка при авторизации', '../auth index.php');
}
?>
