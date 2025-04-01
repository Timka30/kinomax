<?php
require "db.php";

header('Content-Type: application/json');

function sendJsonResponse($data) {
    echo json_encode($data);
    exit;
}

$type = $_GET['type'] ?? 'film';
$category = $_GET['category'] ?? 'all'; 
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';
$action = $_GET['action'] ?? 'getMedia';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    $table = $type === 'film' ? 'films' : 'serials';
    $category_table = $type === 'film' ? 'category_films' : 'category_serials';

    // Получение категорий
    if ($action === 'getCategories') {
        $categories = $pdo->query("SELECT id, type FROM $category_table")
                         ->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse($categories);
    }

    // Получение данных для редактирования
    if ($id && $action === 'getMedia') {
        $sql = "SELECT m.id, m.name, m.image, c.type AS category, m.category AS category_id
                FROM $table m
                INNER JOIN $category_table c ON m.category = c.id 
                WHERE m.id = ?";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        sendJsonResponse($stmt->fetch(PDO::FETCH_ASSOC));
    }

    // Получение списка медиа
    $conditions = [];
    $params = [];
    
    if ($category !== 'all') {
        $conditions[] = "c.type = ?";
        $params[] = $category;
    }
    
    if (!empty($search)) {
        $conditions[] = "m.name LIKE ?";
        $params[] = "%$search%";
    }
    
    $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    
    switch($sort) {
        case 'name':
            $orderBy = 'ORDER BY m.name ASC';
            break;
        case 'category':
            $orderBy = 'ORDER BY c.type ASC';
            break;
        default:
            $orderBy = '';
    }

    $sql = "SELECT m.id, m.name, m.image, c.type AS category
            FROM $table m
            INNER JOIN $category_table c ON m.category = c.id
            $whereClause
            $orderBy";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    sendJsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    sendJsonResponse(['error' => 'Произошла ошибка: ' . $e->getMessage()]);
}
