<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'grant_admin') {
    $userId = $_POST['user_id'] ?? 0;
    $captcha_input = $_POST['captcha_input'] ?? '';
    
    // Функция для редиректа с сообщением
    function redirectWithMessage($message) {
        echo "<script>
            alert('$message');
            window.location.href = '../admin.php';
        </script>";
        exit;
    }

    // Проверка капчи
    if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] !== $captcha_input) {
        $message = "Ошибка: Неправильная капча! Правильная капча: {$_SESSION['captcha']}, Вы ввели: {$captcha_input}";
        unset($_SESSION['captcha']);
        redirectWithMessage($message);
    }

    try {
        // Обновление статуса пользователя на администратор
        $stmt = $pdo->prepare("UPDATE users SET isAdmin = 1 WHERE id = ?");
        $stmt->execute([$userId]);
        
        unset($_SESSION['captcha']);
        redirectWithMessage('Пользователю был выдан статус: Администратор');
    } catch (PDOException $e) {
        redirectWithMessage('Произошла ошибка при обновлении статуса пользователя');
    }
}
?>
