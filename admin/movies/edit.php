<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$success = '';
$error = '';
$movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$movie_id) {
    header('Location: manage.php');
    exit;
}

// Get movie details
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    header('Location: manage.php');
    exit;
}

// Get movie genres
$stmt = $pdo->prepare("SELECT genre_id FROM movie_genres WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movieGenres = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get movie platforms
$stmt = $pdo->prepare("SELECT ott_id FROM movie_ott WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$moviePlatforms = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = sanitize($_POST['title']);
        $slug = sanitize($_POST['slug']);
        $description = sanitize($_POST['description']);
        $release_year = (int)$_POST['release_year'];
        $duration = sanitize($_POST['duration']);
        $rating = (float)$_POST['rating'];
        $poster_url = sanitize($_POST['poster_url']);
        $banner_url = sanitize($_POST['banner_url']);
        $trailer_url = sanitize($_POST['trailer_url']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_trending = isset($_POST['is_trending']) ? 1 : 0;
        
        // Download links
        $download_480p = sanitize($_POST['download_480p']);
        $download_720p = sanitize($_POST['download_720p']);
        $download_1080p = sanitize($_POST['download_1080p']);
        $download_4k = sanitize($_POST['download_4k']);
        
        // Screenshots (convert array to JSON)
        $screenshots = [];
        if (!empty($_POST['screenshots'])) {
            $screenshots = array_filter(array_map('trim', explode("\n", $_POST['screenshots'])));
        }
        $screenshots_json = json_encode($screenshots);
        
        $stmt = $pdo->prepare("
            UPDATE movies SET 
                title = ?, slug = ?, description = ?, poster_url = ?, banner_url = ?, 
                release_year = ?, duration = ?, rating = ?, trailer_url = ?, 
                is_featured = ?, is_trending = ?,
                screenshots = ?, download_480p = ?, download_720p = ?, download_1080p = ?, download_4k = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $title, $slug, $description, $poster_url, $banner_url, $release_year,
            $duration, $rating, $trailer_url, $is_featured, $is_trending,
            $screenshots_json, $download_480p, $download_720p, $download_1080p, $download_4k,
            $movie_id
        ]);
        
        // Update genres
        $pdo->exec("DELETE FROM movie_genres WHERE movie_id = $movie_id");
        if (!empty($_POST['genres'])) {
            $stmt = $pdo->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
            foreach ($_POST['genres'] as $genre_id) {
                $stmt->execute([$movie_id, $genre_id]);
            }
        }
        
        // Update OTT platforms
        $pdo->exec("DELETE FROM movie_ott WHERE movie_id = $movie_id");
        if (!empty($_POST['platforms'])) {
            $stmt = $pdo->prepare("INSERT INTO movie_ott (movie_id, ott_id) VALUES (?, ?)");
            foreach ($_POST['platforms'] as $ott_id) {
                $stmt->execute([$movie_id, $ott_id]);
            }
        }
        
        $success = 'Movie updated successfully!';
        
        // Refresh movie data
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$movie_id]);
        $movie = $stmt->fetch();
        
        $stmt = $pdo->prepare("SELECT genre_id FROM movie_genres WHERE movie_id = ?");
        $stmt->execute([$movie_id]);
        $movieGenres = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $pdo->prepare("SELECT ott_id FROM movie_ott WHERE movie_id = ?");
        $stmt->execute([$movie_id]);
        $moviePlatforms = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all genres and platforms
$genres = getAllGenres($pdo);
$platforms = getAllOTT($pdo);

