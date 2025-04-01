<?php
session_start();
require 'vendor/db.php';
$query = $pdo->query("SELECT * FROM users");
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
html {
    height: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100%;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
}

.container {
    flex-grow: 1; /* Основной контент растягивает оставшееся пространство */
}

.footer {
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    background-color: #030311e6;
    color: white;
    position: relative;
    bottom: 0;
}

.oglavlenie-footer {
    font-size: 24px;
    margin-top: 20px;
    margin-bottom: 15px;
    font-weight: bold;
}

.stolbik1, .stolbik2, .stolbik3 {
    font-size: 16px;
    margin: 0 30px;
    margin-bottom: 20px;
}

.stolbik1 a, .stolbik2 a {
    text-decoration: none;
    color: white;
}

.stolbik1 p, .stolbik2 p {
    margin-top: 7.5px;
}

.stolbik3 img {
    cursor: pointer;
    margin-top: 5px;
    width: 28px;
    height: 28px;
    margin-left: 27.5px;
}
@media (max-width: 480px) {
    .footer {
        margin-top: 0px;
    }
    .admin-category {
       display: flex;
       flex-wrap: wrap;
       justify-content: space-between;
       z-index: 0;
   }
   .admin-category button {
       width: calc(50% - 5px); /* задает ширину кнопки, отнимает от нее 5px, чтобы не было переполнения */
       margin-bottom: 5px; /* добавляет отступ между рядами кнопок*/
   }
}
    </style>
</head>
<body>
<div class="container">
    <!--Шапка-->
    <?php require_once "blocks/header.php" ?>
    <!-- Конец шапки, начало основного блока -->
<div class="admin-conteiner">

<h2 style="text-align: center; font-size: 40px; margin-top: 50px">Админ-панель</h2>
<br>

<div class="admin-category">
    <button class="glow-on-hover" id="admin-button-1" type="submit">Добавить новый контент</button>
    <button class="glow-on-hover" id="admin-button-2" type="submit">Управление пользователями</button>
    <button class="glow-on-hover" id="admin-button-3" type="submit">Добавить пользователя</button>
    <button class="glow-on-hover" id="admin-button-4" type="submit">Управление медиа-контентом</button>
    <button class="glow-on-hover" id="admin-button-4" type="submit"><a href="update.php" style="text-decoration: none; color: white;">Обновление аниме кеша</a></button>
</div>


<?php
require "vendor/db.php"; // Подключение к базе данных

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category'];
    $type = $_POST['type']; // 'film' или 'serial'

    // Обработка загрузки изображения 
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        
        // Определяем папку для сохранения изображения в зависимости от типа 
        $uploadDir = ($type === 'film') ? 'img/films/' : 'img/serials/';
        $imagePath = $uploadDir . basename($imageName); // Путь для сохранения изображения // Проверка типа файла
        $allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
        $fileType = mime_content_type($imageTmpPath);

        if (in_array($fileType, $allowedTypes)) {
            // Перемещение загруженного файла в целевую директорию 
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                // Вставка данных в таблицу 
                if ($type === 'film') {
                    $sql = 'INSERT INTO films (name, category, image) VALUES (:name, :category, :image)';
                } else {
                    $sql = 'INSERT INTO serials (name, category, image) VALUES (:name, :category, :image)';
                }

                $stmt = $pdo->prepare($sql);
                $stmt->execute(['name' => $name, 'category' => $category_id, 'image' => $imageName]);

                // Вывод сообщения и редирект с помощью JavaScript
                echo "<script>
                    alert('Новая карточка добавлена');
                    window.location.href = 'admin.php';
                </script>";
                exit(); // Останавливаем выполнение скрипта
            } else {
                echo "Ошибка при загрузке изображения.";
            }
        } else {
            echo "Недопустимый формат файла. Допустимые форматы: PNG, JPG, WEBP.";
        }
    } else {
        echo "Ошибка при загрузке файла.";
    }
}

// Получение категорий для выпадающего списка
$film_categories = $pdo->query('SELECT id, type FROM category_films')->fetchAll(PDO::FETCH_OBJ);
$serial_categories = $pdo->query('SELECT id, type FROM category_serials')->fetchAll(PDO::FETCH_OBJ);
?>




