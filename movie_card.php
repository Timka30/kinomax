<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Подробная информация о фильме">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preload" href="css/style.css" as="style">
    <link rel="preload" href="css/reset.css" as="style">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
<!-- Лоадер -->
<div class="loader-wrapper">
    <span class="loader"></span>
</div>

<div class="container">
    <!-- Header -->
    <?php require_once "blocks/header.php"; ?>
    <div style="margin-top: 50px"></div>
    <?php
    require "vendor/db.php";
    
    // Проверка ID фильма
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $film_id = (int)$_GET['id'];
        
        // Получение данных о фильме
        $stmt = $pdo->prepare('SELECT * FROM films WHERE id = ?');
        $stmt->execute([$film_id]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($movie) {
            // Данные есть, подключаем поиск по KinopoiskAPI с фильмом из БД
            require "vendor/moviesearch.php";
        } else {
            echo "<p>Фильм не найден в базе данных.</p>";
        }
    } else {
        echo "<p>Некорректный идентификатор фильма.</p>";
    }
    ?>
</div><!-- Закрываем div.container -->

<?php require_once "blocks/footer.php"; ?>

<script>
    window.addEventListener('load', () => {
        document.querySelector('.loader-wrapper').style.display = 'none';
    });

    function injectAdBlocker(iframe) {
        try {
            const iframeWindow = iframe.contentWindow;
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            const adBlockerScript = `
                (function() {
                    const fakePromise = Promise.resolve({
                        success: true,
                        skip: true,
                        error: false
                    });

                    window.Advertising = () => fakePromise;
                    window.VastNext = () => fakePromise;
                    window.VastCheckNext = () => fakePromise;
                    window.LoadXml = () => fakePromise;
                    window.LoadXmlNoCredentials = () => fakePromise;
                    window.VastRemoveAndPlay = () => fakePromise;
                    window.Actions = {
                        Play: () => fakePromise,
                        VastNext: () => fakePromise,
                        VastError: () => fakePromise,
                        VastCheckNext: () => fakePromise
                    };

                    const XHR = window.XMLHttpRequest;
                    window.XMLHttpRequest = function() {
                        const xhr = new XHR();
                        const originalOpen = xhr.open;
                        xhr.open = function(method, url) {
                            if (url.includes('ads') || 
                                url.includes('vast') || 
                                url.includes('banner') ||
                                url.includes('buzzoola') ||
                                url.includes('traffer') ||
                                url.includes('franecki') ||
                                url.includes('dfn-network')) {
                                xhr.abort();
                                return;
                            }
                            return originalOpen.apply(this, arguments);
                        };
                        return xhr;
                    };

                    const originalFetch = window.fetch;
                    window.fetch = function(url, options) {
                        if (typeof url === 'string' && (
                            url.includes('ads') || 
                            url.includes('vast') || 
                            url.includes('banner') ||
                            url.includes('buzzoola') ||
                            url.includes('traffer') ||
                            url.includes('franecki') ||
                            url.includes('dfn-network'))) {
                            return Promise.resolve(new Response('', {
                                status: 200,
                                headers: { 'Content-Type': 'text/xml' }
                            }));
                        }
                        return originalFetch.apply(this, arguments);
                    };

                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            mutation.addedNodes.forEach((node) => {
                                if (node.tagName === 'DIV' && 
                                    (node.className || '').includes('ads')) {
                                    node.remove();
                                }
                            });
                        });
                    });

                    observer.observe(document.body, {
                        childList: true,
                        subtree: true
                    });
                })();
            `;

            const script = iframeDoc.createElement('script');
            script.textContent = adBlockerScript;
            iframeDoc.head.appendChild(script);

        } catch (error) {}
    }

    const iframeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.tagName === 'IFRAME') {
                    node.addEventListener('load', () => injectAdBlocker(node));
                }
            });
        });
    });

    iframeObserver.observe(document.documentElement, {
        childList: true,
        subtree: true
    });
</script>
<script src="js/script.js"></script>
<script src="js/viewing-history.js"></script>
<script src="js/debug-history.js"></script>
<script src="js/favorites.js"></script>
<script src="js/kinobox.min.js"></script>
<script>
    window.addEventListener('load', function() {
        kbox('.kinobox_player', {
            search: {
                kinopoisk: '<?php echo $kinopoiskId; ?>'
            },
            players: {
                collaps: {
                    enable: true,
                    position: 1,
                    token: '338bae9bf342d65058b2fe45b7d288'
                },
                alloha: {
                    enable: false
                },
                kodik: {
                    enable: true,
                    position: 2
                },
                bazon: {
                    enable: true,
                    position: 3
                }
            },
            menu: {
                enable: true,
                limit: 3
            },
            params: {
                all: {
                    "token": "338bae9bf342d65058b2fe45b7d288",
                    "autoplay": "1",
                    "player": "new"
                }
            }
        });
    });
</script>
<style>
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
</style>
</body>
</html>
