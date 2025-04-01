<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Проверка капчи для действия удаления
    if ($action === 'delete') {
        $captcha_input = $_POST['captcha_input'] ?? '';
        if (!validateCaptcha($captcha_input)) {
            showAlert("Вы ввели неправильную каптчу.\n\nПравильная капча: {$_SESSION['captcha']}\nВы ввели: {$captcha_input}", '../admin.php');
            exit;
        }
    }

    try {
        switch($action) {
            case 'delete':
                $userId = $_POST['user_id'] ?? 0;
                executeQuery("DELETE FROM users WHERE id = ?", [$userId]);
                showAlert('Пользователь успешно удален', '../admin.php');
                break;

            case 'add':
                $userData = [
                    $_POST['login'] ?? '',
                    $_POST['password'] ?? '',
                    $_POST['phone'] ?? '',
                    $_POST['email'] ?? '',
                    $_POST['isAdmin'] ?? 0
                ];
                executeQuery(
                    "INSERT INTO users (login, password, phone, email, isAdmin) VALUES (?, ?, ?, ?, ?)", 
                    $userData
                );
                showAlert('Новый пользователь добавлен', '../admin.php');
                break;

            case 'edit':
                $userData = [
                    $_POST['login'] ?? '',
                    $_POST['password'] ?? '',
                    $_POST['phone'] ?? '',
                    $_POST['email'] ?? '',
                    $_POST['isAdmin'] ?? 0,
                    $_POST['user_id'] ?? 0
                ];
                executeQuery(
                    "UPDATE users SET login = ?, password = ?, phone = ?, email = ?, isAdmin = ? WHERE id = ?",
                    $userData
                );
                showAlert('Данные пользователя обновлены', '../admin.php');
                break;
        }
    } catch (PDOException $e) {
        showAlert('Произошла ошибка при выполнении операции', '../admin.php');
    }
}

function validateCaptcha($input) {
    if ($_SESSION['captcha'] !== $input) {
        return false;
    }
    unset($_SESSION['captcha']);
    return true;
}

function executeQuery($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

function showAlert($message, $redirect) {
    echo "<script>
        alert('$message');
        window.location.href = '$redirect';
    </script>";
}
?>