<div class="admin-panel-cards">
    <form action="" method="post" enctype="multipart/form-data" class="admin-panel-cards-form">
        <label for="name">Название</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="type">Тип</label><br>
        <select id="type" name="type" onchange="updateCategories()" required>
            <option value="" selected disabled>Выберите тип</option>
            <option value="film">Фильм</option>
            <option value="serial">Сериал</option>
        </select><br>

        <label for="category">Категория</label><br>
        <select id="category" name="category" required>
            <option value="" selected disabled>Выберите категорию</option>
            <!-- Категории будут добавлены через JavaScript -->
        </select><br>

        <label for="image">Загрузить изображение</label><br>
        <input type="file" id="image" name="image" accept=".png, .jpg, .jpeg, .webp" required><br>
        <div class="admin-panel-cards-btn-center">
            <button class="glow-on-hover" type="submit">Добавить</button>
        </div>
    </form>
</div>

<br>

<div class="user-management-admin-container">
<table class="user-table-admin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Пароль</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Админ</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
  <?php foreach ($users as $user): ?>
  <tr>
    <td data-label="ID"><?= htmlspecialchars($user['id']) ?></td>
    <td data-label="Логин"><?= htmlspecialchars($user['login']) ?></td>
    <td data-label="Пароль"><?= htmlspecialchars($user['password']) ?></td>
    <td data-label="Телефон"><?= htmlspecialchars($user['phone']) ?></td>
    <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
    <td data-label="Админ"><?= htmlspecialchars($user['isAdmin']) ?></td>
    <td data-label="Действия" class="action-buttons">
      <button onclick="ShowDeleteModal(<?= $user['id'] ?>)" class="delete-button-admin">Удалить</button>
      <button onclick="ShowGiveAdminModal(<?= $user['id'] ?>)" class="give-button-admin">Админ</button>
    </td>
  </tr>
  <?php endforeach; ?>
</tbody>
</table>





        <!-- Форма добавления нового пользователя -->
        <form method="post" action="vendor/adminAction.php" class="add-user-form-admin">
            <input type="hidden" name="action" value="add">
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required>
            <label for="password">Пароль:</label>
            <input type="text" id="password" name="password" required>
            <label for="phone">Телефон:</label>
            <input type="tel" id="phone" name="phone" placeholder="+7 (___) ___-__-__" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="isAdmin">Админ (1/0):</label>
            <input type="number" id="isAdmin" name="isAdmin" min="0" max="1" required>
            <div class="add-user-form-admin-btn-center">
                <button type="submit" class="glow-on-hover" id="add-user-button-admin">Добавить</button>
            </div>
        </form>
    </div>

   <!-- Модальное окно для удаления -->
<div id="modal-delete-overlay" class="modal-admin-overlay">
    <div class="modal-admin">
        <h2>Подтверждение удаления</h2>
        <p>Вы действительно хотите удалить данного пользователя?</p>
        <form method="post" action="vendor/adminAction.php">
            <input type="hidden" id="user-id-input-delete" name="user_id">
            <input type="hidden" name="action" value="delete">
            <img src="vendor/generate_captcha.php?rand=<?php echo rand(); ?>" alt="Captcha" class="captcha-image-admin">
            <input type="text" name="captcha_input" placeholder="Введите капчу" required class="captcha-input-admin">
            <div class="modal-buttons-admin">
                <button type="submit" class="confirm-button-admin">Подтвердить</button>
                <button type="button" class="cancel-button-admin" onclick="closeDeleteModal()">Отмена</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно для админки -->
<div id="modal-admin-overlay" class="modal-admin-overlay">
    <div class="modal-admin">
        <h2>Подтверждение</h2>
        <p>Вы действительно хотите выдать админские права данному пользователю?</p>
        <form method="post" action="vendor/adminAction2.php">
            <input type="hidden" id="user-id-input-admin" name="user_id">
            <input type="hidden" name="action" value="grant_admin"> <!-- Изменили на 'grant_admin' -->
            <img src="vendor/generate_captcha.php?rand=<?php echo rand(); ?>" alt="Captcha" class="captcha-image-admin">
            <input type="text" name="captcha_input" placeholder="Введите капчу" required class="captcha-input-admin">
            <div class="modal-buttons-admin">
                <button type="submit" class="confirm-button-admin">Подтвердить</button>
                <button type="button" class="cancel-button-admin" onclick="closeAdminModal()">Отмена</button>
            </div>
        </form>
    </div>
