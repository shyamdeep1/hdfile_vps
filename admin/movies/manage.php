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

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Delete related records first
        $pdo->exec("DELETE FROM movie_genres WHERE movie_id = $id");
        $pdo->exec("DELETE FROM movie_ott WHERE movie_id = $id");
        $pdo->exec("DELETE FROM movies WHERE id = $id");
        $success = 'Movie deleted successfully!';
    } catch (Exception $e) {
        $error = 'Error deleting movie: ' . $e->getMessage();
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Search
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$whereClause = '';
if ($search) {
    $whereClause = "WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

// Get total movies
$totalMovies = $pdo->query("SELECT COUNT(*) FROM movies $whereClause")->fetchColumn();
$totalPages = ceil($totalMovies / $perPage);

// Get movies
$stmt = $pdo->query("
    SELECT m.*, 
    (SELECT COUNT(*) FROM movie_genres WHERE movie_id = m.id) as genre_count,
    (SELECT COUNT(*) FROM movie_ott WHERE movie_id = m.id) as ott_count
    FROM movies m 
    $whereClause 
    ORDER BY m.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
$movies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - <?= SITE_NAME ?></title>
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
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 2rem;
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
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        .btn-edit {
            background: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
        }
        
        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
        }
        
        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.3);
        }
        
        .search-bar {
            background: #1A1A1A;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
        }
        
        .movies-table {
            background: #1A1A1A;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: rgba(229, 9, 20, 0.1);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        th {
            font-weight: 600;
            color: #E50914;
        }
        
        tbody tr:hover {
            background: rgba(255,255,255,0.02);
        }
        
        .movie-poster-thumb {
            width: 60px;
            height: 90px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 5px;
        }
        
        .badge-featured {
            background: rgba(234, 179, 8, 0.2);
            color: #FCD34D;
        }
        
        .badge-trending {
            background: rgba(239, 68, 68, 0.2);
            color: #F87171;
        }
        
        .badge-normal {
            background: rgba(107, 114, 128, 0.2);
            color: #9CA3AF;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
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
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a, .pagination span {
            padding: 10px 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: #E50914;
        }
        
        .pagination .active {
            background: #E50914;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #1A1A1A;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #E50914;
        }
        
        .stat-label {
            color: #B3B3B3;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #B3B3B3;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <i class="fas fa-film"></i> <?= SITE_NAME ?> Admin
        </div>
        <div>
            <a href="../index.php" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="../logout.php" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #EF4444;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <div class="page-header">
            <h1 class="page-title">Manage Movies</h1>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
        </div>
        
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
        
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value"><?= $totalMovies ?></div>
                <div class="stat-label">Total Movies</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $pdo->query("SELECT COUNT(*) FROM movies WHERE is_featured = 1")->fetchColumn() ?>
                </div>
                <div class="stat-label">Featured Movies</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $pdo->query("SELECT COUNT(*) FROM movies WHERE is_trending = 1")->fetchColumn() ?>
                </div>
                <div class="stat-label">Trending Movies</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $pdo->query("SELECT SUM(views) FROM movies")->fetchColumn() ?>
                </div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="search-input" placeholder="Search movies by title or description..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if ($search): ?>
                <a href="manage.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
        
        <!-- Movies Table -->
        <div class="movies-table">
            <?php if (count($movies) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Rating</th>
                            <th>Genres</th>
                            <th>Platforms</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td><?= $movie['id'] ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                                         alt="<?= htmlspecialchars($movie['title']) ?>" 
                                         class="movie-poster-thumb"
                                         onerror="this.src='https://via.placeholder.com/60x90/1A1A1A/E50914?text=No+Image'">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($movie['title']) ?></strong>
                                    <br>
                                    <small style="color: #B3B3B3;"><?= $movie['duration'] ?></small>
                                </td>
                                <td><?= $movie['release_year'] ?></td>
                                <td>
                                    <i class="fas fa-star" style="color: #FCD34D;"></i> 
                                    <?= $movie['rating'] ?>
                                </td>
                                <td>
                                    <span class="badge badge-normal">
                                        <?= $movie['genre_count'] ?> Genre<?= $movie['genre_count'] != 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-normal">
                                        <?= $movie['ott_count'] ?> Platform<?= $movie['ott_count'] != 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($movie['is_featured']): ?>
                                        <span class="badge badge-featured">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($movie['is_trending']): ?>
                                        <span class="badge badge-trending">
                                            <i class="fas fa-fire"></i> Trending
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!$movie['is_featured'] && !$movie['is_trending']): ?>
                                        <span class="badge badge-normal">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($movie['views']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="../../pages/movie-detail.php?slug=<?= $movie['slug'] ?>" 
                                           class="btn btn-sm" 
                                           style="background: rgba(16, 185, 129, 0.2); color: #10B981;"
                                           target="_blank"
                                           title="View Movie">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?= $movie['id'] ?>" 
                                           class="btn btn-sm btn-edit"
                                           title="Edit Movie">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?= $movie['id'] ?>" 
                                           class="btn btn-sm btn-delete"
                                           onclick="return confirm('Are you sure you want to delete this movie?')"
                                           title="Delete Movie">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-film"></i>
                    <h3>No Movies Found</h3>
                    <p>Start by adding your first movie!</p>
                    <br>
                    <a href="add.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Movie
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
