<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode(['success' => false, 'message' => 'Query too short']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT id, title, slug, release_year, rating, poster_url 
        FROM movies 
        WHERE title LIKE ? 
        ORDER BY rating DESC, views DESC 
        LIMIT 10
    ");
    $stmt->execute(['%' . $query . '%']);
    $movies = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'movies' => $movies
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Search failed'
    ]);
}
?>
