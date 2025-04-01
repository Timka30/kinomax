/**
 * Отладочный скрипт для проверки функциональности истории просмотров
 */
console.log('🔍 Debug-History: Скрипт отладки загружен');

// Проверка cookie авторизации
const checkAuth = () => {
    const cookies = document.cookie.split(';').map(c => c.trim());
    const loginCookie = cookies.find(c => c.startsWith('login='));
    
    console.log('🔍 Debug-History: Куки страницы:', cookies);
    console.log('🔍 Debug-History: Куки авторизации:', loginCookie);
    
    if (loginCookie) {
        const username = loginCookie.split('=')[1];
        console.log('🔍 Debug-History: Пользователь авторизован как:', username);
        return true;
    } else {
        console.log('🔍 Debug-History: Пользователь не авторизован');
        return false;
    }
};

// Проверка наличия элемента movie-details
const checkMovieDetails = () => {
    const movieDetails = document.querySelector('.movie-details');
    console.log('🔍 Debug-History: Элемент .movie-details:', movieDetails);
    
    if (movieDetails) {
        // Проверяем наличие всех атрибутов
        console.log('🔍 Debug-History: HTML элемента:', movieDetails.outerHTML.substring(0, 300) + '...');
        
        // Проверяем все data-атрибуты
        const allAttrs = Array.from(movieDetails.attributes)
            .filter(attr => attr.name.startsWith('data-'))
            .map(attr => `${attr.name}: ${attr.value}`);
        console.log('🔍 Debug-History: Все data-атрибуты:', allAttrs);
        
        // Проверяем атрибуты
        const attributes = {
            'id': movieDetails.getAttribute('data-content-id'),
            'type': movieDetails.getAttribute('data-content-type'),
            'title': movieDetails.getAttribute('data-content-title'),
            'poster': movieDetails.getAttribute('data-content-poster'),
            'year': movieDetails.getAttribute('data-content-year')
        };
        
        console.log('🔍 Debug-History: Атрибуты элемента:', attributes);
        
        // Проверяем заполненность обязательных атрибутов
        const requiredAttributes = ['id', 'type', 'title'];
        const missingAttributes = requiredAttributes.filter(attr => !attributes[attr]);
        
        if (missingAttributes.length === 0) {
            console.log('🔍 Debug-History: Все необходимые атрибуты присутствуют');
        } else {
            console.log('🔍 Debug-History: Отсутствуют атрибуты:', missingAttributes);
        }
        
        return attributes;
    } else {
        console.log('🔍 Debug-History: Элемент .movie-details не найден');
        return null;
    }
};

// Тестовая отправка запроса напрямую на add_to_history.php
const testDirectRequest = (attributes) => {
    if (!attributes) return;
    
    const fullPath = '/project/add_to_history.php';
    
    console.log('🔍 Debug-History: Прямой запрос на добавление истории:', fullPath);
    
    const formData = new FormData();
    Object.entries(attributes).forEach(([key, value]) => {
        if (value) formData.append(key, value);
    });
    
    fetch(fullPath, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('🔍 Debug-History: Статус прямого запроса:', response.status);
        return response.text();
    })
    .then(data => {
        console.log('🔍 Debug-History: Ответ на прямой запрос:', 
            data.substring(0, 100) + (data.length > 100 ? '...' : ''));
        try {
            const json = JSON.parse(data);
            console.log('🔍 Debug-History: Ответ в JSON:', json);
        } catch(e) {
            console.log('🔍 Debug-History: Ответ не является JSON');
        }
    })
    .catch(error => {
        console.log('🔍 Debug-History: Ошибка прямого запроса:', error);
    });
};

// Запускаем диагностику при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    console.log('🔍 Debug-History: DOM загружен, начинаем диагностику');
    
    const isAuth = checkAuth();
    const attributes = checkMovieDetails();
    
    if (isAuth && attributes) {
        console.log('🔍 Debug-History: Проверки пройдены, тестируем запрос');
        // Выполняем прямой запрос на добавление в историю
        testDirectRequest(attributes);
    } else {
        console.log('🔍 Debug-History: Проверки не пройдены, запрос не отправляется');
    }
});

// Метод для форсированного добавления в историю (можно вызвать из консоли)
window.debugAddToHistory = () => {
    console.log('🔍 Debug-History: Ручное добавление в историю');
    
    const attributes = checkMovieDetails();
    if (!attributes || !attributes.id || !attributes.type || !attributes.title) {
        console.log('🔍 Debug-History: Недостаточно данных для добавления в историю');
        
        // Попытка создать пользовательские данные
        const manualData = {
            id: attributes?.id || prompt('Введите ID контента'),
            type: attributes?.type || prompt('Введите тип (film/serial)'),
            title: attributes?.title || prompt('Введите название'),
            poster: attributes?.poster || '',
            year: attributes?.year || ''
        };
        
        console.log('🔍 Debug-History: Ручные данные:', manualData);
        
        if (manualData.id && manualData.type && manualData.title) {
            if (typeof addToViewingHistory === 'function') {
                console.log('🔍 Debug-History: Вызываем функцию addToViewingHistory');
                addToViewingHistory(manualData);
            } else {
                console.log('🔍 Debug-History: Функция addToViewingHistory не найдена');
            }
        }
    } else {
        if (typeof addToViewingHistory === 'function') {
            console.log('🔍 Debug-History: Вызываем функцию addToViewingHistory');
            addToViewingHistory(attributes);
        } else {
            console.log('🔍 Debug-History: Функция addToViewingHistory не найдена');
        }
    }
}; 