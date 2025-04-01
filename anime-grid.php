<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Включаем кэширование на стороне браузера
header('Cache-Control: public, max-age=3600'); // Кэш на 1 час

// Функция транслитерации (такая же как в update.php)
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
    
    $string = strtr($string, $converter);
    $string = preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
    return strtolower($string);
}

// Функция для преобразования названия жанра
function normalizeGenre($genre) {
    // Словарь соответствия множественного и единственного числа
    $genreMap = [
        'Детективы' => 'детектив',
        'Драмы' => 'драма',
        'Комедии' => 'комедия',
        'Боевики' => 'боевик',
        'Триллеры' => 'триллер',
        'Ужасы' => 'ужасы',
        'Фэнтези' => 'фэнтези',
        'Романтика' => 'романтика',
        'Повседневность' => 'повседневность',
        'Приключения' => 'приключения',
        'Мистика' => 'мистика'
        
    ];
    
    // Возвращаем значение для API запроса
    return isset($genreMap[$genre]) ? $genreMap[$genre] : mb_strtolower($genre);
}

// Функция для получения имени файла кэша
function getCacheFileName($genre) {
    // Транслитерируем оригинальное название жанра (во множественном числе)
    return transliterate(mb_strtolower($genre)) . '.json';
}

if (isset($_GET['genre'])) {
    $genre = $_GET['genre'];
    // Нормализуем название жанра для API
    $normalizedGenre = normalizeGenre($genre);
    // Получаем имя файла кэша из оригинального названия
    $cacheFile = 'cache/genres/' . getCacheFileName($genre);
    
    error_log("Reading cache file: " . $cacheFile);
    
    if (file_exists($cacheFile)) {
        error_log("Cache file exists: " . $cacheFile);
        
        $jsonData = file_get_contents($cacheFile);
        if ($jsonData === false) {
            error_log("Failed to read cache file");
            $filtered_data = [];
        } else {
            error_log("Successfully read cache file, size: " . strlen($jsonData) . " bytes");
            $filtered_data = json_decode($jsonData, true);
            if ($filtered_data === null) {
                error_log("Failed to decode JSON: " . json_last_error_msg());
                $filtered_data = [];
            } else {
                error_log("Successfully decoded JSON, found " . count($filtered_data) . " entries");
                
                // Выводим первые несколько записей для проверки
                foreach (array_slice($filtered_data, 0, 3) as $index => $anime) {
                    error_log("Sample anime #{$index}:");
                    error_log("- ID: " . ($anime['id'] ?? 'N/A'));
                    error_log("- Name: " . ($anime['names']['ru'] ?? 'N/A'));
                    error_log("- Poster URL: " . ($anime['posters']['medium']['url'] ?? 'N/A'));
                }
                
                // Сортируем по году
                usort($filtered_data, function($a, $b) {
                    return ($b['season']['year'] ?? 0) - ($a['season']['year'] ?? 0);
                });
            }
        }
    } else {
        // Если кэш-файл не существует, получаем данные из API и фильтруем их
        $apiUrl = "https://api.anilibria.tv/v2/getTitles?limit=100&filter=id,names,posters,type,status,description,season&search=" . urlencode($normalizedGenre);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_USERAGENT => 'AnilibriaClient',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]);
        
        $response = curl_exec($ch);
        
        if(curl_errno($ch)) {
            error_log("Curl error: " . curl_error($ch));
            $filtered_data = [];
        } else {
            $data = json_decode($response, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($data['list'])) {
                $filtered_data = $data['list'];
                
                // Сохраняем отфильтрованные данные в кэш
                if (!empty($filtered_data)) {
                    $cacheDir = dirname($cacheFile);
                    if (!is_dir($cacheDir)) {
                        mkdir($cacheDir, 0777, true);
                    }
                    file_put_contents($cacheFile, json_encode($filtered_data));
                }
            } else {
                error_log("Failed to decode API response: " . json_last_error_msg());
                error_log("API Response: " . substr($response, 0, 1000));
                $filtered_data = [];
            }
        }
        curl_close($ch);
    }
} else {
    // Если жанр не выбран, показываем последние обновления
    $apiUrl = "https://api.anilibria.tv/v2/getUpdates?limit=40&filter=id,names,posters,type,status,description,season";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_USERAGENT => 'AnilibriaClient',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {
        error_log("Curl error: " . curl_error($ch));
        $filtered_data = [];
    } else {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($data['list'])) {
            $filtered_data = $data['list'];
        } else {
            error_log("Failed to decode API response: " . json_last_error_msg());
            error_log("API Response: " . substr($response, 0, 1000));
            $filtered_data = [];
        }
    }
    curl_close($ch);
}

