<?php
include "config.php";
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['book_ids']) || !is_array($input['book_ids'])) {
    echo json_encode(['success' => false, 'message' => 'No book IDs provided']);
    exit;
}

$bookIds = $input['book_ids'];
if (empty($bookIds)) {
    echo json_encode(['success' => true, 'books' => []]);
    exit;
}
$bookIds = array_map('intval', $bookIds);
$placeholders = str_repeat('?,', count($bookIds) - 1) . '?';
$sql = "SELECT b.book_id, b.book_title, b.book_author, b.book_price, b.book_cover, c.category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.category_id 
        WHERE b.book_id IN ($placeholders)";

try {
    $result = executeQuery($con, $sql, $bookIds);
    
    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    
    echo json_encode(['success' => true, 'books' => $books]);
} catch (Exception $e) {
    error_log("Favorites details error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>