<?php if (isset($anime)): ?>
    <div class="movie-details">
        <?php
        $posterUrl = '';
        if (!empty($anime['posters']['medium']['url'])) {
            $posterUrl = $anime['posters']['medium']['url'];
            // Заменяем домен если он присутствует
            $posterUrl = str_replace('anilibria.tv', 'anilibria.top', $posterUrl);
            if (strpos($posterUrl, 'http') !== 0) {
                $posterUrl = 'https://anilibria.top' . $posterUrl;
            }
        }
        error_log("Poster URL: " . $posterUrl);
        ?>
        <img src="<?php echo htmlspecialchars($posterUrl); ?>" 
             alt="<?php echo htmlspecialchars($anime['names']['ru']); ?>"
             onerror="console.error('Failed to load image:', this.src);">
        
        <div class="movie-details-content">
            <h1 style="font-size: 30px; color: white;"><?php echo htmlspecialchars($anime['names']['ru']); ?></h1>
            
            <?php if (!empty($anime['description'])): ?>
                <p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($anime['description'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($anime['genres'])): ?>
                <p><strong>Жанры:</strong> <?php echo htmlspecialchars(implode(', ', $anime['genres'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($anime['season']['string'])): ?>
                <p><strong>Сезон:</strong> <?php echo htmlspecialchars($anime['season']['string']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($anime['season']['year'])): ?>
                <p><strong>Год:</strong> <?php echo htmlspecialchars($anime['season']['year']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($anime['type']['full_string'])): ?>
                <p><strong>Тип:</strong> <?php echo htmlspecialchars($anime['type']['full_string']); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($anime['id'])): ?>
        <div class="anime-player">
            <iframe 
                src="https://rt.anilib.moe/public/iframe.php?id=<?php echo urlencode($anime['id']); ?>&noads=1" 
                frameborder="0" 
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox"
                referrerpolicy="origin">
            </iframe>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.querySelector('.anime-player iframe').addEventListener('load', function() {
            console.log('Player loaded successfully');
            this.style.display = 'block';
        });

        document.querySelector('.anime-player iframe').addEventListener('error', function(e) {
            console.error('Failed to load player:', e);
            // Пробуем альтернативный URL при ошибке
            if (this.src.includes('anilibria.top')) {
                this.src = this.src.replace('anilibria.top', 'dl-20241125-5.anilib.one');
            }
        });

        // Добавляем обработчик для мобильных устройств
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            const iframe = document.querySelector('.anime-player iframe');
            iframe.src = iframe.src + '&mobile=1';
        }
    </script>
<?php else: ?>
    <div class="error-message">
        <p>Информация об аниме недоступна</p>
    </div>
<?php endif; ?> 