if (!isset($filtered_data)) {
    $filtered_data = [];
}

// Добавляем отладочную информацию
error_log("Selected genre: " . ($genre ?? 'none'));
error_log("Number of items in filtered_data: " . count($filtered_data));

// Предварительная загрузка изображений
$preloadImages = [];
foreach (array_slice($filtered_data, 0, 6) as $anime) {
    error_log("Anime data structure: " . print_r($anime, true));
    $posterUrl = isset($anime['posters']['medium']['url']) ? $anime['posters']['medium']['url'] : 
                (isset($anime['poster']) ? $anime['poster'] : '');
    if (!empty($posterUrl)) {
        // Заменяем домен если он присутствует
        $posterUrl = str_replace('anilibria.tv', 'anilibria.top', $posterUrl);
        if (strpos($posterUrl, 'http') !== 0) {
            $posterUrl = 'https://anilibria.top' . $posterUrl;
        }
        $preloadImages[] = $posterUrl;
    }
}

require_once 'vendor/db.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аниме в жанре <?= htmlspecialchars($_GET['genre'] ?? 'Все жанры') ?></title>
    
    <!-- Предзагрузка критических ресурсов -->
    <?php foreach ($preloadImages as $image): ?>
        <link rel="preload" as="image" href="<?= htmlspecialchars($image) ?>">
    <?php endforeach; ?>
    
    <!-- Подключение стилей -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Подключаем только нужные иконки -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/solid.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/fontawesome.css">
</head>

<body>
<div class="container">
    <?php require "blocks/header.php" ?>
    
    <?php if (isset($_GET['genre'])): ?>
        <?php if (!empty($filtered_data)): ?>
            <div class="genre-header">
                <h1>Аниме в жанре "<?= htmlspecialchars($_GET['genre']) ?>"</h1>
                <p>Найдено: <?= count($filtered_data) ?></p>
            </div>
            
            <div class="grid">
                <?php foreach ($filtered_data as $anime): ?>
                    <div class="grid-item">
                        <?php 
                        error_log("Processing anime ID: " . ($anime['id'] ?? 'N/A'));
                        $animeUrl = "anime_card.php?id=" . urlencode($anime['id']);
                        error_log("Generated URL: " . $animeUrl);
                        ?>
                        <a href="<?= htmlspecialchars($animeUrl) ?>" class="anime-card" onclick="event.preventDefault(); window.location.href='<?= htmlspecialchars($animeUrl) ?>';">
                            <div class="anime-poster">
                                <?php
                                $posterUrl = '';
                                if (!empty($anime['posters']['medium']['url'])) {
                                    $posterUrl = $anime['posters']['medium']['url'];
                                    // Заменяем домен если он присутствует
                                    $posterUrl = str_replace('anilibria.tv', 'anilibria.top', $posterUrl);
                                    if (strpos($posterUrl, 'http') !== 0) {
                                        $posterUrl = 'https://anilibria.top' . $posterUrl;
                                    }
                                }
                                error_log("Poster URL: " . $posterUrl);
                                ?>
                                <?php if (!empty($posterUrl)): ?>
                                    <img src="<?= htmlspecialchars($posterUrl) ?>" 
                                         alt="<?= htmlspecialchars($anime['names']['ru']) ?>"
                                         loading="lazy"
                                         onerror="console.error('Failed to load image:', this.src);">
                                <?php endif; ?>
                            </div>
                            
                            <div class="anime-info">
                                <h3 class="anime-title">
                                    <?= htmlspecialchars($anime['names']['ru']) ?>
                                </h3>
                                
                                <?php if (!empty($anime['type'])): ?>
                                    <p class="anime-type"><?= htmlspecialchars($anime['type']['string']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($anime['season'])): ?>
                                    <p class="anime-season">
                                        <?= htmlspecialchars($anime['season']['string']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($anime['status'])): ?>
                                    <p class="anime-status">
                                        <?= htmlspecialchars($anime['status']['string']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($anime['description'])): ?>
                                    <p class="anime-description">
                                        <?= htmlspecialchars(mb_substr($anime['description'], 0, 200)) . '...' ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h2>Аниме не найдено</h2>
                <p>К сожалению, аниме в жанре "<?= htmlspecialchars($_GET['genre']) ?>" не найдено.</p>
                <a href="index.php" class="back-button">Вернуться на главную</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require "blocks/footer.php" ?>

