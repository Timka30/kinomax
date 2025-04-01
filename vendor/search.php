<?php
require "db.php"; // Подключение к базе данных

if (isset($_GET['q'])) {
    $query = $_GET['q'];

    try {
        // Поиск в таблице films
        $stmt = $pdo->prepare("SELECT id, name, 'film' AS type FROM films WHERE name LIKE :query LIMIT 10");
        $stmt->execute(['query' => '%' . $query . '%']);
        $filmResults = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Поиск в таблице serials
        $stmt = $pdo->prepare("SELECT id, name, 'serial' AS type FROM serials WHERE name LIKE :query LIMIT 10");
        $stmt->execute(['query' => '%' . $query . '%']);
        $serialResults = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Оъединяем результаты 
        $results = array_merge($filmResults, $serialResults);

        if ($results) {
            foreach ($results as $result) {
                // Проверяем тип (фильм или сериал) и создаем соответствующую ссылку
                if ($result->type === 'film') {
                    echo "<div class='result-item' onclick='window.location.href=\"movie_card.php?id={$result->id}\"'>{$result->name}</div>";
                } elseif ($result->type === 'serial') {
                    echo "<div class='result-item' onclick='window.location.href=\"movie_card_serials.php?id={$result->id}\"'>{$result->name}</div>";
                }
            }
        } else {
            echo "<div class='result-item'>Ничего не найдено</div>";
        }
    } catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }
}
?>