</div>



<div class="media-content-admin">
    <h2>Управление медиа-контентом</h2>
    <div class="media-controls">
        <button id="movies-btn">Фильмы</button>
        <button id="series-btn">Сериалы</button>
    </div>
    <div class="media-table-container">
        <div class="media-filters">
            <input type="text" id="search-input" placeholder="Поиск по названию">
            <select id="category-filter">
                <option value="all">Все категории</option>
            </select>
            <button id="sort-by-name">Сортировка по названию</button>
            <button id="sort-by-category">Сортировка по категории</button>
        </div>
        <table class="media-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Фотография</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="media-table-body">
                <!-- Данные будут загружаться через AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно редактирования -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.querySelector('#edit-modal').style.display='none'">&times;</span>
        <form id="edit-form" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id" />
            <input type="hidden" id="edit-image" name="image" /> <!-- Скрытое поле для старого изображения -->

            <label for="edit-name">Название:</label>
            <input type="text" id="edit-name" name="name" required />

            <label for="edit-category">Категория:</label>
            <select id="edit-category" name="category" required>
                <!-- Категории будут загружаться динамически -->
            </select>

            <label for="edit-image">Изображение:</label>
            <input type="text" id="edit-image-display" disabled placeholder="Текущее изображение" /> <!-- Отображение текущего изображения -->
            <input type="file" id="new-image" name="image" />

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>


<!-- Модальное окно удаления -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.querySelector('#delete-modal').style.display='none'">&times;</span>
        <p>Вы уверены, что хотите удалить этот элемент?</p>
        <button id="delete-confirm">Удалить</button>
        <button id="delete-cancel">Отмена</button>
    </div>
</div>



        </div>
</div>
<!-- footer -->
<?php require_once "blocks/footer.php" ?>
<script src="js/script.js"></script>
<script>
    function updateCategories() {
            const typeSelect = document.getElementById('type');
            const categorySelect = document.getElementById('category');
            const filmCategories = <?php echo json_encode($film_categories); ?>;
            const serialCategories = <?php echo json_encode($serial_categories); ?>;

            // Очищаем выпадающий список категорий
            categorySelect.innerHTML = '<option value="">Выберите категорию</option>';

            // Заполняем выпадающий список в зависимости от выбранного типа 
            const selectedType = typeSelect.value;
            let categoriesToDisplay = [];

            if (selectedType === 'film') {
                categoriesToDisplay = filmCategories;
            } else if (selectedType === 'serial') {
                categoriesToDisplay = serialCategories;
            }

            // Добавляем категории в выпадающий список 
            categoriesToDisplay.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.type;
                categorySelect.appendChild(option);
            });
        }
        // Функция для открытия модального окна удаления
function ShowDeleteModal(userId) {
    // Показываем модальное окно для удаления
    document.getElementById('modal-delete-overlay').style.display = 'flex';
    // Устанавливаем user_id в скрытое поле формы
    document.getElementById('user-id-input-delete').value = userId;
}

// Функция для открытия модального окна для админки
function ShowGiveAdminModal(userId) {
    // Показываем модальное окно для выдачи админских прав
    document.getElementById('modal-admin-overlay').style.display = 'flex';
    // Устанавливаем user_id в скрытое поле формы
    document.getElementById('user-id-input-admin').value = userId;
}

// Функция для закрытия модального окна удаления
function closeDeleteModal() {
    document.getElementById('modal-delete-overlay').style.display = 'none';
}

// Функция для закрытия модального окна админки
function closeAdminModal() {
    document.getElementById('modal-admin-overlay').style.display = 'none';
}



