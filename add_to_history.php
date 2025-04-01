<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка факта запроса
$logFile = __DIR__ . '/history/direct_history_log.txt';
$timestamp = date('Y-m-d H:i:s');
$requestData = "\n=== Новый запрос {$timestamp} ===\n";
$requestData .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$requestData .= "Cookies: " . print_r($_COOKIE, true) . "\n";
$requestData .= "POST: " . print_r($_POST, true) . "\n";
file_put_contents($logFile, $requestData, FILE_APPEND);

// Отправляем заголовок JSON, чтобы клиент не получал ошибки парсинга
header('Content-Type: application/json');

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

// Проверка необходимых параметров
if (!isset($_POST['id']) || !isset($_POST['type']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'Не указаны обязательные параметры']);
    exit;
}

// Нормализация URL постера
$poster = '';
if (isset($_POST['poster']) && !empty($_POST['poster'])) {
    $poster = $_POST['poster'];
    
    // Если постер не полный URL, но не локальный путь к файлу
    if (strpos($poster, 'http') !== 0 && strpos($poster, '/') !== 0) {
        $poster = 'img/' . $poster;
    }
    
    // Логируем информацию о постере
    $posterInfo = "Постер: " . $poster . "\n";
    file_put_contents($logFile, $posterInfo, FILE_APPEND);
}

// Данные для истории
$contentData = [
    'id' => $_POST['id'],
    'type' => $_POST['type'],
    'title' => $_POST['title'],
    'poster' => $poster,
    'year' => isset($_POST['year']) ? $_POST['year'] : '',
    'timestamp' => time(),
    'viewed_at' => date('Y-m-d H:i:s')
];

// Директория для истории
$historyDir = __DIR__ . '/history/users';
if (!is_dir($historyDir)) {
    mkdir($historyDir, 0777, true);
    chmod($historyDir, 0777);
}

// Файл истории
$historyFile = $historyDir . '/' . md5($username) . '.json';

// Получаем текущую историю
$history = [];
if (file_exists($historyFile)) {
    $content = file_get_contents($historyFile);
    $decodedData = json_decode($content, true);
    if (is_array($decodedData)) {
        $history = $decodedData;
    }
}

// Проверяем наличие записи
$existingIndex = -1;
foreach ($history as $index => $item) {
    if ($item['id'] == $contentData['id'] && $item['type'] == $contentData['type']) {
        $existingIndex = $index;
        break;
    }
}

// Обновляем или добавляем запись
if ($existingIndex >= 0) {
    // Обновляем только время, сохраняем оригинальные данные о контенте
    $history[$existingIndex]['timestamp'] = $contentData['timestamp'];
    $history[$existingIndex]['viewed_at'] = $contentData['viewed_at'];
} else {
    $history[] = $contentData;
}

// Сортируем по времени
usort($history, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});

// Ограничиваем 50 записями
if (count($history) > 50) {
    $history = array_slice($history, 0, 50);
}

// Сохраняем историю
$result = file_put_contents($historyFile, json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Логируем результат
$logResult = "Результат записи: " . ($result !== false ? "Успешно ({$result} байт)" : "Ошибка") . "\n";
$logResult .= "Сохраненные данные: " . json_encode($contentData, JSON_UNESCAPED_UNICODE) . "\n";
file_put_contents($logFile, $logResult, FILE_APPEND);

// Отправляем ответ
if ($result !== false) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при записи истории']);
}
?> 