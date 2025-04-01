<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="stylesheet" href="css/style-media.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <?php require_once "blocks/header.php" ?>

    <div class="reg-action">
        <h2 class="h2-account">Личный кабинет пользователя - <?= htmlspecialchars($_COOKIE['login']) ?></h2>


        <div class="stroka-one">
            <div class="prod-posm">
                <a href="viewing_history.php">
                    <h3>Продолжить просмотр</h3>
                    <img src="img/account_image/cd_record.png" alt="Продолжить просмотр" style="width: 80px; height: auto; margin-left: 260px;">
                    <p>Смотреть</p>
                </a>
            </div>
            <div class="div-media">
                <div class="my-kino">
                    <a href="empty.php">
                        <h3>Моё кино</h3>
                        <p>Купленный и доступный в подписке контент</p>
                        <p style="margin-top: 70px;">Смотреть</p>
                    </a>
                </div>
                <div class="platezhi">
                    <a href="podpiska.php">
                        <h3>Платежи и подписки</h3>
                        <p style="margin-top: 52px;">Смотреть</p>
                    </a>
                </div>
            </div>
            <div class="sertivication">
                <a href="empty.php">
                    <h3>Мои сертификаты</h3>
                    <img src="img/account_image/sertivication.png" alt="Сертификаты" style="width: 80px; height: auto; margin-left: 260px;">
                    <p style="margin-top: -10px;">Смотреть</p>
                </a>
            </div>
        </div>

        <br>

        <div class="stroka-two">
            <div class="div-media">
                <div class="use-promo">
                    <a href="empty.php">
                        <h3>Активировать промокод</h3>
                        <p style="margin-top: 52.5px;">Перейти</p>
                    </a>
                </div>
                <div class="bonus-prog">
                    <a href="empty.php">
                        <h3>Бонусные программы</h3>
                        <p style="margin-top: 52px;">Подробнее</p>
                    </a>
                </div>
            </div>
            <div class="izbrannoe">
                <a href="favorites.php">
                    <h3>Избранное</h3>
                    <img src="img/account_image/izbrannoe.png" alt="Избранное" style="width: 80px; height: auto; margin-left: 260px;">
                    <p style="margin-top: -10px;">Смотреть</p>
                </a>
            </div>
            <div class="div-media">
                <div class="my-device">
                    <a href="user_reviews.php">
                        <h3>Мои отзывы</h3>
                        <p style="margin-top: 70px;">Перейти</p>
                    </a>
                </div>
                <div class="settings">
                    <a href="empty.php">
                        <h3>Настройки</h3>
                        <p style="margin-top: 70px;">Перейти</p>
                    </a>
                </div>
            </div>
        </div>

        <br>

        <div class="stroka-one" id="stroka-3">
            <div class="prod-posm">
                <a href="empty.php">
                    <h3>Активировать ТВ-онлайн</h3>
                    <img src="img/account_image/tv.png" alt="ТВ-онлайн" style="width: 80px; height: auto; margin-left: 260px;">
                    <p>Подробнее</p>
                </a>
            </div>
            <div class="div-media">
                <div class="my-kino">
                    <a href="empty.php">
                        <h3>Сообщения</h3>
                        <p style="margin-top: 70px;">Смотреть</p>
                    </a>
                </div>
                <div class="platezhi">
                    <a href="empty.php">
                        <h3>Напоминания</h3>
                        <p style="margin-top: 70px;">Смотреть</p>
                    </a>
                </div>
            </div>
            <div class="sertivication">
                <a href="index.php">
                    <h3>Смотри сейчас</h3>
                    <img src="img/account_image/smotr-seychas.png" alt="Смотреть сейчас" style="width: 80px; height: auto; margin-left: 260px;">
                    <p style="margin-top: -10px;">Смотреть</p>
                </a>
            </div>
        </div>
    </div>

    <div class="kirpich">
        <div class="btn-s-adm">
            <a class="btn-acc" href="vendor/logout.php">
                <button class="glow-on-hover" type="button">Выйти с аккаунта</button>
            </a>
        </div>
        <br>
        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1): ?>
            <div class="btn-s-adm">
                <a class="btn-acc" href="admin.php">
                    <button class="glow-on-hover" type="button">Войти в админ-панель</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<br>
<div class="footer-account" style="margin-top: 100px"></div>

<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
</body>
</html>