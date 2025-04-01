<?php
require_once 'CommentsManager.php';

/**
 * Класс для работы с комментариями пользователя
 */
class UserComments
{
    private $commentsManager;
    
    public function __construct() {
        $this->commentsManager = new CommentsManager();
    }
    
    /**
     * Получает все комментарии указанного пользователя
     * 
     * @param string $username Имя пользователя
     * @return array Массив комментариев
     */
    public function getUserComments($username) {
        $userComments = [];
        
        // Получаем все комментарии для фильмов
        $filmComments = $this->getCommentsFromDirectory('films', $username);
        foreach ($filmComments as $comment) {
            $comment['content_type'] = 'film';
            $userComments[] = $comment;
        }
        
        // Получаем все комментарии для сериалов
        $serialComments = $this->getCommentsFromDirectory('serials', $username);
        foreach ($serialComments as $comment) {
            $comment['content_type'] = 'serial';
            $userComments[] = $comment;
        }
        
        // Сортируем по дате (новые сверху)
        usort($userComments, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $userComments;
    }
    
    /**
     * Получает комментарии из указанной директории
     * 
     * @param string $dirType Тип директории (films или serials)
     * @param string $username Имя пользователя
     * @return array Массив комментариев
     */
    private function getCommentsFromDirectory($dirType, $username) {
        $comments = [];
        $dir = __DIR__ . '/' . $dirType;
        
        if (!is_dir($dir)) {
            return $comments;
        }
        
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $kinopoiskId = pathinfo($file, PATHINFO_FILENAME);
            $commentsFromFile = $this->commentsManager->getComments($dirType === 'films' ? 'film' : 'serial', $kinopoiskId);
            
            foreach ($commentsFromFile as $comment) {
                if ($comment['user'] === $username) {
                    // Добавляем информацию о фильме/сериале
                    $comment['kinopoiskId'] = $kinopoiskId;
                    $comments[] = $comment;
                }
            }
        }
        
        return $comments;
    }
    
    /**
     * Получает информацию о названии фильма или сериала
     * 
     * @param string $type Тип контента (film или serial)
     * @param int $kinopoiskId ID на Кинопоиске
     * @return array|null Информация о фильме/сериале или null, если не найдено
     */
    public function getContentInfo($type, $kinopoiskId) {
        $apiKey = '5552e433-2266-4209-ba28-9fdd418c96fd';
        $cacheKey = "content_info_{$type}_{$kinopoiskId}";
        $cacheFile = sys_get_temp_dir() . '/movie_cache_' . md5($cacheKey);
        
        // Проверяем кэш
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) { // 24 часа
            return unserialize(file_get_contents($cacheFile));
        }
        
        // Делаем запрос к API
        $url = "https://kinopoiskapiunofficial.tech/api/v2.1/films/{$kinopoiskId}";
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['X-API-KEY: ' . $apiKey],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        // Для поиска film ID и поиска названия
        if (!isset($data['data']['nameRu']) && !isset($data['data']['nameEn'])) {
            // Пробуем получить данные через основной API
            $urlSearch = "https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=" . $kinopoiskId;
            
            $ch = curl_init($urlSearch);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['X-API-KEY: ' . $apiKey],
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            
            $responseSearch = curl_exec($ch);
            curl_close($ch);
            
            $searchData = json_decode($responseSearch, true);
            
            if (isset($searchData['films']) && !empty($searchData['films'])) {
                $firstResult = $searchData['films'][0];
                $info = [
                    'title' => $firstResult['nameRu'] ?? ($firstResult['nameEn'] ?? 'Неизвестно'),
                    'year' => $firstResult['year'] ?? '',
                    'posterUrl' => $firstResult['posterUrl'] ?? '',
                    'type' => $type
                ];
                
                // Сохраняем в кэш
                file_put_contents($cacheFile, serialize($info));
                
                return $info;
            }
            
            // Если ничего не найдено, используем KinopoiskId как название
            $info = [
                'title' => "Фильм с ID " . $kinopoiskId,
                'year' => '',
                'posterUrl' => '',
                'type' => $type
            ];
            file_put_contents($cacheFile, serialize($info));
            return $info;
        }
        
        // Если данные найдены, форматируем их
        $info = [
            'title' => $data['data']['nameRu'] ?? ($data['data']['nameEn'] ?? 'Неизвестно'),
            'year' => $data['data']['year'] ?? '',
            'posterUrl' => $data['data']['posterUrl'] ?? '',
            'type' => $type
        ];
        
        // Сохраняем в кэш
        file_put_contents($cacheFile, serialize($info));
        
        return $info;
    }
}
?> 