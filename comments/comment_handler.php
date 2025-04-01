<?php
session_start();
require_once 'CommentsManager.php';

header('Content-Type: application/json');

// Проверка авторизации для некоторых действий
function isUserLoggedIn() {
    return isset($_COOKIE['login']);
}

// Получение имени пользователя
function getUserName() {
    return $_COOKIE['login'] ?? 'Гость';
}

// Функция для ответа с ошибкой
function sendError($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Инициализация менеджера комментариев
$commentsManager = new CommentsManager();

// Получаем действие из запроса
$action = $_POST['action'] ?? 'get';

// Обработка действий
switch ($action) {
    case 'add':
        // Проверка авторизации
        if (!isUserLoggedIn()) {
            sendError('Для добавления комментария необходимо авторизоваться');
        }
        
        // Получение параметров
        $type = $_POST['type'] ?? '';
        $kinopoiskId = $_POST['kinopoiskId'] ?? 0;
        $text = $_POST['text'] ?? '';
        $rating = $_POST['rating'] ?? 5;
        
        // Валидация
        if (empty($type) || !in_array($type, ['film', 'serial'])) {
            sendError('Некорректный тип контента');
        }
        
        if (empty($kinopoiskId) || !is_numeric($kinopoiskId)) {
            sendError('Некорректный ID контента');
        }
        
        if (empty($text)) {
            sendError('Текст комментария не может быть пустым');
        }
        
        if (!is_numeric($rating) || $rating < 1 || $rating > 10) {
            sendError('Рейтинг должен быть числом от 1 до 10');
        }
        
        // Добавление комментария
        $result = $commentsManager->addComment($type, $kinopoiskId, getUserName(), $text, $rating);
        
        if ($result) {
            // Возвращаем обновленный список комментариев и статистику
            $comments = $commentsManager->getComments($type, $kinopoiskId);
            $stats = $commentsManager->getStats($type, $kinopoiskId);
            
            echo json_encode([
                'success' => true,
                'comments' => $comments,
                'stats' => $stats
            ]);
        } else {
            sendError('Не удалось добавить комментарий');
        }
        break;
        
    case 'get':
        // Получение параметров
        $type = $_GET['type'] ?? '';
        $kinopoiskId = $_GET['kinopoiskId'] ?? 0;
        
        // Валидация
        if (empty($type) || !in_array($type, ['film', 'serial'])) {
            sendError('Некорректный тип контента');
        }
        
        if (empty($kinopoiskId) || !is_numeric($kinopoiskId)) {
            sendError('Некорректный ID контента');
        }
        
        // Получение комментариев
        $comments = $commentsManager->getComments($type, $kinopoiskId);
        $stats = $commentsManager->getStats($type, $kinopoiskId);
        
        echo json_encode([
            'success' => true,
            'comments' => $comments,
            'stats' => $stats
        ]);
        break;
        
    case 'delete':
        // Проверка авторизации
        if (!isUserLoggedIn()) {
            sendError('Для удаления комментария необходимо авторизоваться');
        }
        
        // Получение параметров
        $type = $_POST['type'] ?? '';
        $kinopoiskId = $_POST['kinopoiskId'] ?? 0;
        $commentId = $_POST['commentId'] ?? 0;
        
        // Валидация
        if (empty($type) || !in_array($type, ['film', 'serial'])) {
            sendError('Некорректный тип контента');
        }
        
        if (empty($kinopoiskId) || !is_numeric($kinopoiskId)) {
            sendError('Некорректный ID контента');
        }
        
        if (empty($commentId) || !is_numeric($commentId)) {
            sendError('Некорректный ID комментария');
        }
        
        // Удаление комментария
        $result = $commentsManager->deleteComment($type, $kinopoiskId, $commentId);
        
        if ($result) {
            // Возвращаем обновленный список комментариев и статистику
            $comments = $commentsManager->getComments($type, $kinopoiskId);
            $stats = $commentsManager->getStats($type, $kinopoiskId);
            
            echo json_encode([
                'success' => true,
                'comments' => $comments,
                'stats' => $stats
            ]);
        } else {
            sendError('Не удалось удалить комментарий');
        }
        break;
        
    default:
        sendError('Неизвестное действие');
}
?> 