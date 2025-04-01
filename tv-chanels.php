<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Жанры аниме</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<style>
    .page-title, .page-description{
        color: #fff;
        font-family: 'Comfortaa', cursive;
    }
    .main{
        margin-top: 40px;
    }
    .page-title {
    font-size: 28px;
    color: #fff;
    margin: 20px 0 10px 20px;
}

.page-description {
    color: #888;
    margin: 0 0 30px 20px;
}

.genres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

.genre-card {
    position: relative;
    height: 340px;
    border-radius: 8px;
    overflow: hidden;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.genre-card:hover {
    transform: translateY(-5px);
}

.genre-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    position: relative;
}

.genre-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0));
    color: #fff;
}

.genre-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .genres-grid {
        grid-template-columns: repeat(2, 1fr); /* Ровно 2 карточки в ряд */
        gap: 10px; /* Уменьшенный отступ между карточками */
        padding: 15px; /* Уменьшенные отступы от краев экрана */
        margin-left: 5px;
        margin-right: 5px;
    }

    .genre-card {
        height: 200px; /* Уменьшенная высота карточки */
        width: 90%; /* Полная ширина в своей колонке */
    }

    .genre-title {
        font-size: 16px; /* Уменьшенный размер шрифта */
    }
    .genre-image {
        background-size: cover;
        background-position: center;
        transform: scale(1.02); /* Небольшое увеличение для предотвращения белых краев */
    }
}
</style>
</head>

<body>
<div class="container">
    <?php require "blocks/header.php" ?>

    <div class="main">
        <h1 class="page-title">Жанры</h1>
        <p class="page-description">Список жанров на любой вкус и цвет</p>
        
        <div class="genres-grid">
            <?php
            // Жанры согласно нашему списку
            $genres = [
                [
                    'title' => 'Боевые искусства',
                    'image' => 'img/anime/boevie-iskusstva.webp'
                ],
                [
                    'title' => 'Вампиры',
                    'image' => 'img/anime/vampiry.webp'
                ],
                [
                    'title' => 'Демоны',
                    'image' => 'img/anime/demoni.webp'
                ],
                [
                    'title' => 'Детективы',
                    'image' => 'img/anime/detektivi.webp'
                ],
                [
                    'title' => 'Драма',
                    'image' => 'img/anime/drami.webp'
                ],
                [
                    'title' => 'Игры',
                    'image' => 'img/anime/igri.webp'
                ],
                [
                    'title' => 'Исторический',
                    'image' => 'img/anime/istoricheskiy.webp'
                ],
                [
                    'title' => 'Комедия',
                    'image' => 'img/anime/orig.webp'
                ],
                [
                    'title' => 'Магия',
                    'image' => 'img/anime/magia.webp'
                ],
                [
                    'title' => 'Меха',
                    'image' => 'img/anime/meha.webp'
                ],
                [
                    'title' => 'Музыка',
                    'image' => 'img/anime/muzika.webp'
                ],
                [
                    'title' => 'Повседневность',
                    'image' => 'img/anime/povsednevnost.webp'
                ],
                [
                    'title' => 'Приключения',
                    'image' => 'img/anime/priklychenia.webp'
                ],
                [
                    'title' => 'Романтика',
                    'image' => 'img/anime/romantika.webp'
                ],
                [
                    'title' => 'Сверхъестественное',
                    'image' => 'img/anime/sverhestestvennoe.webp'
                ],
                [
                    'title' => 'Сёнен',
                    'image' => 'img/anime/seinen.webp'
                ],
                [
                    'title' => 'Спорт',
                    'image' => 'img/anime/sport.webp'
                ],
                [
                    'title' => 'Супер сила',
                    'image' => 'img/anime/super-sila.webp'
                ],
                [
                    'title' => 'Ужасы',
                    'image' => 'img/anime/uzhasi.webp'
                ],
                [
                    'title' => 'Фантастика',
                    'image' => 'img/anime/fantastika.webp'
                ],
                [
                    'title' => 'Школа',
                    'image' => 'img/anime/shkola.png'
                ],
                [
                    'title' => 'Экшен',
                    'image' => 'img/anime/ekshen.webp'
                ]
            ];

            foreach ($genres as $genre) {
                ?>
                <a href="anime-grid.php?genre=<?= urlencode($genre['title']) ?>" class="genre-card">
                    <div class="genre-image" style="background-image: url('<?= htmlspecialchars($genre['image']) ?>')">
                        <div class="genre-overlay">
                            <h3 class="genre-title"><?= htmlspecialchars($genre['title']) ?></h3>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php require "blocks/footer.php" ?>

</body>
</html>