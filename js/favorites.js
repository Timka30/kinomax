/**
 * Функция для добавления/удаления фильма/сериала в избранное
 */
function toggleFavorite(button) {
    // Проверяем, что пользователь авторизован
    if (!isUserLoggedIn()) {
        showNotification('Войдите, чтобы добавить в избранное', 'error');
        return;
    }
    
    // Получаем данные о контенте из атрибутов data-*
    const contentElement = document.querySelector('.movie-details');
    if (!contentElement) {
        console.error('Элемент .movie-details не найден');
        return;
    }
    
    const contentData = {
        id: contentElement.getAttribute('data-content-id'),
        type: contentElement.getAttribute('data-content-type'),
        title: contentElement.getAttribute('data-content-title'),
        poster: contentElement.getAttribute('data-content-poster'),
        year: contentElement.getAttribute('data-content-year')
    };
    
    // Проверяем обязательные поля
    if (!contentData.id || !contentData.type || !contentData.title) {
        console.error('Не указаны обязательные параметры для избранного', contentData);
        return;
    }
    
    // Определяем действие (добавить/удалить)
    const isFavorited = button.classList.contains('favorited');
    const action = isFavorited ? 'remove' : 'add';
    
    console.log(`${action === 'add' ? 'Добавляем' : 'Удаляем'} из избранного:`, contentData);
    
    // Формируем данные для отправки
    const formData = new FormData();
    formData.append('id', contentData.id);
    formData.append('type', contentData.type);
    formData.append('title', contentData.title);
    formData.append('action', action);
    
    // Добавляем необязательные поля, если они есть
    if (contentData.poster) {
        formData.append('poster', contentData.poster);
    }
    
    if (contentData.year) {
        formData.append('year', contentData.year);
    }
    
    // Отправляем запрос на сервер
    fetch('/project/add_to_favorites.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // Важно для отправки cookie
    })
    .then(response => {
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
            // Меняем внешний вид кнопки
            if (action === 'add') {
                button.classList.add('favorited');
                button.querySelector('i').classList.remove('far');
                button.querySelector('i').classList.add('fas');
                button.title = 'Удалить из избранного';
                showNotification('Добавлено в избранное', 'success');
            } else {
                button.classList.remove('favorited');
                button.querySelector('i').classList.remove('fas');
                button.querySelector('i').classList.add('far');
                button.title = 'Добавить в избранное';
                showNotification('Удалено из избранного', 'success');
            }
        } else {
            console.error('Ошибка при работе с избранным:', data.message);
            showNotification(data.message || 'Произошла ошибка', 'error');
        }
    })
    .catch(error => {
        console.error('Ошибка при выполнении запроса:', error);
        showNotification('Ошибка соединения с сервером', 'error');
    });
}

/**
 * Проверка авторизации пользователя
 */
function isUserLoggedIn() {
    return document.cookie.includes('login=');
}

/**
 * Функция проверки наличия фильма/сериала в избранном
 */
function checkIfFavorited() {
    // Проверяем, что пользователь авторизован
    if (!isUserLoggedIn()) {
        return;
    }
    
    // Получаем данные о контенте
    const contentElement = document.querySelector('.movie-details');
    if (!contentElement) {
        return;
    }
    
    const contentId = contentElement.getAttribute('data-content-id');
    const contentType = contentElement.getAttribute('data-content-type');
    
    if (!contentId || !contentType) {
        return;
    }
    
    // Запрашиваем статус избранного
    const formData = new FormData();
    formData.append('id', contentId);
    formData.append('type', contentType);
    formData.append('check_only', '1');
    
    fetch('/project/add_to_favorites.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.is_favorite) {
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                favoriteBtn.classList.add('favorited');
                favoriteBtn.querySelector('i').classList.remove('far');
                favoriteBtn.querySelector('i').classList.add('fas');
                favoriteBtn.title = 'Удалить из избранного';
            }
        }
    })
    .catch(error => {
        console.error('Ошибка при проверке статуса избранного:', error);
    });
}

/**
 * Функция для отображения уведомлений
 */
function showNotification(message, type = 'info') {
    // Проверяем наличие контейнера для уведомлений
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        document.body.appendChild(notificationContainer);
        
        // Добавляем стили для контейнера уведомлений
        const style = document.createElement('style');
        style.textContent = `
            #notification-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }
            
            .notification {
                padding: 12px 20px;
                margin-bottom: 10px;
                border-radius: 4px;
                color: white;
                font-family: "Comfortaa", sans-serif;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                animation: slide-in 0.3s ease-out forwards, fade-out 0.5s ease-out 2.5s forwards;
                opacity: 0;
                transform: translateX(100%);
            }
            
            .notification.success {
                background-color: #4caf50;
            }
            
            .notification.error {
                background-color: #e50914;
            }
            
            .notification.info {
                background-color: #2196f3;
            }
            
            @keyframes slide-in {
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fade-out {
                to {
                    opacity: 0;
                    transform: translateY(-20px);
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Добавляем уведомление в контейнер
    notificationContainer.appendChild(notification);
    
    // Удаляем уведомление через 3 секунды
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

/**
 * Инициализация кнопки "Добавить в избранное" при загрузке страницы
 */
document.addEventListener('DOMContentLoaded', function() {
    // Получаем данные о фильме/сериале
    const contentElement = document.querySelector('.movie-details');
    
    if (contentElement) {
        // Создаем кнопку "Добавить в избранное"
        const favoriteBtn = document.createElement('button');
        favoriteBtn.className = 'btn-favorite';
        favoriteBtn.title = 'Добавить в избранное';
        favoriteBtn.innerHTML = '<i class="far fa-star"></i>';
        favoriteBtn.onclick = function() {
            toggleFavorite(this);
        };
        
        // Добавляем стили для кнопки
        const style = document.createElement('style');
        style.textContent = `
            .btn-favorite {
                position: absolute;
                top: 20px;
                right: 20px;
                background-color: rgba(0, 0, 0, 0.6);
                color: #ffd700;
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                font-size: 18px;
                z-index: 100;
                transition: all 0.3s ease;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            }
            
            .btn-favorite:hover {
                background-color: rgba(0, 0, 0, 0.8);
                transform: scale(1.1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            }
            
            .btn-favorite.favorited {
                background-color: #ffd700;
                color: #000;
            }
            
            .btn-favorite.favorited:hover {
                background-color: #ffcc00;
            }
            
            @media (max-width: 768px) {
                .btn-favorite {
                    width: 36px;
                    height: 36px;
                    font-size: 16px;
                    top: 15px;
                    right: 15px;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Находим место для вставки кнопки
        const movieInfoContainer = contentElement.querySelector('.movie-details-content');
        if (movieInfoContainer) {
            movieInfoContainer.style.position = 'relative';
            movieInfoContainer.appendChild(favoriteBtn);
        } else {
            contentElement.appendChild(favoriteBtn);
        }
        
        // Проверяем, добавлен ли фильм в избранное
        checkIfFavorited();
    }
}); 