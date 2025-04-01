<div class="movie-card item">
    <a href="movie_card.php?id=<?php echo $film->id; ?>">
        <div class="movie-header" style="background-image: url(img/films/<?php echo $film->image; ?>); background-size: cover;">
            <div class="name-movie">
                <p class="name"><?php echo $film->name ?></p>
            </div>
        </div>
    </a>
</div>