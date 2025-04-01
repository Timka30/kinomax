<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пустая страница</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        .footer{
            margin-top: 366px;
        }
    </style>
</head>
<body>
<div class="container">
    <!--Шапка-->
    <?php require_once "blocks/header.php" ?>
    <!-- Конец шапки, начало основного блока -->

    <div class="empty-div" style="text-align: center; margin-top: 50px;">
        <img src="img/account_image/pedro-dancing-racoon.gif" alt="" style="width: 150px; margin-bottom: 10px; margin-left: -10px;">
        <h2 style="font-size: 40px;">Упс...</h2>
        <p style="font-size: 24px;">К сожалению, на данный момент тут ничего нет</p>
        <p style="font-size: 24px;">Но, не стоит расстраиваться, в ближайшем будущем обязательно появится новый контент</p>
        <a href="account.php"><button class="glow-on-hover" type="button" style="margin-left: -80px;">Вернутся назад</button></a>
    </div>
</div>

<div style="margin-top: 350px"></div>
<!-- footer -->
<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
</body>
</html>