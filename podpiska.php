<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платная подписка</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Poppins:wght@300;500;600&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        .form-podpiska{
            margin-top: 100px;
            width: auto;
            height: auto;
            margin-right: auto;
        }
        .main-block-podpiska{
            text-align: center;
        }
        .main-block-podpiska-p{
            margin-left: auto;
            margin-right: auto;
            width: 300px;
            margin-top: 10px;
        }
        .main-block-podpiska h1{
            font-size: 40px;
        }
        .kupit{
            width: 350px;
            margin-left: -175px;
            margin-right: auto;
        }
        .form-podpiska img{
            width: 500px;
            height: auto;
        }
        .block-podpiska-1{
            margin-top: 300px;
            margin-left: 50px;
            margin-bottom: 100px;
        }
        .block-podpiska-1 h3{
            font-size: 28px;
        }
        .block-podpiska-1 p{
            font-size: 20px;
            width: 350px;
            text-align: center;
            margin-top: 10px;
            margin-left: 55px;
        }
        .block-podpiska-1 img{
            position: absolute;
            margin-left: 600px;
            margin-top: -250px;
        }
        .block-podpiska-2{
            margin-top: 300px;
            margin-left: 50px;
            margin-bottom: 100px;
        }
        .block-podpiska-2 h3{
            margin-left: 750px;
            font-size: 28px;
        }
        .block-podpiska-2 p{
            font-size: 20px;
            width: 350px;
            text-align: center;
            margin-top: 10px;
            margin-left: 717.5px;
        }
        .block-podpiska-2 img{
            position: absolute;
            margin-top: -120px;
        }
        .block-podpiska-3{
            margin-top: 300px;
            margin-left: 50px;
            margin-bottom: 100px;
        }
        .block-podpiska-3 h3{
            margin-left: 75px;
            font-size: 28px;
        }
        .block-podpiska-3 p{
            font-size: 20px;
            width: 350px;
            text-align: center;
            margin-top: 10px;
            margin-left: 10px;
        }
        .block-podpiska-3 img{
            position: absolute;
            margin-left: 600px;
            margin-top: -250px;
        }
        .block-podpiska-4{
            margin-top: 300px;
            margin-left: 50px;
            margin-bottom: 100px;
        }
        .block-podpiska-4 h3{
            margin-left: 720px;
            font-size: 28px;
        }
        .block-podpiska-4 p{
            font-size: 20px;
            width: 350px;
            text-align: center;
            margin-top: 10px;
            margin-left: 660px;
        }
        .block-podpiska-4 img{
            position: absolute;
            margin-left: -75px;
            margin-top: -170px;
        }
        .rekl-pod{
            margin-top: 200px;
            text-align: center;
        }
        .rekl-pod p{
            position: absolute;
            margin-top: 70px;
            margin-left: 522.5px;
        }
        .otmena-podpiski{
            font-size: 12px;
            margin-left: 10px;
            margin-right: auto;
            margin-top: 70px; 
            color: gray;
        }
@media (max-width: 768px) {
    .block-podpiska-1 img,
    .block-podpiska-2 img,
    .block-podpiska-3 img,
    .block-podpiska-4 img {
        position: static;
        margin: 20px auto;
        display: block;
        width: 100%;
        max-width: 300px; /* Ограничение максимальной ширины изображения */
        height: auto;
    }

    .block-podpiska-1 p,
    .block-podpiska-2 p,
    .block-podpiska-3 p,
    .block-podpiska-4 p {
        text-align: left;
        margin: 10px 20px;
    }

    .block-podpiska-2 h3,
    .block-podpiska-4 h3 {
        margin-left: 20px;
        text-align: left;
    }

    .block-podpiska-1,
    .block-podpiska-2,
    .block-podpiska-3,
    .block-podpiska-4 {
        margin: 20px;
    }
}

@media (max-width: 480px) {
    .form-podpiska img {
        width: 100%;
        max-width: 250px; /* Для ещё меньших экранов */
        height: auto;
    }

    .main-block-podpiska h1 {
        font-size: 24px;
    }

    .main-block-podpiska-p {
        width: 90%;
    }

    .kupit {
        width: 90%;
        margin: 10px auto;
    }
}

    </style>
</head>
<body>
<div class="container">
    <?php require_once "blocks/header.php" ?>

    <div class="form-podpiska">
        <div class="main-block-podpiska">
            <h1>Подписка Киномакс</h1>
            <p class="main-block-podpiska-p">Подключай и смотри новые фильмы и сериалы со всего мира в отличном качестве и без рекламы</p>
            <a href="auth index.php"><button class="glow-on-hover kupit" type="button">Попробовать 30 дней бесплатно</button></a>
            <p class="otmena-podpiski">Отменить можно в любой момент</p>
        </div>

        <div class="block-podpiska block-podpiska-1">
            <h3>Одна подписка для всей семьи и друзей</h3>
            <p>Создай персональное пространство для каждого и подключай до пяти устройств. И всё это в одной подписке!</p>
            <img src="img/block-podpiska-1.png" alt="Семейная подписка">
        </div>

        <div class="block-podpiska block-podpiska-2">
            <img src="img/block-podpiska-2.png" alt="Качество 4К">
            <h3>Максимальное качество</h3>
            <p>Постоянно обновляемый каталог фильмов, сериалов и мультфильмов в 4К. А ещё, система, которая позволяет смотреть без неприятных сбоев и остановок.</p>
        </div>

        <div class="block-podpiska block-podpiska-3">
            <h3>Просмотр оффлайн</h3>
            <p>Смотри даже там, где нет интернета. Скачивай любимые фильмы и сериалы прямо на телефон или плашнет.</p>
            <img src="img/block-podpiska-3.png" alt="Оффлайн просмотр">
        </div>

        <div class="block-podpiska block-podpiska-4">
            <img src="img/block-podpiska-4.png" alt="Без рекламы">
            <h3>Никакой рекламы</h3>
            <p>Цени каждый момент. Ни один рекламный ролик не прервёт просмотр интересного медиа-контента!</p>
        </div>

        <div class="rekl-pod">
            <a href="auth index.php"><button class="glow-on-hover kupit" type="button">Попробовать 30 дней бесплатно</button></a>
            <p class="otmena-podpiski">Отменить можно в любой момент</p>
        </div>
    </div>
</div>

<div style="margin-top: 350px"></div>

<?php require_once "blocks/footer.php" ?>
<script src="js/script.js"></script>
</body>
</html>