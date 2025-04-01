<?php
require "vendor/db.php";

// Получаем ID категории из параметра URL с дефолтным значением 1
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT) ?: 1;

// Загружаем название категории из таблицы category_films
$sqlCategory = 'SELECT type FROM category_serials WHERE id = :category_id';
$stmtCategory = $pdo->prepare($sqlCategory);
$stmtCategory->execute(['category_id' => $category_id]);
$category = $stmtCategory->fetch(PDO::FETCH_OBJ);

// Маппинг категорий на жанры API
const GENRE_MAPPING = [
    'Аниме' => 'аниме',
    'Драмы' => 'драма', 
    'Комедии' => 'комедия',
    'Мелодрамы' => 'мелодрама',
    'Мультфильмы' => 'мультфильм',
    'Ужасы' => 'ужасы',
    'Фантастика' => 'фантастика',
];

$categoryName = GENRE_MAPPING[$category->type] ?? 'сериалы';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category->type) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <style>
        .category-page {
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .films-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .film-card {
            background-color: #2c2c2c;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .film-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .film-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .film-info {
            padding: 15px;
            text-align: center;
        }

        .film-info h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #fff;
        }

        .film-info p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #bbb;
        }

        .film-overview {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 10px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .film-card:hover .film-overview {
            transform: translateY(0);
        }

        .loader {
            display: none;
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid #ff6f61;
            border-top: 4px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .load-more {
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .load-more:hover:not(:disabled) {
            background-color: #ff3b2f;
            transform: scale(1.05);
        }

        .load-more:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .error-message {
            color: #ff3b2f;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <?php require "blocks/header.php" ?>
    <div class="category-page">
        <h1><?= htmlspecialchars($category->type) ?></h1>

        <div class="loader" id="loader"></div>
        <div class="error-message" id="error-message"></div>
        <div class="films-grid" id="films-grid"></div>
        <button class="load-more" id="load-more">Показать ещё</button>
    </div>
</div>

<?php require "blocks/footer.php" ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_KEY = '604MP2G-JK04AZ2-QAHDEMX-59XQQTP';
    const elements = {
        filmsGrid: document.getElementById('films-grid'),
        loadMoreBtn: document.getElementById('load-more'),
        loader: document.getElementById('loader'),
        errorMsg: document.getElementById('error-message')
    };

    let currentPage = 1;
    let isLoading = false;

    async function fetchFilms(page = 1) {
        const isAnime = '<?= $category->type ?>' === 'Аниме';
        const isCartoon = '<?= $category->type ?>' === 'Мультфильмы';
        let url;
        
        if (isAnime) {
            url = `https://api.kinopoisk.dev/v1.4/movie?page=${page}&limit=30&type=anime&sortField=rating.kp&sortType=-1`;
        } else if (isCartoon) {
            url = `https://api.kinopoisk.dev/v1.4/movie?page=${page}&limit=30&type=animated-series&sortField=rating.kp&sortType=-1`;
        } else {
            url = `https://api.kinopoisk.dev/v1.4/movie?page=${page}&limit=30&type=tv-series&genres.name=<?= urlencode($categoryName) ?>&sortField=rating.kp&sortType=-1`;
        }

        // Добавляем отладочную информацию
        console.log('Debug Info:');
        console.log('Category Type:', '<?= $category->type ?>');
        console.log('Category Name:', '<?= $categoryName ?>');
        console.log('Is Anime:', isAnime);
        console.log('Is Cartoon:', isCartoon);
        console.log('Request URL:', url);

        try {
            isLoading = true;
            toggleLoading(true);

            const response = await fetch(url, {
                headers: { 'X-API-KEY': API_KEY }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.log('API Error Response:', errorText);
                throw new Error(`HTTP error: ${response.status}`);
            }

            const data = await response.json();
            console.log('API Response:', data);
            
            // Фильтруем результаты для мультфильмов
            let docs = data.docs || [];
            if (isCartoon) {
                docs = docs.filter(film => {
                    // Проверяем наличие базовых данных
                    const hasBasicData = film.poster?.url && 
                                       film.name && 
                                       film.year >= 1990;
                    
                    // Проверяем что это действительно мультсериал
                    const isCartoonSeries = film.genres?.some(g => 
                        g.name.toLowerCase().includes('мультфильм') || 
                        g.name.toLowerCase().includes('детский') ||
                        g.name.toLowerCase().includes('семейный')
                    );
                    
                    return hasBasicData && isCartoonSeries;
                })
                .sort((a, b) => (b.rating?.kp || 0) - (a.rating?.kp || 0))
                .slice(0, 30); // Ограничиваем до 30 лучших результатов
            }
            
            return docs;
        } catch (error) {
            console.error('Error:', error);
            elements.errorMsg.textContent = `Не удалось загрузить фильмы: ${error.message}`;
            return [];
        } finally {
            toggleLoading(false);
        }
    }

    function toggleLoading(show) {
        elements.loader.style.display = show ? 'block' : 'none';
        elements.loadMoreBtn.disabled = show;
        elements.errorMsg.textContent = '';
        isLoading = show;
    }

    function createFilmCard(film) {
        const card = document.createElement('div');
        card.className = 'film-card';
        card.onclick = () => {
            window.location.href = `movie_card_cat.php?name=${encodeURIComponent(film.name || film.alternativeName)}`;
        };

        card.innerHTML = `
            <img src="${film.poster?.url || 'https://via.placeholder.com/200x300'}" 
                 alt="${film.name || film.alternativeName}">
            <div class="film-info">
                <h3>${film.name || film.alternativeName}</h3>
                <p>Год: ${film.year}</p>
                <p>Рейтинг Кинопоиска: ${film.rating?.kp || 'Н/Д'}</p>
            </div>
            <div class="film-overview">
                ${film.shortDescription || 'Описание отсутствует'}
            </div>
        `;

        return card;
    }

    async function loadFilms(page) {
        const films = await fetchFilms(page);
        if (films.length > 0) {
            films.forEach(film => {
                elements.filmsGrid.appendChild(createFilmCard(film));
            });
            currentPage++;
        } else {
            elements.loadMoreBtn.style.display = 'none';
        }
    }

    loadFilms(currentPage);

    elements.loadMoreBtn.addEventListener('click', () => {
        if (!isLoading) loadFilms(currentPage);
    });
});
</script>
</body>
</html>