<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Тест истории просмотров</h1>";

// Включаем класс для работы с историей
require_once 'ViewingHistory.php';

// Проверяем существование директории
$usersDir = __DIR__ . '/users';
echo "<h2>Проверка директории</h2>";
echo "Путь: " . $usersDir . "<br>";
echo "Существует: " . (is_dir($usersDir) ? 'Да' : 'Нет') . "<br>";

if (!is_dir($usersDir)) {
    echo "Пытаемся создать директорию...<br>";
    $result = mkdir($usersDir, 0777, true);
    echo "Результат: " . ($result ? 'Успешно' : 'Ошибка') . "<br>";
}

echo "Доступна для записи: " . (is_writable($usersDir) ? 'Да' : 'Нет') . "<br>";

if (is_dir($usersDir) && !is_writable($usersDir)) {
    echo "Устанавливаем права на запись...<br>";
    $result = chmod($usersDir, 0777);
    echo "Результат: " . ($result ? 'Успешно' : 'Нет') . "<br>";
    echo "Доступна для записи после chmod: " . (is_writable($usersDir) ? 'Да' : 'Нет') . "<br>";
}

// Тестовый пользователь
$testUser = 'test_user';
echo "<h2>Тестовый пользователь: {$testUser}</h2>";

// Создаем экземпляр класса
$history = new ViewingHistory();

// Тестовые данные фильма
$filmData = [
    'id' => '12345',
    'type' => 'film',
    'title' => 'Тестовый фильм',
    'poster' => 'https://example.com/poster.jpg',
    'year' => '2023'
];

echo "<h3>Добавление тестового фильма</h3>";
echo "Данные: <pre>" . print_r($filmData, true) . "</pre>";

// Добавляем запись
$result = $history->addToHistory($testUser, $filmData);
echo "Результат добавления: " . ($result ? 'Успешно' : 'Ошибка') . "<br>";

// Проверяем созданный файл
$filePath = $usersDir . '/' . md5($testUser) . '.json';
echo "<h3>Проверка файла</h3>";
echo "Путь: " . $filePath . "<br>";
echo "Существует: " . (file_exists($filePath) ? 'Да' : 'Нет') . "<br>";

if (file_exists($filePath)) {
    echo "Размер: " . filesize($filePath) . " байт<br>";
    echo "Права: " . substr(sprintf('%o', fileperms($filePath)), -4) . "<br>";
    echo "Содержимое: <pre>" . htmlspecialchars(file_get_contents($filePath)) . "</pre>";
} else {
    echo "Файл не существует, пробуем создать напрямую...<br>";
    $jsonData = json_encode([$filmData], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $result = file_put_contents($filePath, $jsonData);
    echo "Результат прямой записи: " . ($result !== false ? "Успешно ($result байт)" : "Ошибка") . "<br>";
    
    if (file_exists($filePath)) {
        echo "Файл успешно создан, содержимое: <pre>" . htmlspecialchars(file_get_contents($filePath)) . "</pre>";
    } else {
        echo "Файл всё еще не создан, пробуем через fopen...<br>";
        $handle = fopen($filePath, 'w');
        if ($handle) {
            $result = fwrite($handle, $jsonData);
            fclose($handle);
            echo "Результат fwrite: " . ($result !== false ? "Успешно ($result байт)" : "Ошибка") . "<br>";
            
            if (file_exists($filePath)) {
                echo "Файл успешно создан через fopen/fwrite, содержимое: <pre>" . htmlspecialchars(file_get_contents($filePath)) . "</pre>";
            }
        } else {
            echo "Не удалось открыть файл для записи<br>";
        }
    }
}

// Получаем историю
echo "<h3>Получение истории пользователя</h3>";
$userHistory = $history->getUserHistory($testUser);
echo "Количество записей: " . count($userHistory) . "<br>";
echo "Данные истории: <pre>" . print_r($userHistory, true) . "</pre>";

echo "<h2>Информация о сервере</h2>";
echo "PHP версия: " . phpversion() . "<br>";
echo "Пользователь: " . get_current_user() . "<br>";
echo "Путь для временных файлов: " . sys_get_temp_dir() . "<br>";
echo "Текущая директория: " . getcwd() . "<br>";

echo "<h2>Логи</h2>";
$logFile = __DIR__ . '/debug_history.log';
if (file_exists($logFile)) {
    echo "Последние 20 строк лога: <pre>" . htmlspecialchars(implode('', array_slice(file($logFile), -20))) . "</pre>";
} else {
    echo "Лог-файл не найден<br>";
}

echo "<h2>Проверка записи завершена</h2>";
echo "<a href=\"view_history.php\">Просмотреть историю просмотров</a>"; 