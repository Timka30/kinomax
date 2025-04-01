<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(600);

if (php_sapi_name() !== 'cli') {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Обновление кэша аниме</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/scroll.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
        <style>
            .update-container {
                max-width: 800px;
                margin: 100px auto;
                padding: 20px;
                background: rgba(0, 0, 0, 0.5);
                border-radius: 10px;
                text-align: center;
            }
            .update-button {
                display: inline-block;
                background: #6c5ce7;
                color: #fff;
                padding: 15px 30px;
                border-radius: 6px;
                text-decoration: none;
                transition: background 0.2s ease;
                border: none;
                cursor: pointer;
                font-family: 'Comfortaa', cursive;
                font-size: 16px;
                margin: 20px 0;
            }
            .update-button:hover {
                background: #5f4ed6;
            }
            .loader-container {
                display: none;
                margin-top: 30px;
            }
            .loader {
                width: 100px;
                height: 100px;
                border: 5px solid #f3f3f3;
                border-top: 5px solid #6c5ce7;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            }
            .progress-container {
                width: 100%;
                max-width: 300px;
                background: #2d3436;
                height: 20px;
                border-radius: 10px;
                overflow: hidden;
                margin: 0 auto 10px;
            }
            .progress-bar {
                width: 0%;
                height: 100%;
                background: #6c5ce7;
                transition: width 0.3s ease;
            }
            .progress-text {
                color: #fff;
                font-size: 14px;
                margin-bottom: 20px;
            }
            .results {
                margin-top: 20px;
                max-height: 300px;
                overflow-y: auto;
                text-align: left;
                padding: 10px;
            }
            .success { color: #4cd137; margin: 5px 0; }
            .error { color: #e84118; margin: 5px 0; }
            .info { color: #7f8fa6; margin: 20px 0; }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            html {
    height: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100%;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
}

.container {
    flex-grow: 1; /* Основной контент растягивает оставшееся пространство */
}

.footer {
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    background-color: #030311e6;
    color: white;
    position: relative;
    bottom: 0;
    
}

.oglavlenie-footer {
    font-size: 24px;
    margin-top: 20px;
    margin-bottom: 15px;
    font-weight: bold;
}

.stolbik1, .stolbik2, .stolbik3 {
    font-size: 16px;
    margin: 0 30px;
    margin-bottom: 20px;
}

.stolbik1 a, .stolbik2 a {
    text-decoration: none;
    color: white;
}

.stolbik1 p, .stolbik2 p {
    margin-top: 7.5px;
}
.search-nav{
    cursor: pointer;
}
.stolbik3 img {
    cursor: pointer;
    margin-top: 5px;
    width: 28px;
    height: 28px;
    margin-left: 27.5px;
}

  #menu-toggle:checked ~ .menu li {
    border: 1px solid #333;
    height: 2em;
    padding: 0.5em;
    transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
  }
  @media (max-width: 480px) {
    form{
        margin-top: 50px;
        width: 90%;
    }
    .footer {
        margin-top: 0px;
    }
    .stolbik3{
        margin-top: 30px;
    }
    #menu-toggle:checked ~ .menu li {
    border: 1px solid #333;
    height: 2.5em;
    padding: 0em;
    transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
  }
}
        </style>
    </head>
    <body>
    <div class="container">
        <?php require "blocks/header.php" ?>
    <h3><a href="admin.php" style="text-decoration: none; color: white; font-size: 20px; margin-bottom: 20px; margin-top: 50px;">Вернутся назад</a></h3>
        <div class="update-container">
            <h1>Обновление кэша аниме</h1>
            <p>Нажмите кнопку ниже, чтобы обновить кэш всех жанров аниме</p>
            
            <?php if (!isset($_POST['update'])): ?>
                <form method="POST">
                    <button type="submit" name="update" class="update-button">Обновить кэш</button>
                </form>
            <?php endif; ?>

            <?php if (isset($_POST['update'])): ?>
                <div class="loader-container" id="loader" style="display: block;">
                    <div class="loader"></div>
                    <div class="progress-container">
                        <div class="progress-bar" id="progress"></div>
                    </div>
                    <div class="progress-text" id="progress-text">Подготовка к обновлению кэша...</div>
                    <div class="progress-text" id="current-action">Ожидание...</div>
                </div>
                <div class="results" id="results" style="display: block;">
                <?php
                // Отключаем буферизацию вывода
                if (ob_get_level()) ob_end_clean();
                ob_implicit_flush(true);

                $genres = [
                    'Боевые искусства', 'Вампиры', 'Демоны', 'Детектив', 'Драма',
                    'Игры', 'Исторический', 'Комедия', 'Магия', 'Меха', 'Музыка',
                    'Повседневность', 'Приключения', 'Романтика', 'Сверхъестественное',
                    'Сёнен', 'Спорт', 'Супер сила', 'Ужасы', 'Фантастика', 'Школа', 'Экшен'
                ];

                function updateCache($genres) {
                    set_time_limit(1200);
                    
                    // Создаем только один запрос для получения всех тайтлов
                    $ch = curl_init();
                    
                    // Используем самый простой запрос без фильтров
                    $url = "https://api.anilibria.tv/v3/title?id=9001";
                    
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => 'gzip, deflate',
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_HTTPHEADER => [
                            'Accept: application/json',
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                        ],
                        CURLOPT_VERBOSE => true,
                        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP, // Разрешаем оба протокола
                        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP
                    ]);

                    // Создаем файл для записи подробной информации
                    $verbose = fopen('php://temp', 'w+');
                    curl_setopt($ch, CURLOPT_STDERR, $verbose);

                    // Добавим больше отладочной информации
                    echo "<div class='info'>Testing API connection...</div>";
                    echo "<div class='info'>URL: " . htmlspecialchars($url) . "</div>";
                    
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $curlInfo = curl_getinfo($ch);
                    
                    // Получаем подробную информацию
                    rewind($verbose);
                    $verboseLog = stream_get_contents($verbose);
                    
                    echo "<div class='info'>HTTP Code: " . $httpCode . "</div>";
                    echo "<div class='info'>Response: " . htmlspecialchars($response) . "</div>";
                    echo "<div class='info'>CURL Info: <pre>" . htmlspecialchars(print_r($curlInfo, true)) . "</pre></div>";
                    echo "<div class='info'>Verbose Log: <pre>" . htmlspecialchars($verboseLog) . "</pre></div>";

                    if ($response === false) {
                        echo "<div class='error'>CURL Error: " . curl_error($ch) . "</div>";
                    }

                    curl_close($ch);

                    // Если получили успешный ответ, продолжаем с обработкой жанров
                    if ($httpCode === 200) {
                        $data = json_decode($response, true);
                        if ($data) {
                            echo "<div class='success'>API connection successful!</div>";
                            
                            // Здесь будет код обработки жанров
                            // ...
                        }
                    }

                    // Временно возвращаем пустой результат
                    return [];
                }

                // Добавляем функцию транслитерации
                function transliterate($string) {
                    $converter = array(
                        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
                        'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
                        'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
                        'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
                        'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
                        'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
                        'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
                        
                        'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
                        'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
                        'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
                        'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
                        'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
                        'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
                        'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
                        ' ' => '_'
                    );
                    
                    // Преобразуем строку в транслит
                    $string = strtr($string, $converter);
                    
                    // Убираем все лишние символы
                    $string = preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
                    
                    // Приводим к нижнему регистру
                    return strtolower($string);
                }

                // Создаем директорию кэша, если её нет
                if (!is_dir(__DIR__)) {
                    mkdir(__DIR__, 0777, true);
                }

                $totalGenres = count($genres);
                $totalSuccess = 0;
                $totalErrors = 0;

                $results = updateCache($genres);

                foreach ($results as $result) {
                    $progress = round(($result['genre'] === 'error' ? $totalGenres : $totalGenres - $totalErrors) / $totalGenres * 100);
                    echo "<script>
                        document.getElementById('progress').style.width = '{$progress}%';
                        document.getElementById('progress-text').textContent = 'Прогресс: {$progress}%';
                        document.getElementById('current-action').textContent = 'Обработка: {$result['genre']}';
                    </script>";
                    echo ($result['status'] === 'success' ? 
                          "<div class='success'>✓ {$result['genre']}: {$result['count']} anime cached</div>" : 
                          "<div class='error'>✗ {$result['genre']}: Error {$result['code']}" . 
                          (!empty($result['error']) ? " ({$result['error']})" : "") . "</div>");
                    
                    if ($result['status'] === 'success') {
                        $totalSuccess++;
                    } else {
                        $totalErrors++;
                    }
                    
                    // Принудительно отправляем вывод
                    flush();
                    
                    // Уменьшаем задержку между запросами
                    usleep(500000); // 0.5 секунды
                }

                // Сохраняем время последнего обновления
                $updateTime = date('Y-m-d H:i:s');
                file_put_contents(__DIR__ . '/last_update.txt', $updateTime);

                // Выводим итоговую статистику
                echo "<div class='info'>Обновление завершено в $updateTime<br>Успешно: $totalSuccess<br>Ошибок: $totalErrors</div>";
                ?>
                </div>
            <?php endif; ?>
        </div>


    </div>
    
    <?php require "blocks/footer.php" ?>
    <script src="js/script.js"></script>
    </body>
    </html>
    <?php
} 