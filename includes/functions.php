<?php
// Helper Functions

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function uploadImage($file, $directory = 'assets/uploads/') {
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $filename = $file['name'];
    $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > 5000000) { // 5MB limit
        return ['success' => false, 'message' => 'File too large'];
    }
    
    $newFilename = uniqid() . '.' . $fileExt;
    $destination = $directory . $newFilename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newFilename, 'path' => $destination];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

function getMovies($pdo, $filters = [], $limit = null) {
    $sql = "SELECT DISTINCT m.* FROM movies m WHERE 1=1";
    $params = [];
    
    if (!empty($filters['genre'])) {
        $sql .= " AND m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = :genre)";
        $params[':genre'] = $filters['genre'];
    }
    
    if (!empty($filters['ott'])) {
        $sql .= " AND m.id IN (SELECT movie_id FROM movie_ott WHERE ott_id = :ott)";
        $params[':ott'] = $filters['ott'];
    }
    
    if (!empty($filters['search'])) {
        $sql .= " AND m.title LIKE :search";
        $params[':search'] = '%' . $filters['search'] . '%';
    }
    
    if (!empty($filters['featured'])) {
        $sql .= " AND m.is_featured = 1";
    }
    
    if (!empty($filters['trending'])) {
        $sql .= " AND m.is_trending = 1";
    }
    
    $sql .= " ORDER BY m.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getMovieBySlug($pdo, $slug) {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getMovieGenres($pdo, $movieId) {
    $stmt = $pdo->prepare("
        SELECT g.* FROM genres g
        INNER JOIN movie_genres mg ON g.id = mg.genre_id
        WHERE mg.movie_id = ?
    ");
    $stmt->execute([$movieId]);
    return $stmt->fetchAll();
}

function getMovieOTT($pdo, $movieId) {
    $stmt = $pdo->prepare("
        SELECT o.* FROM ott_platforms o
        INNER JOIN movie_ott mo ON o.id = mo.ott_id
        WHERE mo.movie_id = ?
    ");
    $stmt->execute([$movieId]);
    return $stmt->fetchAll();
}

function getAllGenres($pdo) {
    $stmt = $pdo->query("SELECT * FROM genres ORDER BY name");
    return $stmt->fetchAll();
}

function getAllOTT($pdo) {
    $stmt = $pdo->query("SELECT * FROM ott_platforms ORDER BY display_order");
    return $stmt->fetchAll();
}

function incrementViews($pdo, $movieId) {
    if (!isset($_SESSION['viewed_' . $movieId])) {
        $stmt = $pdo->prepare("UPDATE movies SET views = views + 1 WHERE id = ?");
        $stmt->execute([$movieId]);
        $_SESSION['viewed_' . $movieId] = true;
    }
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
    if ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
    return floor($diff / 31536000) . ' years ago';
}
?>
