/**
 * JavaScript для управления модальными окнами
 */
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация модальных окон
    initModals();
    
    // Инициализация формы обратной связи
    initContactForm();
});

/**
 * Инициализация модальных окон
 */
function initModals() {
    // Находим все триггеры для модальных окон
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    // Добавляем обработчики для каждого триггера
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Получаем ID модального окна
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    // Обработчики для закрытия модальных окон
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            closeModal(modal);
        });
    });
    
    // Закрытие при клике вне модального окна
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
    
    // Закрытие по нажатию Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal-overlay[style*="display: flex"]');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

/**
 * Открытие модального окна
 */
function openModal(modal) {
    // Запретить прокрутку страницы
    document.body.style.overflow = 'hidden';
    
    // Отобразить модальное окно
    modal.style.display = 'flex';
    
    // Прокрутить модальное окно вверх (для повторного открытия)
    setTimeout(() => {
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.scrollTop = 0;
        }
        
        // Фокус на первый элемент формы, если это форма
        const firstInput = modal.querySelector('input, textarea');
        if (firstInput) {
            firstInput.focus();
        }
    }, 100);
}

/**
 * Закрытие модального окна
 */
function closeModal(modal) {
    // Разрешить прокрутку страницы
    document.body.style.overflow = '';
    
    // Скрыть модальное окно
    modal.style.display = 'none';
}

/**
 * Инициализация формы обратной связи
 */
function initContactForm() {
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем данные формы
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value.trim();
            
            // Проверяем заполнение обязательных полей
            if (!name || !email || !subject || !message) {
                showFormError('Пожалуйста, заполните все поля формы.');
                return;
            }
            
            // Проверяем корректность email
            if (!isValidEmail(email)) {
                showFormError('Пожалуйста, введите корректный email адрес.');
                return;
            }
            
            // Симулируем отправку данных (в реальном проекте здесь должен быть AJAX запрос)
            const formSubmitButton = contactForm.querySelector('.form-submit');
            formSubmitButton.disabled = true;
            formSubmitButton.textContent = 'Отправка...';
            
            // Имитация задержки отправки (в реальном проекте заменить на реальный запрос)
            setTimeout(function() {
                // Очищаем форму
                contactForm.reset();
                
                // Восстанавливаем кнопку
                formSubmitButton.disabled = false;
                formSubmitButton.textContent = 'Отправить сообщение';
                
                // Показываем сообщение об успешной отправке
                showThankYouMessage();
                
                // Закрываем модальное окно через 2 секунды
                setTimeout(function() {
                    const modal = document.getElementById('modal-contact');
                    closeModal(modal);
                }, 2000);
            }, 1000);
        });
    }
}

/**
 * Отображение ошибки формы
 */
function showFormError(message) {
    alert('Ошибка: ' + message);
}

/**
 * Отображение сообщения об успешной отправке
 */
function showThankYouMessage() {
    alert('Спасибо! Ваше сообщение успешно отправлено. Мы свяжемся с вами в ближайшее время.');
}

/**
 * Проверка корректности email
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
} 