// Parse screenshots
$screenshots = !empty($movie['screenshots']) ? json_decode($movie['screenshots'], true) : [];
$screenshotsText = is_array($screenshots) ? implode("\n", $screenshots) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #0F0F0F;
            color: #fff;
        }
        
        .admin-header {
            background: #1A1A1A;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #E50914;
        }
        
        .admin-content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-title {
            font-size: 2rem;
            margin-bottom: 30px;
        }
        
        .form-container {
            background: #1A1A1A;
            padding: 40px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #B3B3B3;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #E50914;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #E50914;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #ff0a16;
        }
        
        .btn-secondary {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10B981;
            color: #6EE7B7;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #EF4444;
            color: #FCA5A5;
        }
        
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .form-section h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: #E50914;
        }
        
        .help-text {
            font-size: 0.85rem;
            color: #B3B3B3;
            margin-top: 5px;
        }
        
        .movie-preview {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255,255,255,0.02);
            border-radius: 10px;
        }
        
        .movie-preview img {
            width: 150px;
            height: 225px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .movie-preview-info {
            flex: 1;
        }
        
        .movie-preview-info h2 {
            margin-bottom: 10px;
        }
        
        .movie-preview-info p {
            color: #B3B3B3;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <i class="fas fa-film"></i> <?= SITE_NAME ?> Admin
        </div>
        <div>
            <a href="manage.php" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-arrow-left"></i> Back to Movies
            </a>
            <a href="../logout.php" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #EF4444;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <h1 class="page-title">Edit Movie</h1>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>
        
        <!-- Movie Preview -->
        <div class="movie-preview">
            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" onerror="this.src='https://via.placeholder.com/150x225/1A1A1A/E50914?text=No+Image'">
            <div class="movie-preview-info">
                <h2><?= htmlspecialchars($movie['title']) ?></h2>
                <p><i class="fas fa-calendar"></i> <?= $movie['release_year'] ?> • <i class="fas fa-clock"></i> <?= $movie['duration'] ?></p>
                <p><i class="fas fa-star" style="color: #FCD34D;"></i> <?= $movie['rating'] ?>/10</p>
                <p><i class="fas fa-eye"></i> <?= number_format($movie['views']) ?> views</p>
            </div>
        </div>
        
        <form method="POST" class="form-container">
            <!-- Basic Information -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Movie Title *</label>
                        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($movie['title']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>URL Slug *</label>
                        <input type="text" name="slug" class="form-control" required placeholder="movie-title-here" value="<?= htmlspecialchars($movie['slug']) ?>">
                        <div class="help-text">Use lowercase, hyphens instead of spaces</div>
                    </div>
                    
                    <div class="form-group">
                        <label>Release Year *</label>
                        <input type="number" name="release_year" class="form-control" required min="1900" max="2030" value="<?= $movie['release_year'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Duration *</label>
                        <input type="text" name="duration" class="form-control" required placeholder="2h 15min" value="<?= htmlspecialchars($movie['duration']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Rating (out of 10) *</label>
                        <input type="number" name="rating" class="form-control" required step="0.1" min="0" max="10" value="<?= $movie['rating'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Trailer URL</label>
                        <input type="url" name="trailer_url" class="form-control" placeholder="YouTube URL" value="<?= htmlspecialchars($movie['trailer_url']) ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Description *</label>
                        <textarea name="description" class="form-control" required><?= htmlspecialchars($movie['description']) ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Images -->
            <div class="form-section">
                <h3><i class="fas fa-image"></i> Images</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Poster URL *</label>
                        <input type="text" name="poster_url" class="form-control" required placeholder="500x750px recommended" value="<?= htmlspecialchars($movie['poster_url']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Banner URL *</label>
                        <input type="text" name="banner_url" class="form-control" required placeholder="1920x600px recommended" value="<?= htmlspecialchars($movie['banner_url']) ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Screenshots (One URL per line)</label>
                        <textarea name="screenshots" class="form-control" placeholder="https://example.com/screenshot1.jpg&#10;https://example.com/screenshot2.jpg&#10;https://example.com/screenshot3.jpg"><?= htmlspecialchars($screenshotsText) ?></textarea>
                        <div class="help-text">Enter each screenshot URL on a new line</div>
                    </div>
                </div>
            </div>
            
            <!-- Download Links -->
            <div class="form-section">
                <h3><i class="fas fa-download"></i> Download Links</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>480p Download Link</label>
                        <input type="text" name="download_480p" class="form-control" placeholder="SD Quality" value="<?= htmlspecialchars($movie['download_480p']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>720p Download Link</label>
                        <input type="text" name="download_720p" class="form-control" placeholder="HD Quality" value="<?= htmlspecialchars($movie['download_720p']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>1080p Download Link</label>
                        <input type="text" name="download_1080p" class="form-control" placeholder="Full HD" value="<?= htmlspecialchars($movie['download_1080p']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>4K Download Link</label>
                        <input type="text" name="download_4k" class="form-control" placeholder="Ultra HD" value="<?= htmlspecialchars($movie['download_4k']) ?>">
                    </div>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="form-section">
                <h3><i class="fas fa-tags"></i> Categories</h3>
                
                <div class="form-group">
                    <label>Genres</label>
                    <div class="checkbox-group">
                        <?php foreach ($genres as $genre): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" id="genre_<?= $genre['id'] ?>" <?= in_array($genre['id'], $movieGenres) ? 'checked' : '' ?>>
                                <label for="genre_<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>OTT Platforms</label>
                    <div class="checkbox-group">
                        <?php foreach ($platforms as $platform): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="platforms[]" value="<?= $platform['id'] ?>" id="platform_<?= $platform['id'] ?>" <?= in_array($platform['id'], $moviePlatforms) ? 'checked' : '' ?>>
                                <label for="platform_<?= $platform['id'] ?>"><?= htmlspecialchars($platform['name']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Status -->
            <div class="form-section" style="border: none;">
                <h3><i class="fas fa-toggle-on"></i> Status</h3>
                
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="is_featured" id="is_featured" <?= $movie['is_featured'] ? 'checked' : '' ?>>
                        <label for="is_featured">Featured Movie</label>
                    </div>
                    
                    <div class="checkbox-item">
                        <input type="checkbox" name="is_trending" id="is_trending" <?= $movie['is_trending'] ? 'checked' : '' ?>>
                        <label for="is_trending">Trending Movie</label>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Movie
                </button>
                <a href="manage.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <a href="../../pages/movie-detail.php?slug=<?= $movie['slug'] ?>" class="btn" style="background: rgba(16, 185, 129, 0.2); color: #10B981;" target="_blank">
                    <i class="fas fa-eye"></i> View Movie
                </a>
            </div>
        </form>
    </div>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
