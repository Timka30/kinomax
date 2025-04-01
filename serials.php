<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сериалы</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php require_once "blocks/header.php" ?>

    <div class="main">
    <?php
    require_once "vendor/db.php";
    
    $categories = [
        1 => 'Аниме',
        2 => 'Драмы', 
        3 => 'Комедии',
        4 => 'Мелодрамы',
        5 => 'Мультфильмы',
        6 => 'Ужасы',
        7 => 'Фантастика'
    ];

    foreach ($categories as $id => $name) {
        echo '<div class="stroka1">';
        echo "<a href='category_serials.php?category_id={$id}'><h3 class='zag-films'>{$name}</h3></a>";
        echo '<div class="wrapper">';
        echo '<div class="carousel"><div class="content">';

        $sql = 'SELECT serials.id, serials.name, serials.image 
                FROM serials 
                JOIN category_serials ON serials.category = category_serials.id 
                WHERE category_serials.id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        if ($stmt) {
            $films = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($films as $film) {
                require "blocks/carosel_serials.php";
            }
        }

        echo '</div></div>';
        ?>
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
        <br>
    <?php
    }
    ?>
    </div>
</div>

<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
</body>
</html>