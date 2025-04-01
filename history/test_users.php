<?php
// Установка максимального уровня отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Тест создания истории просмотров</h1>";

// Проверка наличия имени пользователя в cookie
if (isset($_COOKIE['login']) && !empty($_COOKIE['login'])) {
    $username = $_COOKIE['login'];
    echo "<p>Пользователь: $username</p>";
} else {
    // Используем тестового пользователя
    $username = 'test_user';
    echo "<p>Используем тестового пользователя: $username</p>";
}

// Директория для хранения истории
$usersDir = __DIR__ . '/users';
echo "<p>Директория для хранения: $usersDir</p>";

// Создаем директорию, если она не существует
if (!is_dir($usersDir)) {
    echo "<p>Директория не существует, создаем...</p>";
    $result = mkdir($usersDir, 0777, true);
    echo "<p>Результат создания директории: " . ($result ? 'Успешно' : 'Ошибка') . "</p>";
}

// Проверяем права на запись
echo "<p>Директория существует: " . (is_dir($usersDir) ? 'Да' : 'Нет') . "</p>";
echo "<p>Директория доступна для записи: " . (is_writable($usersDir) ? 'Да' : 'Нет') . "</p>";

// Создаем тестовые данные для истории
$historyData = [
    [
        'id' => '435',
        'type' => 'film',
        'title' => 'Интерстеллар',
        'poster' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1600647/430042eb-ee69-4818-aed0-a312400a26bf/300x450',
        'year' => '2014',
        'timestamp' => time(),
        'viewed_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => '251733',
        'type' => 'serial',
        'title' => 'Во все тяжкие',
        'poster' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/6201401/a2d5bcbb-663e-4469-a34a-f851e7046d22/300x450',
        'year' => '2008',
        'timestamp' => time() - 3600,
        'viewed_at' => date('Y-m-d H:i:s', time() - 3600)
    ]
];

// Путь к файлу истории
$historyFile = $usersDir . '/' . md5($username) . '.json';
echo "<p>Файл истории: $historyFile</p>";

// Преобразуем данные в JSON
$jsonData = json_encode($historyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "<p>JSON данные подготовлены</p>";

// Сохраняем в файл
$writeResult = file_put_contents($historyFile, $jsonData);

if ($writeResult !== false) {
    echo "<p>Данные успешно записаны в файл ($writeResult байт)</p>";
    echo "<p>Содержимое файла:</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($historyFile)) . "</pre>";
} else {
    echo "<p>Ошибка при записи в файл</p>";
    
    // Показываем последнюю ошибку
    if (function_exists('error_get_last')) {
        $error = error_get_last();
        echo "<p>Последняя ошибка: " . htmlspecialchars(print_r($error, true)) . "</p>";
    }
}

// Проверяем наличие файла после записи
echo "<p>Файл существует после записи: " . (file_exists($historyFile) ? 'Да' : 'Нет') . "</p>";

echo "<p><a href='../viewing_history.php'>Перейти на страницу истории просмотров</a></p>";
?> 