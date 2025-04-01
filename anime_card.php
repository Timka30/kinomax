<?php
ob_start(); // Начинаем буферизацию вывода
session_start(); // Добавляем сессию для сообщений об ошибках

require "vendor/db.php";
require "vendor/animesearch.php";

// Добавим отладку
error_log("Received GET parameters: " . print_r($_GET, true));

// Проверяем ID аниме
if (!isset($_GET['id']) || empty($_GET['id'])) {
    error_log("No anime ID provided in request");
    $_SESSION['error'] = "ID аниме не указан";
    header('Location: tv-chanels.php');
    ob_end_flush();
    exit();
}

$anime_id = (int)$_GET['id'];
error_log("Processing anime_id: " . $anime_id);

if ($anime_id <= 0) {
    error_log("Invalid anime ID: " . $anime_id);
    $_SESSION['error'] = "Некорректный ID аниме";
    header('Location: tv-chanels.php');
    ob_end_flush();
    exit();
}

$anime = getAnimeDetails($anime_id);
error_log("Raw anime details: " . print_r($anime, true));

// Проверяем структуру ответа
if (empty($anime)) {
    error_log("API returned empty response for ID: " . $anime_id);
    $_SESSION['error'] = "Информация об аниме не найдена";
    header('Location: tv-chanels.php');
    ob_end_flush();
    exit();
}

// Проверяем наличие обязательных полей
if (!isset($anime['names']) || !isset($anime['names']['ru'])) {
    error_log("Missing required fields in API response for ID " . $anime_id . ": " . print_r($anime, true));
    $_SESSION['error'] = "Неверный формат данных аниме";
    header('Location: tv-chanels.php');
    ob_end_flush();
    exit();
}

error_log("Successfully loaded anime data for ID " . $anime_id);

error_log("Anime data prepared for template: " . print_r($anime, true));
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($anime['names']['ru']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
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
.anime-player {
    width: 100%;
    height: auto;
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
iframe{
    max-width: 1200px;
    width: 100%;
    height: 650px;
    border: none;
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
    .container{
        margin-bottom: 150px;
    }
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
  .anime-player {
    width: 100%;
    height: auto;
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
iframe{
    max-width: 1200px;
    width: 100%;
    height: 200px;
    border: none;
    border-radius: 8px;
}
}
    </style>
    </style>
</head>
<body>
    <!-- Лоадер -->
    <div class="loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>

    <div class="container">
        <?php require "blocks/header.php"; ?>
        <div style="margin-top: 50px"></div>
        <?php require "vendor/anime_content.php"; ?>
    </div>
    <?php require "blocks/footer.php"; ?>
    <!-- Скрипты -->
    <script>
        window.addEventListener('load', () => {
            document.querySelector('.loader-wrapper').style.display = 'none';
        });
    </script>
</body>
</html>
<?php
ob_end_flush(); // Завершаем буферизацию и отправляем вывод
?>
