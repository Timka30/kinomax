<?php
require "db.php";

header('Content-Type: application/json');

function handleImageUpload($type, $oldImage = '') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return $oldImage;
    }

    $imageTmpPath = $_FILES['image']['tmp_name'];
    $imageOriginalName = $_FILES['image']['name'];
    $uploadDir = ($type === 'film') ? 'img/films/' : 'img/serials/';
    $imageName = uniqid() . '-' . basename($imageOriginalName);
    $imagePath = $uploadDir . $imageName;

    $allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
    $fileType = mime_content_type($imageTmpPath);

    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception('Недопустимый формат файла.');
    }

    if (!move_uploaded_file($imageTmpPath, $imagePath)) {
        throw new Exception('Ошибка при сохранении изображения.');
    }

    return $imageName;
}

function validateInput($id, $name, $category_id, $type) {
    if (!$id || !$name || !$category_id || !$type) {
        throw new Exception('Все поля обязательны для заполнения.');
    }
}

function updateDatabase($pdo, $type, $name, $category_id, $imageName, $id) {
    $table = ($type === 'film') ? 'films' : 'serials';
    $sql = "UPDATE $table SET name = :name, category = :category, image = :image WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'category' => $category_id,
        'image' => $imageName,
        'id' => $id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса.']);
    exit;
}

try {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $category_id = $_POST['category'] ?? null;
    $type = $_POST['type'] ?? null;

    validateInput($id, $name, $category_id, $type);
    
    $imageName = handleImageUpload($type, $_POST['image'] ?? '');
    
    updateDatabase($pdo, $type, $name, $category_id, $imageName, $id);
    
    echo json_encode(['success' => true, 'message' => 'Данные успешно обновлены.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
