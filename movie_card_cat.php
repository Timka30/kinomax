<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Киномакс'; ?></title>
  
  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/reset.css">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <style>
/* Стили для лоадера */
.loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #030311;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    transition: opacity 0.5s;
    will-change: opacity;
}

.loader {
    width: 48px;
    height: 48px;
    border: 5px solid #FFF;
    border-bottom-color: #007BFF;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    will-change: transform;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.loader-hidden {
    opacity: 0;
    pointer-events: none;
}

.stroka1{
    width: 99%;
}
    .movie-details {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: "Comfortaa", sans-serif;
    will-change: transform;
}
.

/* Стили для заголовка */
.movie-details-content h1 {
    font-size: 32px;
    margin-bottom: 10px;
    color: #333;
}

/* Стили для изображения */
.movie-details img {
    max-width: 300px;
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    will-change: transform;
}

/* Контейнер с текстовой информацией */
.movie-details-content p {
    margin: 5px 0;
    color: white;
    font-size: 16px;
    line-height: 1.5;
}
.content p{
  margin-top: 50px;
}

.movie-details-content strong {
    color: #b3b3b3;
}

/* Контейнер текста справа от изображения */
.movie-details-content {
    display: flex;
    flex-direction: column;
    flex: 1;
}

/* Контейнер для кнопок */
.buttons-container {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Стили для кнопок */
.buttons-container button {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    will-change: background-color;
}

.buttons-container button:hover {
    background-color: #0056b3;
}

/* Стили для плеера */
.kinobox_player {
    width: 1200px;
    margin: 20px auto 0;
    border: 2px solid #ddd;
    border-radius: 8px;
}


.movie-card {
  box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
  width: 300px;
  height: 170px;
  margin: 30px 15px 10px;
  border-radius: 10px;
  display: inline-block;
  overflow: hidden;
  will-change: transform;
}

.movie-header {
  height: 100%;
  width: 100%;
  background-size: cover;
  background-position: center;
  border-radius: 10px;
  will-change: transform;
}

.movie-screen {
  width: 255px;
  height: 220px;
}

.name-movie {
  padding-top: 55px;
  font-family: "Comfortaa", sans-serif;
}

.header-icon {
  width: 64px;
  height: 64px;
  line-height: 180px;
  margin-left: 47.5px;
  opacity: 0.35;
  will-change: opacity;
}

.header-icon-container img {
  margin-top: 20px;
  margin-left: 2.5px;
}

.header-icon:hover {
  border-radius: 10px;
  opacity: 1;
}

.movie-card:hover {
  transform: scale(1.03);
  box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.08);
}

/* Карусель */
.wrapper {
  width: 100%;
  position: relative;
}

.carousel {
  overflow: auto;
  scroll-behavior: smooth;
  display: flex;
  gap: 15px;
  -webkit-overflow-scrolling: touch;
}

.carousel::-webkit-scrollbar {
  height: 0;
}

.content {
  display: grid;
  grid-gap: 7.5px;
  grid-auto-flow: column;
  margin: auto;
  box-sizing: border-box;
  height: 315px;
}

.prev,
.next {
  display: flex;
  justify-content: center;
  align-content: center;
  background: white;
  border: none;
  padding: 8px;
  border-radius: 50%;
  outline: 0;
  cursor: pointer;
  position: absolute;
  will-change: transform;
}

.prev {
  top: 42.5%;
  left: 0;
  transform: translate(50%, -50%);
  display: none;
}

.next {
  top: 42.5%;
  right: 0;
  transform: translate(-50%, -50%);
}

.item {
  flex-shrink: 0;
  width: 350px;
  height: 220px;
}

.prosmotr-films{
    font-size: 20px;
    margin-top: 70px;
    margin-bottom: -15px;
    margin-left: 15px;
    font-family: "Comfortaa", sans-serif;
    font-weight: 400;
    font-style: normal;
}

