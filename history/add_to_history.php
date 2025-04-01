<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка факта запроса
$requestLogged = false;
$logFile = __DIR__ . '/history_log.txt';

// Функция для логирования
function logMessage($message) {
    global $logFile, $requestLogged;
    $timestamp = date('Y-m-d H:i:s');
    
    if (!$requestLogged) {
        // Логируем запрос только один раз в начале
        $logData = "$timestamp - *** НОВЫЙ ЗАПРОС ***\n";
        $logData .= "$timestamp - Remote IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $logData .= "$timestamp - User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $logData .= "$timestamp - Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
        $logData .= "$timestamp - POST данные: " . print_r($_POST, true) . "\n";
        $logData .= "$timestamp - COOKIE данные: " . print_r($_COOKIE, true) . "\n";
        $requestLogged = true;
        file_put_contents($logFile, $logData, FILE_APPEND);
    }
    
    // Логируем переданное сообщение
    $logMessage = "$timestamp - $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

logMessage("Обработчик запущен");

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logMessage("Ошибка: Неверный метод запроса (" . $_SERVER['REQUEST_METHOD'] . ")");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

// Проверка авторизации
if (!isset($_COOKIE['login']) || empty($_COOKIE['login'])) {
    logMessage("Ошибка: Пользователь не авторизован");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

// Получаем имя пользователя
$username = $_COOKIE['login'];
logMessage("Пользователь: $username");

// Проверяем наличие необходимых параметров
if (!isset($_POST['id']) || !isset($_POST['type']) || !isset($_POST['title'])) {
    logMessage("Ошибка: Не указаны обязательные параметры");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Не указаны обязательные параметры']);
    exit;
}

// Получаем параметры
$contentData = [
    'id' => trim($_POST['id']),
    'type' => trim($_POST['type']),
    'title' => trim($_POST['title']),
    'poster' => isset($_POST['poster']) ? trim($_POST['poster']) : '',
    'year' => isset($_POST['year']) ? trim($_POST['year']) : '',
    'timestamp' => time(),
    'viewed_at' => date('Y-m-d H:i:s')
];

// Дополнительная проверка данных
if (empty($contentData['id']) || empty($contentData['type']) || empty($contentData['title'])) {
    logMessage("Ошибка: Пустые обязательные данные после обработки");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Пустые обязательные данные']);
    exit;
}

logMessage("Данные контента: id=" . $contentData['id'] . ", type=" . $contentData['type'] . ", title=" . $contentData['title']);

// Путь к файлу истории
$usersDir = __DIR__ . '/users';
$historyFile = $usersDir . '/' . md5($username) . '.json';

try {
    // Создаем директорию, если она не существует
    if (!is_dir($usersDir)) {
        logMessage("Создаем директорию для истории: $usersDir");
        if (!mkdir($usersDir, 0777, true)) {
            logMessage("Ошибка: Не удалось создать директорию $usersDir");
            throw new Exception('Не удалось создать директорию для хранения истории');
        }
        chmod($usersDir, 0777); // Устанавливаем права на запись
    }
    
    logMessage("Директория существует: " . (is_dir($usersDir) ? 'Да' : 'Нет'));
    logMessage("Директория доступна для записи: " . (is_writable($usersDir) ? 'Да' : 'Нет'));
    
    // Получаем текущую историю
    $history = [];
    if (file_exists($historyFile)) {
        logMessage("Чтение существующего файла истории");
        $historyContent = file_get_contents($historyFile);
        $history = json_decode($historyContent, true);
        if (!is_array($history)) {
            logMessage("Ошибка при декодировании JSON, создаем новый массив");
            $history = [];
        }
    }
    
    // Проверяем, есть ли уже такой контент в истории
    $existingIndex = -1;
    foreach ($history as $index => $item) {
        if ($item['id'] == $contentData['id'] && $item['type'] == $contentData['type']) {
            $existingIndex = $index;
            break;
        }
    }
    
    // Если контент уже есть в истории - обновляем время просмотра
    if ($existingIndex >= 0) {
        logMessage("Обновление существующей записи (индекс: $existingIndex)");
        $history[$existingIndex]['timestamp'] = $contentData['timestamp'];
        $history[$existingIndex]['viewed_at'] = $contentData['viewed_at'];
    } else {
        // Добавляем новую запись
        logMessage("Добавление новой записи");
        $history[] = $contentData;
    }
    
    // Сортируем по времени (новые сверху)
    usort($history, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    // Ограничиваем историю последними 50 записями
    if (count($history) > 50) {
        logMessage("Ограничиваем историю до 50 записей");
        $history = array_slice($history, 0, 50);
    }
    
    // Сохраняем историю
    $jsonData = json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $writeResult = file_put_contents($historyFile, $jsonData);
    
    if ($writeResult === false) {
        logMessage("Ошибка при записи файла истории");
        throw new Exception('Не удалось записать файл истории');
    }
    
    logMessage("Файл истории успешно записан: $writeResult байт");
    logMessage("Файл существует после записи: " . (file_exists($historyFile) ? 'Да' : 'Нет'));
    
    // Отправляем успешный ответ
    $response = ['success' => true];
    
} catch (Exception $e) {
    logMessage("Исключение: " . $e->getMessage());
    logMessage("Стек вызовов: " . $e->getTraceAsString());
    $response = ['success' => false, 'message' => $e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);
logMessage("Ответ отправлен: " . json_encode($response));
logMessage("Обработка запроса завершена");
exit;
?> 