<?php
require "db.php";

header('Content-Type: application/json');

function sendJsonResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Неверный метод запроса.');
}

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;

if (!$id || !$type) {
    sendJsonResponse(false, 'ID и тип записи обязательны для удаления.');
}

try {
    $table = ($type === 'film') ? 'films' : 'serials';
    $stmt = $pdo->prepare("SELECT image FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagePath = "img/{$table}/" . $record['image'];
        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        
        sendJsonResponse(true, 'Запись успешно удалена.');
    }
    
    sendJsonResponse(false, 'Запись не найдена.');
    
} catch (PDOException $e) {
    sendJsonResponse(false, 'Ошибка при удалении записи: ' . $e->getMessage());
}
?>
