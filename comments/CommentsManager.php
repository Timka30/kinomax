<?php
class CommentsManager {
    private $commentsDir;
    private $filmsDir;
    private $serialsDir;
    
    public function __construct() {
        // Определение директорий для хранения комментариев
        $this->commentsDir = dirname(__FILE__);
        $this->filmsDir = $this->commentsDir . '/films';
        $this->serialsDir = $this->commentsDir . '/serials';
        
        // Создаем директории, если они не существуют
        if (!is_dir($this->filmsDir)) {
            mkdir($this->filmsDir, 0777, true);
        }
        
        if (!is_dir($this->serialsDir)) {
            mkdir($this->serialsDir, 0777, true);
        }
    }
    
    /**
     * Добавление нового комментария
     * 
     * @param string $type Тип (film или serial)
     * @param int $kinopoiskId ID Кинопоиска
     * @param string $userName Имя пользователя
     * @param string $text Текст комментария
     * @param int $rating Рейтинг (1-10)
     * @return bool Результат операции
     */
    public function addComment($type, $kinopoiskId, $userName, $text, $rating) {
        $fileName = $this->getFileName($type, $kinopoiskId);
        $comments = $this->getComments($type, $kinopoiskId);
        
        // Новый комментарий
        $newComment = [
            'id' => $this->generateCommentId($comments),
            'user' => $userName,
            'text' => $text,
            'rating' => (int)$rating,
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s')
        ];
        
        // Добавляем комментарий
        $comments[] = $newComment;
        
        // Сохраняем файл
        return $this->saveComments($type, $kinopoiskId, $comments);
    }
    
    /**
     * Получение всех комментариев для фильма/сериала
     * 
     * @param string $type Тип (film или serial)
     * @param int $kinopoiskId ID Кинопоиска
     * @return array Массив комментариев
     */
    public function getComments($type, $kinopoiskId) {
        $fileName = $this->getFileName($type, $kinopoiskId);
        
        if (file_exists($fileName)) {
            $commentsJson = file_get_contents($fileName);
            $comments = json_decode($commentsJson, true);
            if (is_array($comments)) {
                // Сортировка по дате (новые сверху)
                usort($comments, function($a, $b) {
                    return $b['timestamp'] - $a['timestamp'];
                });
                return $comments;
            }
        }
        
        return [];
    }
    
    /**
     * Удаление комментария
     * 
     * @param string $type Тип (film или serial)
     * @param int $kinopoiskId ID Кинопоиска
     * @param int $commentId ID комментария
     * @return bool Результат операции
     */
    public function deleteComment($type, $kinopoiskId, $commentId) {
        $comments = $this->getComments($type, $kinopoiskId);
        
        // Находим и удаляем комментарий
        foreach ($comments as $key => $comment) {
            if ($comment['id'] == $commentId) {
                unset($comments[$key]);
                $comments = array_values($comments); // Переиндексация массива
                return $this->saveComments($type, $kinopoiskId, $comments);
            }
        }
        
        return false;
    }
    
    /**
     * Получение среднего рейтинга
     * 
     * @param string $type Тип (film или serial)
     * @param int $kinopoiskId ID Кинопоиска
     * @return float Средний рейтинг
     */
    public function getAverageRating($type, $kinopoiskId) {
        $comments = $this->getComments($type, $kinopoiskId);
        
        if (empty($comments)) {
            return 0;
        }
        
        $total = 0;
        $count = count($comments);
        
        foreach ($comments as $comment) {
            $total += $comment['rating'];
        }
        
        return round($total / $count, 1);
    }
    
    /**
     * Получение полной статистики по отзывам
     * 
     * @param string $type Тип (film или serial)
     * @param int $kinopoiskId ID Кинопоиска
     * @return array Статистика
     */
    public function getStats($type, $kinopoiskId) {
        $comments = $this->getComments($type, $kinopoiskId);
        
        return [
            'count' => count($comments),
            'average_rating' => $this->getAverageRating($type, $kinopoiskId)
        ];
    }
    
    /**
     * Генерация имени файла
     */
    private function getFileName($type, $kinopoiskId) {
        $dir = ($type == 'film') ? $this->filmsDir : $this->serialsDir;
        return $dir . '/' . $kinopoiskId . '.json';
    }
    
    /**
     * Сохранение комментариев в файл
     */
    private function saveComments($type, $kinopoiskId, $comments) {
        $fileName = $this->getFileName($type, $kinopoiskId);
        return file_put_contents($fileName, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Генерация уникального ID для комментария
     */
    private function generateCommentId($comments) {
        $maxId = 0;
        
        foreach ($comments as $comment) {
            if ($comment['id'] > $maxId) {
                $maxId = $comment['id'];
            }
        }
        
        return $maxId + 1;
    }
} 