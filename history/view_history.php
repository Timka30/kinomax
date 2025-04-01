<?php
session_start();
require_once 'ViewingHistory.php';

// Подключаем лог для отладки
if (!function_exists('logMessage')) {
    function logMessage($message) {
        $logFile = __DIR__ . '/view_history_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "$timestamp - $message\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}

// Записываем в лог информацию о доступе
logMessage("Страница истории просмотров запрошена");
logMessage("IP: " . $_SERVER['REMOTE_ADDR']);
logMessage("User Agent: " . $_SERVER['HTTP_USER_AGENT']);
logMessage("Cookie: " . print_r($_COOKIE, true));

// Более надежная проверка авторизации
if (!isset($_COOKIE['login']) || empty($_COOKIE['login'])) {
    logMessage("Ошибка: Пользователь не авторизован");
    header('Location: ../auth index.php');
    exit;
}

// Получаем имя пользователя
$username = $_COOKIE['login'];
logMessage("Пользователь: $username");

// Информация о директории
$usersDir = __DIR__ . '/users';
logMessage("Директория истории: $usersDir");
logMessage("Директория существует: " . (is_dir($usersDir) ? 'Да' : 'Нет'));
logMessage("Права на директорию: " . (is_dir($usersDir) ? substr(sprintf('%o', fileperms($usersDir)), -4) : 'Н/Д'));
logMessage("Директория доступна для записи: " . (is_dir($usersDir) ? (is_writable($usersDir) ? 'Да' : 'Нет') : 'Н/Д'));

// Инициализируем класс для работы с историей
try {
    $viewingHistory = new ViewingHistory();
    logMessage("Класс ViewingHistory успешно инициализирован");
    
    // Получаем историю просмотров
    $history = $viewingHistory->getUserHistory($username);
    logMessage("Получено записей истории: " . count($history));
    
    // Обработка удаления записи
    if (isset($_POST['delete_item'])) {
        $contentId = $_POST['content_id'];
        $contentType = $_POST['content_type'];
        
        logMessage("Запрос на удаление записи: id=$contentId, type=$contentType");
        
        if ($viewingHistory->removeFromHistory($username, $contentId, $contentType)) {
            logMessage("Запись успешно удалена");
            header('Location: view_history.php');
            exit;
        } else {
            logMessage("Ошибка при удалении записи");
        }
    }
    
    // Обработка очистки истории
    if (isset($_POST['clear_history'])) {
        logMessage("Запрос на очистку всей истории");
        
        if ($viewingHistory->clearHistory($username)) {
            logMessage("История успешно очищена");
            $history = []; // Обновляем переменную истории
            header('Location: view_history.php');
            exit;
        } else {
            logMessage("Ошибка при очистке истории");
        }
    }
} catch (Exception $e) {
    logMessage("Исключение: " . $e->getMessage());
    logMessage("Стек вызовов: " . $e->getTraceAsString());
    
    // Создаем пустой массив истории в случае ошибки
    $history = [];
}

// Функция для форматирования даты
function formatDate($timestamp) {
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'только что';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' ' . plural($mins, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' ' . plural($hours, 'час', 'часа', 'часов') . ' назад';
    } elseif ($diff < 172800) {
        return 'вчера';
    } else {
        return date('d.m.Y', $timestamp);
    }
}

// Функция для склонения слов
function plural($number, $one, $two, $many) {
    $mod10 = $number % 10;
    $mod100 = $number % 100;
    
    if ($mod10 == 1 && $mod100 != 11) {
        return $one;
    } elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
        return $two;
    } else {
        return $many;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История просмотров - <?= htmlspecialchars($username) ?></title>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/scroll.css">
    <link rel="stylesheet" href="../css/style-media.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .viewing-history {
            width: 100%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            font-family: "Comfortaa", sans-serif;
        }
        
        .history-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #fff;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .history-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .clear-history-btn {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: "Comfortaa", sans-serif;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(229, 9, 20, 0.2);
        }
        
        .clear-history-btn:hover {
            background-color: #b20710;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(229, 9, 20, 0.25);
        }
        
        .no-history {
            text-align: center;
            padding: 80px 0;
            color: #aaa;
            font-style: italic;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin: 40px 0;
        }
        
        .no-history p {
            font-size: 18px;
            line-height: 1.6;
        }
        
        .no-history i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #555;
            display: block;
        }
        
        .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .history-item {
            background-color: rgba(20, 20, 30, 0.4);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .history-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .history-poster {
            width: 100%;
            height: 360px;
            position: relative;
            overflow: hidden;
        }
        
        .history-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .history-item:hover .history-poster img {
            transform: scale(1.05);
        }
        
        .history-poster::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, 
                rgba(0, 0, 0, 0) 50%, 
                rgba(0, 0, 0, 0.7) 80%, 
                rgba(0, 0, 0, 0.9) 100%);
            z-index: 1;
        }
        
        .history-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            color: white;
            z-index: 2;
        }
        
        .history-title-item {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
        
        .history-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-top: 10px;
        }
        
        .history-year {
            color: #ddd;
            background-color: rgba(0, 0, 0, 0.4);
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .history-date {
            color: #ccc;
            font-style: italic;
        }
        
        .type-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: rgba(229, 9, 20, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .history-actions-item {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 3;
            display: flex;
            gap: 12px;
        }
        
        .btn-delete, .btn-view {
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .btn-delete:hover {
            color: #fff;
            background-color: rgba(229, 9, 20, 0.8);
            transform: scale(1.1);
        }
        
        .btn-view:hover {
            color: #fff;
            background-color: rgba(76, 175, 80, 0.8);
            transform: scale(1.1);
        }
        
        .back-to-account {
            margin-bottom: 30px;
        }
        
        .back-to-account a {
            color: #ccc;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .back-to-account a:hover {
            color: #fff;
            background-color: rgba(0, 0, 0, 0.4);
            transform: translateX(-5px);
        }
        
        .back-to-account i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }
        
        .back-to-account a:hover i {
            transform: translateX(-3px);
        }
        
        /* Анимации */
        .history-item {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .history-item:nth-child(1) { animation-delay: 0.1s; }
        .history-item:nth-child(2) { animation-delay: 0.2s; }
        .history-item:nth-child(3) { animation-delay: 0.3s; }
        .history-item:nth-child(4) { animation-delay: 0.4s; }
        .history-item:nth-child(5) { animation-delay: 0.5s; }
        
        @media (max-width: 768px) {
            .history-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 15px;
            }
            
            .history-poster {
                height: 250px;
            }
            
            .history-info {
                padding: 15px;
            }
            
            .history-title-item {
                font-size: 16px;
            }
            
            .type-badge {
                top: 10px;
                left: 10px;
                padding: 4px 8px;
            }
            
            .history-actions-item {
                top: 10px;
                right: 10px;
            }
            
            .btn-delete, .btn-view {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .history-title {
                font-size: 22px;
            }
            
            .history-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
            }
            
            .history-poster {
                height: 200px;
            }
            
            .history-info {
                padding: 10px;
            }
            
            .history-title-item {
                font-size: 14px;
                -webkit-line-clamp: 1;
            }
            
            .history-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .btn-delete, .btn-view {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
            
            .type-badge {
                font-size: 10px;
                padding: 3px 6px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php require_once "../blocks/header.php" ?>
    
    <div class="viewing-history">
        <div class="back-to-account">
            <a href="../account.php"><i class="fas fa-arrow-left"></i> Вернуться в личный кабинет</a>
        </div>
        
        <h1 class="history-title">История просмотров</h1>
        
        <?php if (!empty($history)): ?>
            <div class="history-actions">
                <form method="post" onsubmit="return confirm('Вы уверены, что хотите очистить всю историю просмотров?');">
                    <button type="submit" name="clear_history" class="clear-history-btn">
                        <i class="fas fa-trash-alt"></i> Очистить историю
                    </button>
                </form>
            </div>
            
            <div class="history-grid">
                <?php foreach ($history as $index => $item): ?>
                    <div class="history-item" style="animation-delay: <?= 0.1 * ($index % 10) ?>s;">
                        <div class="type-badge">
                            <?= $item['type'] === 'film' ? 'Фильм' : 'Сериал' ?>
                        </div>
                        
                        <div class="history-actions-item">
                            <a href="<?= $item['type'] === 'film' 
                                ? '../movie_card.php?id=' . htmlspecialchars($item['id']) 
                                : '../movie_card_serials.php?id=' . htmlspecialchars($item['id']) ?>" 
                               class="btn-view" title="Смотреть">
                                <i class="fas fa-play"></i>
                            </a>
                            <form method="post" style="display: inline;" onsubmit="return confirm('Удалить запись из истории просмотров?');">
                                <input type="hidden" name="content_id" value="<?= htmlspecialchars($item['id']) ?>">
                                <input type="hidden" name="content_type" value="<?= htmlspecialchars($item['type']) ?>">
                                <button type="submit" name="delete_item" class="btn-delete" title="Удалить из истории">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="history-poster">
                            <img src="<?= !empty($item['poster']) ? htmlspecialchars($item['poster']) : '../img/no-poster.jpg' ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
                        </div>
                        
                        <div class="history-info">
                            <h3 class="history-title-item"><?= htmlspecialchars($item['title']) ?></h3>
                            <div class="history-meta">
                                <div class="history-year"><?= !empty($item['year']) ? htmlspecialchars($item['year']) : 'Год неизвестен' ?></div>
                                <div class="history-date" title="<?= htmlspecialchars($item['viewed_at']) ?>">
                                    <?= formatDate($item['timestamp']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-history">
                <i class="fas fa-film"></i>
                <p>История просмотров пуста.</p>
                <p>Начните смотреть фильмы и сериалы, чтобы они отображались здесь!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer-account" style="margin-top: 100px"></div>

</div>

    <?php require_once "../blocks/footer.php" ?>
    
<script src="../js/script.js"></script>
</body>
</html> 