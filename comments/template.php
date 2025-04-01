<?php
/**
 * Шаблон блока комментариев
 * 
 * Параметры:
 * @param string $type - тип контента (film или serial)
 * @param int $kinopoiskId - ID контента на Кинопоиске
 */

// Проверяем, авторизован ли пользователь
$isLoggedIn = isset($_COOKIE['login']);
$userName = $_COOKIE['login'] ?? '';
?>

<!-- Подключаем стили и скрипты -->
<link rel="stylesheet" href="css/comments.css">
<script src="js/comments.js"></script>

<div class="comments-section">
    <div class="comments-header">
        <div class="comments-title">Отзывы зрителей</div>
        <div id="comments-stats">
            <!-- Сюда будет добавлена статистика -->
        </div>
    </div>
    
    <?php if ($isLoggedIn): ?>
    <form id="comment-form">
        <h3>Оставить отзыв</h3>
        
        <div class="form-group">
            <label for="rating">Ваша оценка:</label>
            <div class="rating-input">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="star <?php echo ($i <= 5) ? 'selected' : ''; ?>">★</span>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" value="5">
        </div>
        
        <div class="form-group">
            <label for="comment-text">Ваш отзыв:</label>
            <textarea name="text" id="comment-text" placeholder="Поделитесь своим мнением о фильме..."></textarea>
        </div>
        
        <button type="submit" class="submit-comment">Отправить отзыв</button>
    </form>
    <?php else: ?>
    <div class="auth-message">
        <p>Чтобы оставить отзыв, необходимо <a href="../auth index.php">авторизоваться</a> на сайте.</p>
    </div>
    <?php endif; ?>
    
    <div id="comments-container">
        <!-- Сюда будут добавлены комментарии -->
    </div>
</div>

<script>
    // Инициализация системы комментариев
    initComments({
        type: '<?php echo $type; ?>',
        kinopoiskId: <?php echo $kinopoiskId; ?>,
        isLoggedIn: <?php echo $isLoggedIn ? 'true' : 'false'; ?>,
        currentUserName: '<?php echo $userName; ?>'
    });
</script> 