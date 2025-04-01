<?php
/**
 * Класс для управления историей просмотров пользователей
 */
class ViewingHistory
{
    private $historyDir;
    private $logFile;
    
    public function __construct() {
        // Определение директории для хранения истории
        $this->historyDir = dirname(__FILE__) . '/users';
        $this->logFile = dirname(__FILE__) . '/debug_history.log';
        
        $this->logMessage("Инициализация класса ViewingHistory");
        $this->logMessage("Директория для хранения истории: " . $this->historyDir);
        
        // Создаем директорию, если она не существует
        if (!is_dir($this->historyDir)) {
            $this->logMessage("Директория не существует, создаем...");
            $mkdirResult = mkdir($this->historyDir, 0777, true);
            $this->logMessage("Результат создания директории: " . ($mkdirResult ? 'Успешно' : 'Ошибка'));
        } else {
            $this->logMessage("Директория уже существует");
        }
        
        // Проверяем права на запись
        if (!is_writable($this->historyDir)) {
            $this->logMessage("ОШИБКА: Директория недоступна для записи");
            chmod($this->historyDir, 0777);
            $this->logMessage("Попытка установить права 0777: " . (is_writable($this->historyDir) ? 'Успешно' : 'Ошибка'));
        } else {
            $this->logMessage("Директория доступна для записи");
        }
    }
    