document.addEventListener("DOMContentLoaded", function () {
    const button1 = document.querySelector("#admin-button-1");
    const button2 = document.querySelector("#admin-button-2");
    const button3 = document.querySelector("#admin-button-3");
    const button4 = document.querySelector("#admin-button-4");

    const adminPanelCards = document.querySelector(".admin-panel-cards");
    const userTableAdmin = document.querySelector(".user-table-admin");
    const addUserFormAdmin = document.querySelector(".add-user-form-admin");
    const mediaContentAdmin = document.querySelector(".media-content-admin"); // Новый блок

    let activeBlock = null;

    function hideAllBlocks() {
        adminPanelCards.style.display = "none";
        userTableAdmin.style.display = "none";
        addUserFormAdmin.style.display = "none";
        mediaContentAdmin.style.display = "none"; // Скрыть новый блок
    }

    button1.addEventListener("click", function () {
        if (activeBlock === adminPanelCards) {
            adminPanelCards.style.display = "none";
            activeBlock = null;
        } else {
            hideAllBlocks();
            adminPanelCards.style.display = "block";
            activeBlock = adminPanelCards;
        }
    });

    button2.addEventListener("click", function () {
        if (activeBlock === userTableAdmin) {
            userTableAdmin.style.display = "none";
            activeBlock = null;
        } else {
            hideAllBlocks();
            userTableAdmin.style.display = "block";
            activeBlock = userTableAdmin;
        }
    });

    button3.addEventListener("click", function () {
        if (activeBlock === addUserFormAdmin) {
            addUserFormAdmin.style.display = "none";
            activeBlock = null;
        } else {
            hideAllBlocks();
            addUserFormAdmin.style.display = "block";
            activeBlock = addUserFormAdmin;
        }
    });

    button4.addEventListener("click", function () {
        if (activeBlock === mediaContentAdmin) {
            mediaContentAdmin.style.display = "none";
            activeBlock = null;
        } else {
            hideAllBlocks();
            mediaContentAdmin.style.display = "block";
            activeBlock = mediaContentAdmin;
        }
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Удаляем все нецифровые символы

        // Если номер начинается с 7, убираем её из value
        if (value.startsWith('7')) {
            value = value.slice(1);
        }

        // Ограничиваем строку до 10 цифр
        value = value.slice(0, 10);

        // Форматируем
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue += '+7 ';
        }
        if (value.length > 0) {
            formattedValue += '(' + value.slice(0, 3);
        }
        if (value.length >= 3) {
            formattedValue += ') ' + value.slice(3, 6);
        }
        if (value.length >= 6) {
            formattedValue += '-' + value.slice(6, 8);
        }
        if (value.length >= 8) {
            formattedValue += '-' + value.slice(8, 10);
        }

        e.target.value = formattedValue; // Обновляем значение поля
    });
});





