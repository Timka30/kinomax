<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Отправляем заголовок JSON
header('Content-Type: application/json');

// Лог-файл для отладки
$logFile = __DIR__ . '/favorites/favorites_log.txt';
$timestamp = date('Y-m-d H:i:s');
$requestData = "\n=== Новый запрос {$timestamp} ===\n";
$requestData .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$requestData .= "Cookies: " . print_r($_COOKIE, true) . "\n";
$requestData .= "POST: " . print_r($_POST, true) . "\n";
file_put_contents($logFile, $requestData, FILE_APPEND);

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

// Проверка авторизации
if (!isset($_COOKIE['login']) || empty($_COOKIE['login'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

// Получение имени пользователя
$username = $_COOKIE['login'];

// Проверка только статуса избранного
if (isset($_POST['check_only']) && isset($_POST['id']) && isset($_POST['type'])) {
    $contentId = $_POST['id'];
    $contentType = $_POST['type'];
    
    $favorites = [];
    if (file_exists($favoritesFile)) {
        $content = file_get_contents($favoritesFile);
        $decodedData = json_decode($content, true);
        if (is_array($decodedData)) {
            $favorites = $decodedData;
        }
    }
    
    $is_favorite = false;
    foreach ($favorites as $item) {
        if ($item['id'] == $contentId && $item['type'] == $contentType) {
            $is_favorite = true;
            break;
        }
    }
    
    echo json_encode(['is_favorite' => $is_favorite]);
    exit;
}

// Проверка необходимых параметров
if (!isset($_POST['id']) || !isset($_POST['type']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'Не указаны обязательные параметры']);
    exit;
}

// Действие (добавить/удалить)
$action = isset($_POST['action']) ? $_POST['action'] : 'add';

// Данные для избранного
$contentData = [
    'id' => $_POST['id'],
    'type' => $_POST['type'],
    'title' => $_POST['title'],
    'poster' => isset($_POST['poster']) ? $_POST['poster'] : '',
    'year' => isset($_POST['year']) ? $_POST['year'] : '',
    'added_timestamp' => time(),
    'added_at' => date('Y-m-d H:i:s')
];

// Нормализация URL постера
if (!empty($contentData['poster'])) {
    $poster = $contentData['poster'];
    
    // Если постер не полный URL, но не локальный путь к файлу
    if (strpos($poster, 'http') !== 0 && strpos($poster, '/') !== 0) {
        $poster = 'img/' . $poster;
    }
    
    $contentData['poster'] = $poster;
}

// Директория для избранного
$favoritesDir = __DIR__ . '/favorites/users';
if (!is_dir($favoritesDir)) {
    mkdir($favoritesDir, 0777, true);
    chmod($favoritesDir, 0777);
}

// Файл избранного
$favoritesFile = $favoritesDir . '/' . md5($username) . '.json';

// Получаем текущий список избранного
$favorites = [];
if (file_exists($favoritesFile)) {
    $content = file_get_contents($favoritesFile);
    $decodedData = json_decode($content, true);
    if (is_array($decodedData)) {
        $favorites = $decodedData;
    }
}

// Проверяем наличие записи
$existingIndex = -1;
foreach ($favorites as $index => $item) {
    if ($item['id'] == $contentData['id'] && $item['type'] == $contentData['type']) {
        $existingIndex = $index;
        break;
    }
}

// Добавление или удаление из избранного
if ($action === 'add') {
    if ($existingIndex >= 0) {
        // Уже в избранном, обновляем время добавления
        $favorites[$existingIndex]['added_timestamp'] = $contentData['added_timestamp'];
        $favorites[$existingIndex]['added_at'] = $contentData['added_at'];
        $result = ['success' => true, 'message' => 'Обновлено в избранном', 'status' => 'updated'];
    } else {
        // Добавляем в избранное
        $favorites[] = $contentData;
        $result = ['success' => true, 'message' => 'Добавлено в избранное', 'status' => 'added'];
    }
} else {
    if ($existingIndex >= 0) {
        // Удаляем из избранного
        unset($favorites[$existingIndex]);
        $favorites = array_values($favorites); // Переиндексируем массив
        $result = ['success' => true, 'message' => 'Удалено из избранного', 'status' => 'removed'];
    } else {
        // Не было в избранном
        $result = ['success' => false, 'message' => 'Нет в избранном', 'status' => 'not_found'];
    }
}

// Сохраняем избранное
$saveResult = file_put_contents($favoritesFile, json_encode($favorites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Логируем результат
$logResult = "Действие: " . $action . "\n";
$logResult .= "Результат: " . ($saveResult !== false ? "Успешно ({$saveResult} байт)" : "Ошибка") . "\n";
$logResult .= "Статус: " . $result['status'] . "\n";
file_put_contents($logFile, $logResult, FILE_APPEND);

// Отправляем ответ
if ($saveResult !== false) {
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при записи избранного']);
}
?> 