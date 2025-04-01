<head>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <!-- Современные браузеры -->
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    
    <!-- Цвет темы для мобильных браузеров -->
    <meta name="theme-color" content="#09092c">

    <!-- Блокировка нежелательных элементов -->
    <style>
        /* Блокировка элементов Яндекс.Переводчика */
        .tr-popup,
        #tr-popup,
        [class*="tr-popup"],
        /* Блокировка рекламного скрипта */
        script[src*="retagro.com"],
        #V4LnBocA {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            position: absolute !important;
            left: -9999px !important;
            top: -9999px !important;
            width: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            z-index: -9999 !important;
        }
    </style>
</head>

<div class="header">
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="logo"></a>
    </div>
    <div class="nav">
            <p class="header-glav"><a href="index.php">Главная</a></p>
            <p><a href="tv-chanels.php">Аниме</a></p>   
            <p><a href="serials.php">Сериалы</a></p>
            <p><a href="films.php">Фильмы</a></p>
    </div>
    <div class="search" onclick="openSearchModal()">
        <button class="search-btn" type="submit">
            <img class="search-svg" src="img/search.svg" alt="">
        </button>
    </div>

    <div id="searchModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSearchModal()">&times;</span>
            <h2>Поиск</h2>
            <br>
            <input type="text" id="searchInput" placeholder="Введите название фильма или сериала" oninput="fetchResults()">
            <div id="searchResults"></div>
        </div>
    </div>

    <div class="nav-burger">
        <input id="menu-toggle" type="checkbox" />
        <label class='menu-button-container' for="menu-toggle">
            <div class='menu-button'></div>
        </label>
        <ul class="menu">
            <li class="search-nav" onclick="openSearchModal()"><p>Поиск</p></li>
            <li><p class="header-glav"><a href="index.php">Главная</a></p></li>
            <li><p><a href="tv-chanels.php">Аниме</a></p> </li>
            <li><p><a href="serials.php">Сериалы</a></p></li>
            <li><p><a href="films.php">Фильмы</a></p></li>
                <?php
                if (isset($_COOKIE['login']))
                    echo '<li class="auth-profile">
                                <a href="account.php">
                                    <p alt="Личный кабинет" style="margin-top: -4px; text-align: center;">Личный кабинет</p>
                                </a>
                            </li>
                            <li class="auth-profile2">
                                <a href="vendor/logout.php">
                                    <p alt="Выйти">Выйти</p>
                                </a>
                            </li>';
                else
                    echo '<li class="auth-li">
                                <a href="auth index.php">
                                    <p alt="Войти">Войти</p>
                                </a>
                            </li>';
                ?>
        </ul>
    </div>
    <?php
        if(isset($_COOKIE['login']))
        echo '<div class="auth">
                <a href="account.php">
                    <img src="img/user.svg" alt="Личный кабинет">
                </a>
                <a href="vendor/logout.php">
                    <p alt="Выйти" style="margin-left: 15px;">Выйти</p>
                </a>
            </div>';
        else
        echo '<div class="auth">
                <a href="auth index.php">
                    <img src="img/login.svg" alt="Войти">
                    <p alt="Войти">Войти</p>
                </a>
            </div>';
    ?>
</div>