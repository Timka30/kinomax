<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Критические стили загружаем сразу -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    
    <!-- Некритические стили загружаем асинхронно -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    </noscript>
    
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
    transition: opacity 0.3s;
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
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 1200px;
    padding: 20px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: "Comfortaa", sans-serif;
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
}

.buttons-container button:hover {
    background-color: #0056b3;
}

/* Стили для плеера */
.kinobox_player {
    width: 100%;
    aspect-ratio: 16/9;
    max-width: 1200px;
    margin: 20px auto;
}

.kinobox_player iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.movie-card {
  box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
  width: 300px; /* Увеличиваем ширину */
  height: 170px; /* Устанавливаем меньшую высоту для горизонтальной ориентации */
  margin: 30px 15px 10px;
  border-radius: 10px;
  display: inline-block;
  overflow: hidden; /* Скрыть лишнее содержимое */
}

.movie-header {
  height: 100%; /* Занимает всю высоту родителя */
  width: 100%; /* Занимает всю ширину родителя */
  background-size: cover;
  background-position: center; /* Центрирование изображений */
  border-radius: 10px;
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
}

.header-icon-container img {
  margin-top: 20px;
  margin-left: 2.5px;
}

.header-icon:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
  opacity: 1;
}

.movie-card:hover {
  box-sizing: border-box;
  -webkit-transform: scale(1.03);
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
  display: flex; /* Используем flex для горизонтального скролла */
  gap: 15px; /* Пробел между элементами */
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
  flex-shrink: 0; /* Запрещаем элементам уменьшаться */
  width: 350px; /* Соответствует ширине карточки */
  height: 220px; /* Соответствует высоте карточки */
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
  .container{
    margin-bottom: 120px;
  }
}
    </style>
</head>
<body>
    <div class="loader-wrapper">
        <span class="loader"></span>
    </div>

    <div class="container">
        <?php
        require_once "blocks/header.php";
        require "vendor/serialsSearch.php";

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $serial_id = (int)$_GET['id'];
            $serial = getSerialDetails($serial_id);

            if ($serial) {
                ?>
                <title><?php echo htmlspecialchars($serial['name']); ?></title>
                <div class="movie-details" 
                     data-content-id="<?= $serial['id'] ?>" 
                     data-content-type="serial" 
                     data-content-title="<?= htmlspecialchars($serial['name_ru'] ?: $serial['name_en']) ?>" 
                     data-content-poster="<?= htmlspecialchars($serial['poster']) ?>" 
                     data-content-year="<?= htmlspecialchars($serial['year']) ?>">
                    <img src="<?php echo htmlspecialchars($serial['image']); ?>" 
                         alt="<?php echo htmlspecialchars($serial['name']); ?>"
                         loading="lazy"
                         onerror="this.src='images/placeholder.jpg'">
                    
                    <div class="movie-details-content">
                        <h1><?php echo htmlspecialchars($serial['name']); ?></h1>
                        <!-- Остальной контент -->
                    </div>
                </div>
                <?php
            } else {
                echo "<p></p>";
            }
        } else {
            echo "<p>Неверный ID сериала.</p>";
        }
        ?>
    </div>

    <?php require_once "blocks/footer.php"; ?>

    <script>
        // Закрываем лоадер после загрузки основного контента
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.querySelector('.loader-wrapper');
            if (loader) {
                // Даем небольшую задержку для загрузки основного контента
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }, 500);
            }
        });
    </script>

    <script src="js/script.js"></script>
    <script src="js/viewing-history.js"></script>
    <script src="js/debug-history.js"></script>
    <script src="js/favorites.js"></script>
</body>
</html>
