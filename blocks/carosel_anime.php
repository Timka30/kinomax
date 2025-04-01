<div class="movie-card item">
<a href="anime_card.php?id=<?php echo $film->id; ?>">
    <div class="movie-header" style="background-image: url(img/anime/<?php echo $film->image; ?>); background-size: cover;">
        <div class="name-movie">
            <p class="name"><?php echo htmlspecialchars($film->name); ?></p>
        </div>
    </div>
    </a>
</div>
