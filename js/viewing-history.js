/**
 * Функция для добавления фильма/сериала в историю просмотров
 */
function addToViewingHistory(contentData) {
    // Проверяем, что пользователь авторизован
    if (!isUserLoggedIn()) {
        console.log('Пользователь не авторизован. История просмотров не сохраняется.');
        return;
    }
    
    // Проверяем обязательные поля
    if (!contentData.id || !contentData.type || !contentData.title) {
        console.error('Не указаны обязательные параметры для сохранения в историю просмотров', contentData);
        return;
    }
    
    console.log('Сохраняем историю просмотра:', contentData);
    
    // Формируем данные для отправки
    const formData = new FormData();
    formData.append('id', contentData.id);
    formData.append('type', contentData.type);
    formData.append('title', contentData.title);
    
    // Добавляем необязательные поля, если они есть
    if (contentData.poster) {
        formData.append('poster', contentData.poster);
    }
    
    if (contentData.year) {
        formData.append('year', contentData.year);
    }
    
    // ВАЖНО: всегда используем абсолютный путь к файлу add_to_history.php
    const fullPath = '/project/add_to_history.php';
    
    console.log('Отправляем запрос по адресу:', fullPath);
    
    // Отправляем запрос на сервер
    fetch(fullPath, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // Важно для отправки cookie
    })
    .then(response => {
        console.log('Получен ответ от сервера, статус:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json().catch(error => {
            console.warn('Ошибка парсинга JSON:', error);
            return { success: false, message: 'Ошибка формата ответа' };
        });
    })
    .then(data => {
        if (data.success) {
            console.log('Запись добавлена в историю просмотров');
        } else {
            console.error('Ошибка при добавлении в историю просмотров:', data.message);
        }
    })
    .catch(error => {
        console.error('Ошибка при выполнении запроса:', error);
    });
}

/**
 * Проверка авторизации пользователя
 */
function isUserLoggedIn() {
    const isLoggedIn = document.cookie.includes('login=');
    console.log('Пользователь авторизован:', isLoggedIn);
    return isLoggedIn;
}

/**
 * Автоматически добавляем фильм/сериал в историю просмотров при загрузке страницы
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM загружен, ищем информацию о фильме/сериале');
    
    // Получаем данные о фильме/сериале из атрибутов data-*
    const contentElement = document.querySelector('.movie-details');
    
    if (contentElement) {
        console.log('Найден элемент .movie-details:', contentElement);
        
        const contentData = {
            id: contentElement.getAttribute('data-content-id'),
            type: contentElement.getAttribute('data-content-type'),
            title: contentElement.getAttribute('data-content-title'),
            poster: contentElement.getAttribute('data-content-poster'),
            year: contentElement.getAttribute('data-content-year')
        };
        
        // Логируем данные для отладки
        console.log('Получены данные из атрибутов:', {
            id: contentData.id,
            type: contentData.type,
            title: contentData.title,
            posterLength: contentData.poster ? contentData.poster.length : 0,
            year: contentData.year
        });
        
        // Проверяем, что все обязательные атрибуты присутствуют
        if (contentData.id && contentData.type && contentData.title) {
            console.log('Все необходимые атрибуты найдены, добавляем в историю просмотров');
            
            // Небольшая задержка для уверенности, что страница полностью загружена
            setTimeout(() => {
                addToViewingHistory(contentData);
            }, 1000);
        } else {
            console.warn('Не найдены все необходимые атрибуты для сохранения истории просмотров');
            console.log('Имеющиеся атрибуты:', {
                'data-content-id': contentElement.getAttribute('data-content-id') || 'НЕ УКАЗАН',
                'data-content-type': contentElement.getAttribute('data-content-type') || 'НЕ УКАЗАН',
                'data-content-title': contentElement.getAttribute('data-content-title') || 'НЕ УКАЗАН',
                'data-content-poster': contentElement.getAttribute('data-content-poster') || 'НЕ УКАЗАН',
                'data-content-year': contentElement.getAttribute('data-content-year') || 'НЕ УКАЗАН'
            });
        }
    } else {
        console.warn('Элемент .movie-details не найден на странице');
    }
}); 