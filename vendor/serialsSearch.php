<?php
// API configuration
const API_KEY = '5552e433-2266-4209-ba28-9fdd418c96fd';
const GOOGLE_API_KEY = 'AIzaSyDOLRWO1IRJprhhDWvi62CeZh4o5AbwTwo';
const GOOGLE_SEARCH_ID = '84cadb501feeb4a8d';

require_once 'db.php';

// Helper functions
function makeApiRequest($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['X-API-KEY: ' . API_KEY]
    ]);
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {
        error_log('cURL Error: ' . curl_error($ch));
        return null;
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

function getMovieDetails($kinopoiskId) {
    return makeApiRequest("https://kinopoiskapiunofficial.tech/api/v2.1/films/{$kinopoiskId}");
}

function searchMovieByTitle($title) {
    return makeApiRequest("https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=" . urlencode($title));
}

function getGoogleImages($movieName, $totalImages = 20) {
    $images = [];
    $query = urlencode("Кадры из сериала " . $movieName);
    
    for ($startIndex = 1; $startIndex <= $totalImages; $startIndex += 10) {
        $url = "https://www.googleapis.com/customsearch/v1?q={$query}&cx=" . GOOGLE_SEARCH_ID . 
               "&searchType=image&imgSize=large&imgType=photo&key=" . GOOGLE_API_KEY . 
               "&num=10&start={$startIndex}";
               
        $data = makeApiRequest($url);
        
        if (!isset($data['items']) || empty($data['items'])) {
            break;
        }
        
        $images = array_merge($images, array_column($data['items'], 'link'));
    }
    
    return $images;
}

function getSerialDetails($id) {
    // Проверяем кэш
    $cacheFile = __DIR__ . '/../cache/serials/' . $id . '.json';
    $cacheTime = 3600; // 1 час кэширования

    // Проверяем существование кэша
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    // Если кэша нет или он устарел, делаем запрос к API
    $apiUrl = "https://api.example.com/serials/" . $id; // Замените на ваш реальный URL API

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        
        // Сохраняем результат в кэш
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0777, true);
        }
        file_put_contents($cacheFile, $response);
        
        return $data;
    }

    return null;
}

// Main logic
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Идентификатор фильма не передан или неверен.");
}

$film_id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT name FROM serials WHERE id = ?');
$stmt->execute([$film_id]);
$film = $stmt->fetch(PDO::FETCH_OBJ);

if (!$film) {
    die("Фильм не найден в базе данных.");
}

$moviesData = searchMovieByTitle($film->name);

if (empty($moviesData['films'])) {
    die("Фильм не найден в API.");
}

$movie = $moviesData['films'][0];
$kinopoiskId = $movie['filmId'];
$movieData = getMovieDetails($movie['kinopoiskId']);
?>

<div class="movie-details"
     data-content-id="<?= htmlspecialchars($kinopoiskId) ?>" 
     data-content-type="serial" 
     data-content-title="<?= htmlspecialchars($movie['nameRu']) ?>" 
     data-content-poster="<?= htmlspecialchars($movie['posterUrl']) ?>" 
     data-content-year="<?= htmlspecialchars($movie['year']) ?>">
    <img src="<?= htmlspecialchars($movie['posterUrl']) ?>" alt="<?= htmlspecialchars($movie['nameRu']) ?>">
    
    <div class="movie-details-content">
        <h1 style="font-size: 30px"><?= htmlspecialchars($movie['nameRu']) ?></h1><br>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($movie['description'])) ?></p>
        <p><strong>Рейтинг:</strong> <?= htmlspecialchars($movie['rating']) ?></p>
        <p><strong>Жанры:</strong> 
            <?= implode(', ', array_map(fn($genre) => htmlspecialchars($genre['genre']), $movie['genres'])) ?>
        </p>
        <p><strong>Страна:</strong> 
            <?= implode(', ', array_map(fn($country) => htmlspecialchars($country['country']), $movie['countries'])) ?>
        </p>
        <p><strong>Год:</strong> <?= htmlspecialchars($movie['year']) ?></p>
        <p><strong>Длительность:</strong> <?= htmlspecialchars($movie['filmLength']) ?> часов</p>
    </div>

    <div class="stroka1">
        <h3 class="zag-films">Картинки из сериала</h3>
        <div class="wrapper">
            <div class="carousel">
                <div class="content">
                    <?php
                    $images = getGoogleImages($movie['nameRu']);
                    if ($images) {
                        foreach ($images as $imageUrl) {
                            echo "<div class='movie-card item'>
                                    <div class='movie-header' style='background-image: url({$imageUrl}); background-size: cover;'>
                                    </div>
                                  </div>";
                        }
                    } else {
                        echo "<p>Изображения не найдены.</p>";
                    }
                    ?>
                </div>
            </div>
            <button class="prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" d="M0 0h24v24H0V0z"/>
                    <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z"/>
                </svg>
            </button>
            <button class="next">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" d="M0 0h24v24H0V0z"/>
                    <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z"/>
                </svg>
            </button>
        </div>
    </div>

    <h1 class="prosmotr-films">Смотреть сериал</h1>
    
    <?php if(isset($_COOKIE['login'])): ?>
    <div class="kinobox_player"></div>
    <script src="./js/kinobox.min.js" async></script>
    <script>
        // Инициализируем плеер как только скрипт загрузится
        document.addEventListener('DOMContentLoaded', function() {
            const initPlayer = function() {
                if (typeof kbox === 'undefined') {
                    setTimeout(initPlayer, 100);
                    return;
                }
                kbox('.kinobox_player', {
                    search: {
                        kinopoisk: '<?php echo $kinopoiskId; ?>'
                    },
                    players: {
                        collaps: {
                            enable: true,
                            position: 1,
                            token: '338bae9bf342d65058b2fe45b7d288'
                        },
                        alloha: {
                            enable: false
                        },
                        kodik: {
                            enable: true,
                            position: 2
                        },
                        bazon: {
                            enable: true,
                            position: 3
                        }
                    },
                    menu: {
                        enable: true,
                        limit: 3
                    },
                    params: {
                        all: {
                            "token": "338bae9bf342d65058b2fe45b7d288",
                            "autoplay": "1",
                            "player": "new"
                        }
                    }
                });
            };
            initPlayer();
        });
    </script>
    <?php else: ?>
    <div class="auth-request">
        <div class="auth-request-content">
            <h3>Для просмотра сериала необходимо авторизоваться</h3>
            <p>Зарегистрированные пользователи могут смотреть сериалы на нашем сайте</p>
            <a href="./auth index.php" class="auth-btn">Войти или зарегистрироваться</a>
        </div>
    </div>
    <style>
        .auth-request {
            background-color: rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 130px 0;
            margin-bottom: 10px;
        }
        .auth-request-content h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #e50914;
        }
        .auth-request-content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .auth-btn {
            display: inline-block;
            background-color: #e50914;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .auth-btn:hover {
            background-color: #b20710;
        }
        @media (max-width: 768px) {
            .auth-request {
                margin: 10px 0px;
            }
        }
    </style>
    <?php endif; ?>

    <?php
// Подключаем блок комментариев
$type = 'serial';
include_once __DIR__ . '/../comments/template.php';
?>

</div>



<!-- Подключение скрипта для истории просмотров -->
<script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/viewing-history.js' : '/js/viewing-history.js' ?>"></script>
<script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/debug-history.js' : '/js/debug-history.js' ?>"></script>
<script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/favorites.js' : '/js/favorites.js' ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      // Код инициализации плеера
      // ... existing code ...
    });
</script>