    /**
     * Добавление записи о просмотре фильма/сериала
     * 
     * @param string $username Имя пользователя
     * @param array $contentData Данные о контенте
     * @return bool Результат операции
     */
    public function addToHistory($username, $contentData) {
        $this->logMessage("Добавление записи в историю для пользователя: $username");
        $this->logMessage("Данные контента: " . print_r($contentData, true));
        
        if (empty($username) || empty($contentData['id'])) {
            $this->logMessage("ОШИБКА: Пустое имя пользователя или ID контента");
            return false;
        }
        
        $history = $this->getUserHistory($username);
        $this->logMessage("Получено записей истории: " . count($history));
        
        // Проверяем, есть ли уже такой контент в истории
        $existingIndex = -1;
        foreach ($history as $index => $item) {
            if ($item['id'] == $contentData['id'] && $item['type'] == $contentData['type']) {
                $existingIndex = $index;
                break;
            }
        }
        
        // Текущее время
        $timestamp = time();
        
        // Если контент уже есть в истории - обновляем время просмотра
        if ($existingIndex >= 0) {
            $this->logMessage("Обновление существующей записи (индекс: $existingIndex)");
            $history[$existingIndex]['timestamp'] = $timestamp;
            $history[$existingIndex]['viewed_at'] = date('Y-m-d H:i:s');
        } else {
            // Добавляем новую запись
            $this->logMessage("Добавление новой записи");
            $newItem = [
                'id' => $contentData['id'],
                'type' => $contentData['type'],
                'title' => $contentData['title'] ?? 'Без названия',
                'poster' => $contentData['poster'] ?? '',
                'year' => $contentData['year'] ?? '',
                'timestamp' => $timestamp,
                'viewed_at' => date('Y-m-d H:i:s')
            ];
            
            $history[] = $newItem;
        }
        
        // Сортируем по времени (новые сверху)
        usort($history, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        // Ограничиваем историю последними 50 записями
        if (count($history) > 50) {
            $this->logMessage("Ограничиваем историю до 50 записей");
            $history = array_slice($history, 0, 50);
        }
        
        $saveResult = $this->saveUserHistory($username, $history);
        $this->logMessage("Результат сохранения: " . ($saveResult ? 'Успешно' : 'Ошибка'));
        
        return $saveResult;
    }
    
    /**
     * Получение истории просмотров пользователя
     * 
     * @param string $username Имя пользователя
     * @param int $limit Ограничение количества записей (0 - без ограничения)
     * @return array История просмотров
     */
    public function getUserHistory($username, $limit = 0) {
        $fileName = $this->getHistoryFileName($username);
        $this->logMessage("Получение истории пользователя: $username");
        $this->logMessage("Файл истории: $fileName");
        
        if (file_exists($fileName)) {
            $this->logMessage("Файл истории существует");
            $historyJson = file_get_contents($fileName);
            $history = json_decode($historyJson, true);
            
            if (is_array($history)) {
                // Сортируем по времени (новые сверху)
                usort($history, function($a, $b) {
                    return $b['timestamp'] - $a['timestamp'];
                });
                
                // Если указан лимит, ограничиваем количество записей
                if ($limit > 0 && count($history) > $limit) {
                    $history = array_slice($history, 0, $limit);
                }
                
                $this->logMessage("Получено записей: " . count($history));
                return $history;
            } else {
                $this->logMessage("ОШИБКА: Некорректный JSON в файле истории");
            }
        } else {
            $this->logMessage("Файл истории не существует");
        }
        
        return [];
    }
    
    /**
     * Очистка истории просмотров пользователя
     * 
     * @param string $username Имя пользователя
     * @return bool Результат операции
     */
    public function clearHistory($username) {
        $fileName = $this->getHistoryFileName($username);
        $this->logMessage("Очистка истории пользователя: $username");
        $this->logMessage("Файл истории: $fileName");
        
        if (file_exists($fileName)) {
            $unlinkResult = unlink($fileName);
            $this->logMessage("Результат удаления файла: " . ($unlinkResult ? 'Успешно' : 'Ошибка'));
            return $unlinkResult;
        }
        
        $this->logMessage("Файл истории не существует, очистка не требуется");
        return true;
    }
    
    /**
     * Удаление записи из истории просмотров
     * 
     * @param string $username Имя пользователя
     * @param string $contentId ID контента
     * @param string $contentType Тип контента
     * @return bool Результат операции
     */
    public function removeFromHistory($username, $contentId, $contentType) {
        $this->logMessage("Удаление записи из истории пользователя: $username");
        $this->logMessage("ID контента: $contentId, тип: $contentType");
        
        $history = $this->getUserHistory($username);
        $initialCount = count($history);
        
        // Ищем запись для удаления
        foreach ($history as $index => $item) {
            if ($item['id'] == $contentId && $item['type'] == $contentType) {
                unset($history[$index]);
                break;
            }
        }
        
        // Переиндексируем массив
        $history = array_values($history);
        $this->logMessage("Удалено записей: " . ($initialCount - count($history)));
        
        $saveResult = $this->saveUserHistory($username, $history);
        $this->logMessage("Результат сохранения: " . ($saveResult ? 'Успешно' : 'Ошибка'));
        
        return $saveResult;
    }
    
    /**
     * Получение имени файла для хранения истории
     * 
     * @param string $username Имя пользователя
     * @return string Путь к файлу
     */
    private function getHistoryFileName($username) {
        // Используем md5 для безопасного формирования имени файла
        $safeUsername = md5($username);
        return $this->historyDir . '/' . $safeUsername . '.json';
    }
    
    /**
     * Сохранение истории пользователя в файл
     * 
     * @param string $username Имя пользователя
     * @param array $history История просмотров
     * @return bool Результат операции
     */
    private function saveUserHistory($username, $history) {
        $fileName = $this->getHistoryFileName($username);
        $this->logMessage("Сохранение истории пользователя: $username");
        $this->logMessage("Файл истории: $fileName");
        
        // Проверяем директорию перед сохранением
        if (!is_dir($this->historyDir)) {
            $this->logMessage("ВНИМАНИЕ: Директория не существует, повторная попытка создания");
            $mkdirResult = mkdir($this->historyDir, 0777, true);
            $this->logMessage("Результат создания директории: " . ($mkdirResult ? 'Успешно' : 'Ошибка'));
            
            if (!$mkdirResult) {
                $this->logMessage("ОШИБКА: Не удалось создать директорию");
                return false;
            }
        }
        
        // Проверяем права на запись
        if (!is_writable($this->historyDir)) {
            $this->logMessage("ОШИБКА: Директория недоступна для записи");
            $chmodResult = chmod($this->historyDir, 0777);
            $this->logMessage("Попытка установить права 0777: " . ($chmodResult ? 'Успешно' : 'Ошибка'));
            
            if (!is_writable($this->historyDir)) {
                $this->logMessage("ОШИБКА: Директория всё еще недоступна для записи");
                return false;
            }
        }
        
        // Преобразуем данные в JSON
        $jsonData = json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($jsonData === false) {
            $this->logMessage("ОШИБКА: Не удалось преобразовать данные в JSON: " . json_last_error_msg());
            return false;
        }
        
        // Проверяем доступность директории для записи
        $dirWritable = is_writable(dirname($fileName));
        $this->logMessage("Директория " . dirname($fileName) . " доступна для записи: " . ($dirWritable ? 'Да' : 'Нет'));
        
        // Пробуем записать данные в файл
        $writeResult = file_put_contents($fileName, $jsonData);
        
        if ($writeResult === false) {
            $this->logMessage("ОШИБКА: Не удалось записать данные в файл");
            if (function_exists('error_get_last') && $error = error_get_last()) {
                $this->logMessage("Последняя ошибка: " . $error['message']);
            }
            
            // Дополнительная проверка ошибок файловой системы
            $this->logMessage("Текущий пользователь PHP: " . get_current_user());
            $this->logMessage("Текущие права PHP: " . substr(sprintf('%o', fileperms($this->historyDir)), -4));
            
            // Пробуем альтернативный способ записи
            $this->logMessage("Пробуем альтернативный способ записи через fopen/fwrite");
            $handle = fopen($fileName, 'w');
            if ($handle) {
                $fwriteResult = fwrite($handle, $jsonData);
                fclose($handle);
                $this->logMessage("Результат fwrite: " . ($fwriteResult !== false ? "Успешно ($fwriteResult байт)" : "Ошибка"));
                return $fwriteResult !== false;
            } else {
                $this->logMessage("ОШИБКА: Не удалось открыть файл для записи");
                return false;
            }
        }
        
        $this->logMessage("Данные успешно записаны в файл ($writeResult байт)");
        return true;
    }
    
    /**
     * Логирование сообщений
     * 
     * @param string $message Сообщение для записи в лог
     */
    private function logMessage($message) {
        $logMessage = date('Y-m-d H:i:s') . " - " . $message . "\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
} 