<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн-кинотеатр KINOMAX</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    
</head>
<body>
<div class="container">
    <!--Шапка-->
    <?php require_once "blocks/header.php" ?>
    <!-- Конец шапки, начало основного блока -->

<div class="reklama">
    <div class="bez-podpiski">
        <img class="bez-podpiski-img" src="img/bez-podpiski.jpg" alt="">
        <p>Вот так ваше кресло выглядит <b>БЕЗ премиум подписки</b></p>
    </div>

    <div class="podpiska hidden">
        <img src="img/s podpiskoy.png" alt="">
        <p class="txt-one">А вот так ваше кресло будет выглядить вместе с нашей Премиум подпиской!</p>
        <p class="txt-two">Просмотр фильмов и сериалов в 4К, доступ к эксклюзивному контенту и многое другое! Оформи подписку прямо сейчас и получи пробный период на 30 дней!</p>
        <a href="podpiska.php"><button class="glow-on-hover" type="button"><p class="glow-on-hover-p">Купить подписку</p></button></a>
    </div>
</div>
<div class="probel"></div>
    <div class="main">
        <div class="stroka1">
            <h3 class="zag-films">Популярно сейчас</h3>
                <div class="wrapper">
                    <div class="carousel">
                      <div class="content">
                      <?php
              require "vendor/db.php";
              $sql = 'SELECT serials.id, serials.name, serials.image
              FROM serials
              JOIN category_serials ON serials.category = category_serials.id
              WHERE category_serials.id IN (1);';
              $stmt = $pdo->query($sql);
              if ($stmt) {
                  $films = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($films as $film) {
                      require "blocks/carosel_serials.php";
                  }
              } else {
                  echo "Error: Unable to execute query";
              }
            ?>
                      </div>
                    </div>
                    <button class="prev">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                      </svg>
                    </button>
                    <button class="next">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                      </svg>
                    </button>
                </div>
        </div>
        <br>
        <div class="stroka1">
            <h3 class="zag-films">Рекомендуем специально для Вас</h3>
                <div class="wrapper">
                    <div class="carousel">
                      <div class="content">
                      <?php
              require "vendor/db.php";
              $sql = 'SELECT films.id, films.name, films.image
              FROM films
              JOIN category_films ON films.category = category_films.id
              WHERE category_films.id IN (2);';
              $stmt = $pdo->query($sql);
              if ($stmt) {
                  $films = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($films as $film) {
                      require "blocks/carosel.php";
                  }
              } else {
                  echo "Error: Unable to execute query";
              }
            ?>
                      </div>
                    </div>
                    <button class="prev">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                      </svg>
                    </button>
                    <button class="next">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                      </svg>
                    </button>
                </div>
        </div>
        <br>
        <div class="stroka1">
            <h3 class="zag-films">Новинки</h3>
                <div class="wrapper">
                    <div class="carousel">
                      <div class="content">
                      <?php
              require "vendor/db.php";
              $sql = 'SELECT serials.id, serials.name, serials.image
              FROM serials
              JOIN category_serials ON serials.category = category_serials.id
              WHERE category_serials.id IN (7);';
              $stmt = $pdo->query($sql);
              if ($stmt) {
                  $films = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($films as $film) {
                      require "blocks/carosel_serials.php";
                  }
              } else {
                  echo "Error: Unable to execute query";
              }
            ?>
                      </div>
                    </div>
                    <button class="prev">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                      </svg>
                    </button>
                    <button class="next">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                      </svg>
                    </button>
                </div>
        </div>
        <br>
        <div class="stroka1">
            <h3 class="zag-films">Что посмотреть в выходные</h3>
                <div class="wrapper">
                    <div class="carousel">
                      <div class="content">
                      <?php
              require "vendor/db.php";
              $sql = 'SELECT films.id, films.name, films.image
              FROM films
              JOIN category_films ON films.category = category_films.id
              WHERE category_films.id IN (4);';
              $stmt = $pdo->query($sql);
              if ($stmt) {
                  $films = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($films as $film) {
                      require "blocks/carosel.php";
                  }
              } else {
                  echo "Error: Unable to execute query";
              }
            ?>
                      </div>
                    </div>
                    <button class="prev">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                      </svg>
                    </button>
                    <button class="next">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                      </svg>
                    </button>
                </div>
        </div>
    </div>

</div>

<!-- footer -->
<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
</body>
</html>