<style>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
    font-family: 'Comfortaa', cursive;
}

.genre-header {
    text-align: center;
    color: #fff;
    padding: 40px 20px;
}

.genre-header h1 {
    font-size: 32px;
    margin-bottom: 10px;
}

.genre-header p {
    color: #aaa;
    font-size: 16px;
}

.grid-item {
    background: rgba(32, 32, 32, 0.8);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
}

.grid-item:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    background: rgba(40, 40, 40, 0.9);
}

.grid-item:hover .anime-info {
    background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(40, 40, 40, 0.8));
}

.anime-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: #fff;
}

.anime-poster {
    position: relative;
    padding-top: 140%;
    background: #1a1a1a;
}

.anime-poster img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.grid-item:hover .anime-poster img {
    transform: scale(1.05);
}

.no-poster {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
    color: #666;
}

.anime-info {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: background 0.3s ease;
}

.anime-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    line-height: 1.3;
}

.anime-type, .anime-season, .anime-status {
    margin: 0;
    font-size: 13px;
    color: #aaa;
    line-height: 1.2;
}

.anime-description {
    margin: 8px 0 0;
    font-size: 13px;
    color: #aaa;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.watch-button {
    display: block;
    background: #6c5ce7;
    color: #fff;
    text-align: center;
    padding: 8px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s ease;
    margin-top: auto;
}

.watch-button:hover {
    background: #5f4ed6;
}

.no-results, .error-message {
    text-align: center;
    color: #fff;
    padding: 60px 20px;
}

.no-results i, .error-message i {
    font-size: 48px;
    color: #6c5ce7;
    margin-bottom: 20px;
}

.no-results h2, .error-message h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.no-results p, .error-message p {
    color: #aaa;
    margin-bottom: 20px;
}

.back-button {
    display: inline-block;
    background: #6c5ce7;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s ease;
}

.back-button:hover {
    background: #5f4ed6;
}

/* Адаптивность */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        padding: 15px;
    }

    .anime-info {
        padding: 12px;
        gap: 6px;
    }

    .anime-title {
        font-size: 14px;
    }

    .anime-type, .anime-season, .anime-status, .anime-description {
        font-size: 12px;
    }
}
</style>

<script>
// Добавляем обработчик для отладки
document.addEventListener('DOMContentLoaded', function() {
    const animeCards = document.querySelectorAll('.anime-card');
    animeCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Не отменяем стандартное поведение, позволяя браузеру перейти по href
            console.log('Clicked card URL:', this.href);
        });
    });
});

function createAnimeCard(anime) {
    const card = document.createElement('div');
    card.className = 'grid-item';
    
    const link = document.createElement('a');
    link.href = `anime_card.php?id=${encodeURIComponent(anime.id)}`;
    link.className = 'anime-card';
    
    let posterUrl = anime.poster;
    if (posterUrl && !posterUrl.startsWith('http')) {
        posterUrl = 'https://anilibria.top' + posterUrl;
    }

    link.innerHTML = `
        <div class="anime-poster">
            <img src="${posterUrl}" 
                 alt="${anime.name}"
                 loading="lazy"
                 onerror="console.error('Failed to load image:', this.src)">
        </div>
        <div class="anime-info">
            <h3 class="anime-title">${anime.name}</h3>
            <p class="anime-type">${anime.type || ''}</p>
            <p class="anime-season">${anime.year || 'Н/Д'}</p>
            <p class="anime-status">${anime.status || ''}</p>
        </div>
    `;

    card.appendChild(link);
    return card;
}
</script>

</body>
</html>
