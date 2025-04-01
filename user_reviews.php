<?php 
session_start();

// Проверка авторизации
if (!isset($_COOKIE['login'])) {
    header('Location: auth index.php');
    exit;
}

require_once 'comments/user_comments.php';

// Получаем имя пользователя
$username = $_COOKIE['login'];

// Инициализируем класс для работы с комментариями
$userComments = new UserComments();

// Получаем комментарии пользователя
$comments = $userComments->getUserComments($username);

// Обработка удаления комментария
if (isset($_POST['delete_comment'])) {
    $type = $_POST['content_type'];
    $kinopoiskId = $_POST['kinopoisk_id'];
    $commentId = $_POST['comment_id'];
    
    require_once 'comments/CommentsManager.php';
    $commentsManager = new CommentsManager();
    $commentsManager->deleteComment($type, $kinopoiskId, $commentId);
    
    // Перенаправляем на ту же страницу для обновления
    header('Location: user_reviews.php');
    exit;
}

// Загружаем информацию о контенте для каждого комментария
foreach ($comments as &$comment) {
    $contentInfo = $userComments->getContentInfo($comment['content_type'], $comment['kinopoiskId']);
    if ($contentInfo) {
        $comment['content_info'] = $contentInfo;
    } else {
        $comment['content_info'] = [
            'title' => 'Неизвестно',
            'year' => '',
            'posterUrl' => '',
            'type' => $comment['content_type']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои отзывы - <?= htmlspecialchars($username) ?></title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="stylesheet" href="css/style-media.css">
    <link rel="stylesheet" href="css/comments.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Lato&family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .user-reviews {
            width: 100%;
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .reviews-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #fff;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .no-reviews {
            text-align: center;
            padding: 50px 0;
            color: #aaa;
            font-style: italic;
        }
        
        .review-card {
            display: flex;
            margin-bottom: 30px;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .review-card:hover {
            transform: translateY(-5px);
        }
        
        .poster-container {
            flex: 0 0 140px;
            position: relative;
        }
        
        .poster-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .content-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(229, 9, 20, 0.9);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .review-content {
            flex: 1;
            padding: 20px;
            position: relative;
        }
        
        .content-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #fff;
        }
        
        .content-year {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .review-text {
            color: #ddd;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        
        .review-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            font-size: 12px;
            color: #888;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
        }
        
        .rating-display .stars {
            display: flex;
            margin-left: 5px;
        }
        
        .rating-display .star {
            color: #e50914;
            font-size: 14px;
            margin-right: 2px;
        }
        
        .review-date {
            font-style: italic;
        }
        
        .review-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
        }
        
        .btn-edit, .btn-delete, .btn-view {
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s;
        }
        
        .btn-edit:hover {
            color: #2196F3;
        }
        
        .btn-delete:hover {
            color: #e50914;
        }
        
        .btn-view:hover {
            color: #4CAF50;
        }
        
        /* Модальное окно для подтверждения удаления */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .modal-content {
            background-color: #282828;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
        }
        
        .modal-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #fff;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .modal-btn {
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        
        .modal-btn-cancel {
            background-color: #555;
            color: white;
        }
        
        .modal-btn-confirm {
            background-color: #e50914;
            color: white;
        }
        
        .back-to-account {
            margin-bottom: 20px;
        }
        
        .back-to-account a {
            color: #aaa;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .back-to-account a:hover {
            color: #fff;
        }
        
        .back-to-account i {
            margin-right: 5px;
        }
        
        @media (max-width: 768px) {
            .review-card {
                flex-direction: column;
            }
            
            .poster-container {
                flex: 0 0 auto;
                height: 200px;
            }
            
            .content-type-badge {
                top: auto;
                bottom: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .reviews-title {
                font-size: 20px;
            }
            
            .review-content {
                padding: 15px;
            }
            
            .content-title {
                font-size: 18px;
                margin-right: 60px;
            }
            
            .review-actions {
                top: 10px;
                right: 10px;
                gap: 5px;
            }
            
            .btn-edit, .btn-delete, .btn-view {
                font-size: 14px;
            }
            
            .poster-container {
                height: 180px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php require_once "blocks/header.php" ?>
    
    <div class="user-reviews">
        <div class="back-to-account">
            <a href="account.php"><i class="fas fa-arrow-left"></i> Вернуться в личный кабинет</a>
        </div>
        
        <h1 class="reviews-title">Мои отзывы</h1>
        
        <?php if (empty($comments)): ?>
            <div class="no-reviews">
                <p>У вас еще нет отзывов. Посмотрите фильмы и сериалы, чтобы оставить свое мнение!</p>
            </div>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="review-card">
                    <div class="poster-container">
                        <img src="<?= htmlspecialchars($comment['content_info']['posterUrl']) ?>" alt="Постер">
                        <div class="content-type-badge">
                            <?= $comment['content_type'] === 'film' ? 'Фильм' : 'Сериал' ?>
                        </div>
                    </div>
                    <div class="review-content">
                        <h2 class="content-title"><?= htmlspecialchars($comment['content_info']['title']) ?></h2>
                        <div class="content-year"><?= htmlspecialchars($comment['content_info']['year']) ?></div>
                        
                        <div class="review-text"><?= nl2br(htmlspecialchars($comment['text'])) ?></div>
                        
                        <div class="review-meta">
                            <div class="rating-display">
                                Ваша оценка: 
                                <div class="stars">
                                    <?php for ($i = 0; $i < $comment['rating']; $i++): ?>
                                        <span class="star">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-date"><?= htmlspecialchars($comment['date']) ?></div>
                        </div>
                        
                        <div class="review-actions">
                            <?php
                            // Используем movie_card_cat.php для всех типов контента
                            $contentUrl = 'movie_card_cat.php?name=' . urlencode($comment['content_info']['title']);
                            ?>
                            <a href="<?= $contentUrl ?>" class="btn-view" title="Перейти к контенту">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="post" style="display: inline;" onsubmit="return confirm('Вы уверены, что хотите удалить этот отзыв?');">
                                <input type="hidden" name="content_type" value="<?= htmlspecialchars($comment['content_type']) ?>">
                                <input type="hidden" name="kinopoisk_id" value="<?= htmlspecialchars($comment['kinopoiskId']) ?>">
                                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <button type="submit" name="delete_comment" class="btn-delete" title="Удалить отзыв">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="footer-account" style="margin-top: 100px"></div>
    </div>
    <?php require_once "blocks/footer.php" ?>


<script src="js/script.js"></script>
</body>
</html> 