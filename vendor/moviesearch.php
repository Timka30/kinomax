<?php
// Включаем ключ API и адрес API для Kinopoisk
$apiKey = '5552e433-2266-4209-ba28-9fdd418c96fd';

// Подключение к базе данных
require_once 'db.php';

// Простое файловое кэширование
function getCachedData($key, $ttl = 3600) {
    $cacheFile = sys_get_temp_dir() . '/movie_cache_' . md5($key);
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return unserialize(file_get_contents($cacheFile));
    }
    return false;
}

function setCachedData($key, $data) {
    $cacheFile = sys_get_temp_dir() . '/movie_cache_' . md5($key);
    file_put_contents($cacheFile, serialize($data));
}

// Оптимизированная функция для получения информации о фильме
function getMovieDetails($kinopoiskId) {
    global $apiKey;
    
    $cacheKey = "movie_details_" . $kinopoiskId;
    $cachedData = getCachedData($cacheKey);
    if ($cachedData !== false) {
        return $cachedData;
    }

    $url = "https://kinopoiskapiunofficial.tech/api/v2.1/films/{$kinopoiskId}";
    
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['X-API-KEY: ' . $apiKey],
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    setCachedData($cacheKey, $data);
    
    return $data;
}

// Оптимизированная функция поиска
function searchMovieByTitle($title) {
    global $apiKey;
    
    $cacheKey = "movie_search_" . md5($title);
    $cachedData = getCachedData($cacheKey);
    if ($cachedData !== false) {
        return $cachedData;
    }

    $ch = curl_init("https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=" . urlencode($title));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['X-API-KEY: ' . $apiKey],
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    setCachedData($cacheKey, $data);
    
    return $data;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $film_id = (int)$_GET['id'];

    $stmt = $pdo->prepare('SELECT name FROM films WHERE id = ?');
    $stmt->execute([$film_id]);
    $film = $stmt->fetch(PDO::FETCH_OBJ);

    if ($film) {
        $moviesData = searchMovieByTitle($film->name);

        if (!empty($moviesData['films'])) {
            $movie = $moviesData['films'][0];
            $kinopoiskId = $movie['filmId'];
            $movieData = getMovieDetails($movie['kinopoiskId']);
            ?>
            <div class="movie-details"
                 data-content-id="<?= htmlspecialchars($kinopoiskId) ?>" 
                 data-content-type="film" 
                 data-content-title="<?= htmlspecialchars($movie['nameRu']) ?>" 
                 data-content-poster="<?= htmlspecialchars($movie['posterUrl']) ?>" 
                 data-content-year="<?= htmlspecialchars($movie['year']) ?>">
                <img loading="lazy" decoding="async" width="300" height="450" src="<?php echo htmlspecialchars($movie['posterUrl']); ?>" alt="<?php echo htmlspecialchars($movie['nameRu']); ?>">

                <div class="movie-details-content">
                    <h1 style="font-size: 30px"><?php echo htmlspecialchars($movie['nameRu']); ?></h1><br>
                    <p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                    <p><strong>Рейтинг:</strong> <?php echo htmlspecialchars($movie['rating']); ?></p>
                    <p><strong>Жанры:</strong> 
                        <?php echo implode(', ', array_map(function($genre) {
                            return htmlspecialchars($genre['genre']);
                        }, $movie['genres'])); ?>
                    </p>
                    <p><strong>Страна:</strong> 
                        <?php echo implode(', ', array_map(function($country) {
                            return htmlspecialchars($country['country']);
                        }, $movie['countries'])); ?>
                    </p>
                    <p><strong>Год:</strong> <?php echo htmlspecialchars($movie['year']); ?></p>
                    <p><strong>Длительность:</strong> <?php echo htmlspecialchars($movie['filmLength']); ?> часов</p>
                </div>

                <div class="stroka1">
                    <h3 class="zag-films">Картинки из фильма</h3>
                    <div class="wrapper">
                        <div class="carousel">
                            <div class="content">
                            <?php
                            $apiKey = 'AIzaSyDOLRWO1IRJprhhDWvi62CeZh4o5AbwTwo';
                            $searchEngineId = '84cadb501feeb4a8d';
                            $query = urlencode("Кадры из фильма " . $movie['nameRu']);
                            
                            $cacheKey = "movie_images_" . md5($movie['nameRu']);
                            $images = getCachedData($cacheKey);
                            
                            if ($images === false) {
                                $images = [];
                                $url = "https://www.googleapis.com/customsearch/v1?q={$query}&cx={$searchEngineId}&searchType=image&imgSize=large&imgType=photo&key={$apiKey}&num=10";
                                
                                $ch = curl_init($url);
                                curl_setopt_array($ch, [
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_CONNECTTIMEOUT => 3,
                                    CURLOPT_TIMEOUT => 5,
                                    CURLOPT_SSL_VERIFYPEER => false
                                ]);
                                $response = curl_exec($ch);
                                curl_close($ch);
                                
                                $data = json_decode($response, true);
                                
                                if (isset($data['items'])) {
                                    foreach ($data['items'] as $item) {
                                        $images[] = $item['link'];
                                    }
                                    setCachedData($cacheKey, $images);
                                }
                            }

                            if (!empty($images)) {
                                foreach ($images as $imageUrl) {
                                    echo "<div class='movie-card item'>
                                            <div class='movie-header' style='background-image: url({$imageUrl}); background-size: cover;' loading='lazy'>
                                            </div>
                                          </div>";
                                }
                            } else {
                                echo "<p>Изображения не найдены.</p>";
                            }
                            ?>
                            </div>
                        </div>
                        <button class="prev" aria-label="Previous">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                            </svg>
                        </button>
                        <button class="next" aria-label="Next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <h1 class="prosmotr-films">Смотреть фильм</h1>

                <?php if(isset($_COOKIE['login'])): ?>
                <div class="kinobox_player"></div>
                <script src="./js/kinobox.min.js" defer></script>
                <script>
                    window.addEventListener('load', function() {
                        kbox('.kinobox_player', {search: {kinopoisk: '<?php echo $kinopoiskId; ?>'}});
                    });
                </script>
                <?php else: ?>
                <div class="auth-request">
                    <div class="auth-request-content">
                        <h3>Для просмотра фильма необходимо авторизоваться</h3>
                        <p>Зарегистрированные пользователи могут смотреть фильмы на нашем сайте</p>
                        <a href="../auth index.php" class="auth-btn">Войти или зарегистрироваться</a>
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
            $type = 'film';
            include_once __DIR__ . '/../comments/template.php';
            ?>
            
            </div>
            
         
            
            <!-- Подключение скрипта для истории просмотров -->
            <script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/viewing-history.js' : '/js/viewing-history.js' ?>"></script>
            <script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/debug-history.js' : '/js/debug-history.js' ?>"></script>
            <script src="<?= strpos($_SERVER['REQUEST_URI'], '/project/') !== false ? '/project/js/favorites.js' : '/js/favorites.js' ?>"></script>
            <?php
        } else {
            echo "Фильм не найден в API.";
        }
    } else {
        echo "Фильм не найден в базе данных.";
    }
} else {
    echo "Идентификатор фильма не передан или неверен.";
}
?>

<script src="./js/kinobox.min.js" defer></script>

<script>
    // Делаем проверку доступности скрипта истории просмотров
    document.addEventListener('DOMContentLoaded', function() {
      // Получаем данные о фильме из URL
      var urlParams = new URLSearchParams(window.location.search);
      var movieName = urlParams.get('name');
      
      if (movieName && document.querySelector('.movie-details')) {
        // Асинхронно загружаем скрипт, чтобы не блокировать загрузку страницы
        var script = document.createElement('script');
        script.src = 'js/viewing-history.js';
        script.async = true;
        document.body.appendChild(script);
        
        console.log('Скрипт истории просмотров загружен для фильма: ' + movieName);
      }
    });
  </script>