document.addEventListener("DOMContentLoaded", function () {
    const moviesBtn = document.querySelector("#movies-btn");
    const seriesBtn = document.querySelector("#series-btn");
    const categoryFilter = document.querySelector("#category-filter");
    const searchInput = document.querySelector("#search-input");
    const sortByName = document.querySelector("#sort-by-name");
    const sortByCategory = document.querySelector("#sort-by-category");
    const tableBody = document.querySelector("#media-table-body");

    let currentType = "film"; // По умолчанию фильмы

    function loadCategories() {
        fetch(`vendor/getMedia.php?action=getCategories&type=${currentType}`)
            .then((response) => response.json())
            .then((categories) => {
                categoryFilter.innerHTML = '<option value="all">Все категории</option>';
                categories.forEach((category) => {
                    const option = document.createElement("option");
                    option.value = category.type;
                    option.textContent = category.type;
                    categoryFilter.appendChild(option);
                });
            })
            .catch((error) => console.error("Ошибка при загрузке категорий:", error));
    }

    function loadMedia() {
        const search = searchInput.value;
        const category = categoryFilter.value;
        const sort = sortByName.classList.contains("active")
            ? "name"
            : sortByCategory.classList.contains("active")
            ? "category"
            : "";

        const params = new URLSearchParams({
            type: currentType,
            category: category,
            search: search,
            sort: sort,
        });

        fetch(`vendor/getMedia.php?${params.toString()}`)
            .then((response) => response.json())
            .then((data) => {
                tableBody.innerHTML = "";
                data.forEach((item) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.category}</td>
                        <td>
                            <div class="movie-header" id="movie-header-table" 
                                style="background-image: url(img/${currentType}s/${item.image}); background-size: cover;">
                            </div>
                        </td>
                        <td>
                            <button class="edit-btn" data-id="${item.id}">Редактировать</button><br>
                            <button class="delete-btn" data-id="${item.id}">Удалить</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                // Добавляем обработчики для кнопок
                const editButtons = document.querySelectorAll(".edit-btn");
                const deleteButtons = document.querySelectorAll(".delete-btn");

                editButtons.forEach((button) => {
                    button.addEventListener("click", handleEditClick);
                });

                deleteButtons.forEach((button) => {
                    button.addEventListener("click", handleDeleteClick);
                });
            })
            .catch((error) => console.error("Ошибка при загрузке данных:", error));
    }

    function handleEditClick(event) {
    const id = event.target.dataset.id;

    // Загрузка данных для редактирования
    fetch(`vendor/getMedia.php?action=getMedia&type=${currentType}&id=${id}`)
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                // Заполняем форму редактирования
                document.querySelector("#edit-name").value = data.name;
                document.querySelector("#edit-id").value = data.id;
                document.querySelector("#edit-image").value = data.image; // старое изображение
                document.querySelector("#new-image").value = ""; // Новый файл изображения

                // Загрузка категорий с установкой текущей
                fetch(`vendor/getMedia.php?action=getCategories&type=${currentType}`)
                    .then((response) => response.json())
                    .then((categories) => {
                        const editCategory = document.querySelector("#edit-category");
                        editCategory.innerHTML = ""; // Очистка списка категорий

                        categories.forEach((category) => {
                            const option = document.createElement("option");
                            option.value = category.id;
                            option.textContent = category.type;
                            if (category.type === data.category) {
                                option.selected = true; // Установить текущую категорию
                            }
                            editCategory.appendChild(option);
                        });

                        // Показываем модальное окно
                        document.querySelector("#edit-modal").style.display = "block";
                    })
                    .catch((error) => console.error("Ошибка при загрузке категорий:", error));
            }
        })
        .catch((error) => console.error("Ошибка при загрузке данных для редактирования:", error));
}


    // Обработчик отправки формы редактирования
    document.querySelector("#edit-form").addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        formData.append("type", currentType);

        fetch("vendor/updateMedia.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    loadMedia();
                    document.querySelector("#edit-modal").style.display = "none"; // Закрыть модальное окно
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => console.error("Ошибка при отправке данных:", error));
    });

    function handleDeleteClick(event) {
        const id = event.target.dataset.id; // Получаем ID записи
        const modal = document.querySelector("#delete-modal"); // Модальное окно

        // Показываем модальное окно
        modal.style.display = "block";

        // Устанавливаем обработчик подтверждения удаления
        const confirmDelete = document.querySelector("#delete-confirm");
        const cancelDelete = document.querySelector("#delete-cancel");

        confirmDelete.onclick = function () {
            fetch("vendor/deleteMedia.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    id: id,
                    type: currentType, // Передаем текущий тип ('film' или 'serial')
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message);
                        modal.style.display = "none";
                        loadMedia(); // Перезагружаем данные
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => console.error("Ошибка при удалении записи:", error));
        };

        // Отмена удаления
        cancelDelete.onclick = function () {
            modal.style.display = "none";
        };
    }

    // Закрытие модального окна при клике вне его
    window.onclick = function (event) {
        const modal = document.querySelector("#delete-modal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    moviesBtn.addEventListener("click", () => {
        currentType = "film";
        loadCategories();
        loadMedia();
    });

    seriesBtn.addEventListener("click", () => {
        currentType = "serial";
        loadCategories();
        loadMedia();
    });

    categoryFilter.addEventListener("change", loadMedia);
    searchInput.addEventListener("input", loadMedia);
    sortByName.addEventListener("click", () => {
        sortByName.classList.add("active");
        sortByCategory.classList.remove("active");
        loadMedia();
    });

    sortByCategory.addEventListener("click", () => {
        sortByCategory.classList.add("active");
        sortByName.classList.remove("active");
        loadMedia();
    });

    loadCategories();
    loadMedia();
});

</script>
</body>
</html>