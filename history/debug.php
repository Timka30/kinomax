<?php
// Базовые настройки
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Простая диагностика истории просмотров</h1>";

// Логирование в файл
$logFile = __DIR__ . '/simple_debug.log';
$timestamp = date('Y-m-d H:i:s');
$message = "[{$timestamp}] Новый запрос с IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
file_put_contents($logFile, $message, FILE_APPEND);

// Проверка авторизации
echo "<h2>Статус авторизации</h2>";
if (isset($_COOKIE['login'])) {
    echo "<p>Пользователь: <b>" . htmlspecialchars($_COOKIE['login']) . "</b></p>";
} else {
    echo "<p style='color:red'>Не авторизован</p>";
}

// Проверка директории
$usersDir = __DIR__ . '/users';
echo "<h2>Проверка директории</h2>";
echo "<p>Путь: <b>" . htmlspecialchars($usersDir) . "</b></p>";
echo "<p>Существует: <b>" . (is_dir($usersDir) ? 'Да' : 'Нет') . "</b></p>";
echo "<p>Доступна для записи: <b>" . (is_writable($usersDir) ? 'Да' : 'Нет') . "</b></p>";

// Если директория не существует, создаем её
if (!is_dir($usersDir)) {
    echo "<p>Создаём директорию...</p>";
    if (mkdir($usersDir, 0777, true)) {
        echo "<p style='color:green'>Директория создана</p>";
    } else {
        echo "<p style='color:red'>Ошибка создания директории</p>";
    }
}

// Проверка POST-данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST-данные</h2>";
    echo "<pre>" . htmlspecialchars(print_r($_POST, true)) . "</pre>";
    
    // Если это POST-запрос и пользователь авторизован, создаем тестовую запись
    if (isset($_COOKIE['login']) && !empty($_COOKIE['login'])) {
        $username = $_COOKIE['login'];
        $historyFile = $usersDir . '/' . md5($username) . '.json';
        
        echo "<h2>Тестовый файл истории</h2>";
        echo "<p>Путь: <b>" . htmlspecialchars($historyFile) . "</b></p>";
        
        // Создаем тестовую запись
        $testData = [
            [
                'id' => $_POST['id'] ?? '123',
                'type' => $_POST['type'] ?? 'film',
                'title' => $_POST['title'] ?? 'Тестовый фильм',
                'poster' => $_POST['poster'] ?? '',
                'year' => $_POST['year'] ?? '2023',
                'timestamp' => time(),
                'viewed_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $result = file_put_contents($historyFile, json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($result !== false) {
            echo "<p style='color:green'>Файл истории создан (" . $result . " байт)</p>";
        } else {
            echo "<p style='color:red'>Ошибка создания файла истории</p>";
        }
    }
}

// Ссылки
echo "<h2>Полезные ссылки</h2>";
echo "<ul>";
echo "<li><a href='/project/viewing_history.php'>Просмотр истории</a></li>";
echo "<li><a href='/project/add_to_history.php'>Скрипт добавления (корень)</a></li>";
echo "<li><a href='/project/history/simple_debug.log'>Лог диагностики</a></li>";
echo "</ul>";

// Для AJAX-запросов возвращаем JSON
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'debug выполнен']);
}
?> 