.menu_list ul{
  display: none;
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
    flex-grow: 1;
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

.stolbik3 img {
    cursor: pointer;
    margin-top: 5px;
    width: 28px;
    height: 28px;
    margin-left: 27.5px;
}

@media (max-width: 767px){
  .movie-details img{
    margin-left: auto;
    margin-right: auto;
  }
  .prev, .next{
    display: none;
    visibility: hidden;
  }
  .movie-details{
    margin-left: 5px;
    margin-right: 5px;
  }
  .movie-details-content h1{
    margin-left: auto;
    margin-right: auto;
    text-align: center;
  }
  .item{
    width: 250px;
    height: 130px;
  }
  .content{
    height: auto;
  }
}
    </style>
</head>

<body>
  <div class="container">
    <?php require_once "blocks/header.php"; ?>
    <div style="margin-top: 50px"></div>
    
    <?php
    require "vendor/db.php";

    // Получаем параметр name из URL
    $movieName = isset($_GET['name']) ? $_GET['name'] : '';

    // Код для определения kinopoiskId по названию фильма
    $kinopoiskId = 0;
    $type = 'film'; // По умолчанию считаем, что это фильм

    // Если название передано, пытаемся найти kinopoiskId через API
    if (!empty($movieName)) {
        $apiKey = '5552e433-2266-4209-ba28-9fdd418c96fd';
        $searchUrl = "https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=" . urlencode($movieName);
        
        $ch = curl_init($searchUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['X-API-KEY: ' . $apiKey],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if (isset($data['films']) && !empty($data['films'])) {
            // Получаем первый результат поиска
            $kinopoiskId = $data['films'][0]['filmId'];
            
            // Определяем тип контента (фильм или сериал) 
            // Можно определить по типу или другим признакам
            if (isset($data['films'][0]['type']) && strtolower($data['films'][0]['type']) === 'tv_series') {
                $type = 'serial';
            }
        }
    }

    if (isset($_GET['name'])) {
      $movie_name = $_GET['name'];
      $apiKey = '604MP2G-JK04AZ2-QAHDEMX-59XQQTP';
      $url = "https://api.kinopoisk.dev/v1.4/movie/search?query=" . urlencode($movie_name);
      
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
          'X-API-KEY: ' . $apiKey,
          'Content-Type: application/json'
        ]
      ]);
      
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      
      if ($httpCode == 200 && ($movie_data = json_decode($response, true)) && !empty($movie_data['docs'])) {
        $movie = $movie_data['docs'][0];
        $kinopoiskId = $movie['id'];
        ?>

        <div class="movie-details" 
             data-content-id="<?= htmlspecialchars($kinopoiskId) ?>" 
             data-content-type="film" 
             data-content-title="<?= htmlspecialchars($movie['name']) ?>" 
             data-content-poster="<?= htmlspecialchars($movie['poster']['url'] ?? '') ?>" 
             data-content-year="<?= htmlspecialchars($movie['year'] ?? '') ?>">
          <img src="<?= htmlspecialchars($movie['poster']['url'] ?? 'path/to/default/image.jpg') ?>" 
               alt="<?= htmlspecialchars($movie['name']) ?>">
          
          <div class="movie-details-content">
            <h1 style="font-size: 30px"><?= htmlspecialchars($movie['name']) ?></h1><br>
            
            <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($movie['description'] ?? 'Описание отсутствует')) ?></p>
            <p><strong>Рейтинг:</strong> <?= htmlspecialchars($movie['rating']['kp'] ?? 'Нет данных') ?></p>
            
            <p><strong>Жанры:</strong> 
              <?= !empty($movie['genres']) ? 
                  implode(', ', array_map(fn($genre) => htmlspecialchars($genre['name']), $movie['genres'])) : 
                  'Нет данных' ?>
            </p>
            
            <p><strong>Страна:</strong>
              <?= !empty($movie['countries']) ? 
                  implode(', ', array_map(fn($country) => htmlspecialchars($country['name']), $movie['countries'])) : 
                  'Нет данных' ?>
            </p>
            
            <p><strong>Год:</strong> <?= htmlspecialchars($movie['year'] ?? 'Нет данных') ?></p>
            <p><strong>Длительность:</strong> <?= htmlspecialchars($movie['movieLength'] ?? 'Нет данных') ?> минут</p>
          </div>

          <div class="stroka1">
            <h3 class="zag-films">Картинки из фильма</h3>
            <div class="wrapper">
              <div class="carousel">
                <div class="content">
                  <?php
                  $googleApiKey = 'AIzaSyDOLRWO1IRJprhhDWvi62CeZh4o5AbwTwo';
                  $searchEngineId = '84cadb501feeb4a8d';
                  $query = urlencode("Кадры из фильма " . $movie['name']);
                  
                  $images = [];
                  for ($startIndex = 1; $startIndex <= 20; $startIndex += 10) {
                    $url = "https://www.googleapis.com/customsearch/v1?q={$query}&cx={$searchEngineId}&searchType=image&imgSize=large&imgType=photo&key={$googleApiKey}&num=10&start={$startIndex}";
                    
                    $response = file_get_contents($url);
                    $data = json_decode($response, true);
                    
                    if (!empty($data['items'])) {
                      $images = array_merge($images, array_column($data['items'], 'link'));
                    }
                  }

                  if ($images) {
                    foreach ($images as $imageUrl) {
                      echo "<div class='movie-card item'>
                              <div class='movie-header' style='background-image: url({$imageUrl});'></div>
                            </div>";
                    }
                  } else {
                    echo "<p>Изображения не найдены.</p>";
                  }
                  ?>
                </div>
              </div>
              <button class="prev nav-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                </svg>
              </button>
              <button class="next nav-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                </svg>
              </button>
            </div>
          </div>

          <h1 class="prosmotr-films">Смотреть фильм</h1>
          <div class="kinobox_player"></div>
          <script src="js/kinobox.min.js"></script>
          <script>
            kbox('.kinobox_player', {search: {kinopoisk: '<?= $kinopoiskId ?>'}});
          </script>
          <?php
          // Если нашли kinopoiskId, подключаем блок комментариев
          if ($kinopoiskId > 0) {
              // Подключаем блок комментариев
              include_once __DIR__ . '/comments/template.php';
          }
          ?>
        </div>

        <?php
      } else {
        echo "Фильм не найден в API.";
      }
    } else {
      echo "Название фильма не передано.";
    }
    ?>

  </div>
  
  <?php require_once "blocks/footer.php"; ?>
  <script src="js/script.js"></script>
  <script src="js/viewing-history.js"></script>
  <script src="js/debug-history.js"></script>
  <script src="js/favorites.js"></script>
</body>
</html>