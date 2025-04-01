/**
 * Система комментариев для фильмов и сериалов
 */
class CommentsSystem {
    constructor(options) {
        this.type = options.type || 'film'; // film или serial
        this.kinopoiskId = options.kinopoiskId || 0;
        this.containerSelector = options.containerSelector || '#comments-container';
        this.formSelector = options.formSelector || '#comment-form';
        this.isLoggedIn = options.isLoggedIn || false;
        
        this.container = document.querySelector(this.containerSelector);
        this.form = document.querySelector(this.formSelector);
        
        this.init();
    }
    
    /**
     * Инициализация системы комментариев
     */
    init() {
        // Загружаем комментарии при инициализации
        this.loadComments();
        
        // Добавляем обработчик отправки формы, если авторизован
        if (this.isLoggedIn && this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitComment();
            });
            
            // Инициализация рейтинга
            this.initRatingStars();
        }
    }
    
    /**
     * Загрузка комментариев с сервера
     */
    loadComments() {
        const url = `comments/comment_handler.php?action=get&type=${this.type}&kinopoiskId=${this.kinopoiskId}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.renderComments(data.comments);
                    this.updateStats(data.stats);
                } else {
                    console.error('Ошибка загрузки комментариев:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка при выполнении запроса:', error);
            });
    }
    
    /**
     * Отправка комментария
     */
    submitComment() {
        const textArea = this.form.querySelector('textarea');
        const ratingInput = this.form.querySelector('input[name="rating"]');
        
        if (!textArea || !ratingInput) {
            alert('Ошибка: Не найдены поля формы');
            return;
        }
        
        const text = textArea.value.trim();
        const rating = parseInt(ratingInput.value);
        
        if (!text) {
            alert('Пожалуйста, введите текст комментария');
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('type', this.type);
        formData.append('kinopoiskId', this.kinopoiskId);
        formData.append('text', text);
        formData.append('rating', rating);
        
        fetch('comments/comment_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Очищаем форму
                textArea.value = '';
                this.resetRatingStars();
                
                // Обновляем комментарии и статистику
                this.renderComments(data.comments);
                this.updateStats(data.stats);
            } else {
                alert(`Ошибка: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Ошибка при отправке комментария:', error);
            alert('Произошла ошибка при отправке комментария');
        });
    }
    
    /**
     * Удаление комментария
     */
    deleteComment(commentId) {
        if (!confirm('Вы уверены, что хотите удалить этот комментарий?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('type', this.type);
        formData.append('kinopoiskId', this.kinopoiskId);
        formData.append('commentId', commentId);
        
        fetch('comments/comment_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем комментарии и статистику
                this.renderComments(data.comments);
                this.updateStats(data.stats);
            } else {
                alert(`Ошибка: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Ошибка при удалении комментария:', error);
            alert('Произошла ошибка при удалении комментария');
        });
    }
    
    /**
     * Отрисовка комментариев
     */
    renderComments(comments) {
        if (!this.container) return;
        
        const commentsHtml = comments.length > 0 
            ? comments.map(comment => this.renderComment(comment)).join('')
            : '<div class="no-comments">Нет комментариев. Будьте первым, кто оставит отзыв!</div>';
        
        this.container.innerHTML = commentsHtml;
        
        // Добавляем обработчики для кнопок удаления
        if (this.isLoggedIn) {
            const deleteButtons = this.container.querySelectorAll('.delete-comment');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const commentId = e.target.dataset.id;
                    this.deleteComment(commentId);
                });
            });
        }
    }
    
    /**
     * Отрисовка одного комментария
     */
    renderComment(comment) {
        const username = comment.user;
        const isCurrentUser = this.isLoggedIn && username === currentUserName;
        const deleteButton = isCurrentUser 
            ? `<button class="delete-comment" data-id="${comment.id}">✕</button>` 
            : '';
        
        return `
            <div class="comment">
                <div class="comment-header">
                    <div class="comment-user">${username}</div>
                    <div class="comment-rating">${this.renderRatingStars(comment.rating)}</div>
                    ${deleteButton}
                </div>
                <div class="comment-text">${comment.text}</div>
                <div class="comment-date">${comment.date}</div>
            </div>
        `;
    }
    
    /**
     * Отрисовка звезд рейтинга (только для отображения)
     */
    renderRatingStars(rating) {
        let starsHtml = '';
        
        for (let i = 1; i <= 10; i++) {
            const starClass = i <= rating ? 'filled' : 'empty';
            starsHtml += `<span class="star ${starClass}">★</span>`;
        }
        
        return `<div class="stars">${starsHtml}</div>`;
    }
    
    /**
     * Обновление статистики
     */
    updateStats(stats) {
        const statsContainer = document.querySelector('#comments-stats');
        if (!statsContainer) return;
        
        statsContainer.innerHTML = `
            <div class="stats-box">
                <div class="rating-value">${stats.average_rating}</div>
                <div class="rating-count">${stats.count} отзывов</div>
                <div class="rating-stars">${this.renderRatingStars(Math.round(stats.average_rating))}</div>
            </div>
        `;
    }
    
    /**
     * Инициализация интерактивных звезд рейтинга в форме
     */
    initRatingStars() {
        const ratingContainer = this.form.querySelector('.rating-input');
        const ratingInput = this.form.querySelector('input[name="rating"]');
        
        if (!ratingContainer || !ratingInput) return;
        
        const stars = ratingContainer.querySelectorAll('.star');
        
        stars.forEach((star, index) => {
            // При наведении
            star.addEventListener('mouseover', () => {
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add('hover');
                }
            });
            
            // При отведении мыши
            star.addEventListener('mouseout', () => {
                stars.forEach(s => s.classList.remove('hover'));
            });
            
            // При клике
            star.addEventListener('click', () => {
                const rating = index + 1;
                ratingInput.value = rating;
                
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
        });
    }
    
    /**
     * Сброс рейтинга в форме
     */
    resetRatingStars() {
        const ratingContainer = this.form.querySelector('.rating-input');
        const ratingInput = this.form.querySelector('input[name="rating"]');
        
        if (!ratingContainer || !ratingInput) return;
        
        ratingInput.value = 5; // Ставим средний рейтинг
        
        const stars = ratingContainer.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < 5) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }
}

// Глобальная переменная для имени текущего пользователя
let currentUserName = '';

// Функция для инициализации системы комментариев
function initComments(options) {
    currentUserName = options.currentUserName || '';
    document.addEventListener('DOMContentLoaded', () => {
        new CommentsSystem(options);
    });
} 