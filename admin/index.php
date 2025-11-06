<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Get statistics
$stats = [
    'total_movies' => $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn(),
    'total_genres' => $pdo->query("SELECT COUNT(*) FROM genres")->fetchColumn(),
    'total_platforms' => $pdo->query("SELECT COUNT(*) FROM ott_platforms")->fetchColumn(),
    'total_views' => $pdo->query("SELECT SUM(views) FROM movies")->fetchColumn(),
    'featured_movies' => $pdo->query("SELECT COUNT(*) FROM movies WHERE is_featured = 1")->fetchColumn(),
    'trending_movies' => $pdo->query("SELECT COUNT(*) FROM movies WHERE is_trending = 1")->fetchColumn(),
];

// Get recent movies
$recentMovies = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-content {
            padding: 30px;
        }
        
        .page-title {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .breadcrumb {
            color: #B3B3B3;
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: #1A1A1A;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #E50914;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(229, 9, 20, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #E50914;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #B3B3B3;
            font-size: 0.9rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        
        .table {
            width: 100%;
            background: #1A1A1A;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .table th {
            background: rgba(229, 9, 20, 0.1);
            color: #E50914;
            font-weight: 600;
        }
        
        .table tr:hover {
            background: rgba(255,255,255,0.02);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #E50914;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #ff0a16;
        }
        
        .btn-success {
            background: #10B981;
            color: #fff;
        }
        
        .btn-danger {
            background: #EF4444;
            color: #fff;
        }
        
        .quick-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .logout-btn {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
            border: 1px solid #EF4444;
        }
        
        .logout-btn:hover {
            background: #EF4444;
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <i class="fas fa-film"></i> <?= SITE_NAME ?> Admin
        </div>
        <div class="admin-user">
            <span>Welcome, <strong><?= $_SESSION['admin_username'] ?></strong></span>
            <a href="logout.php" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <h1 class="page-title">Dashboard</h1>
        <div class="breadcrumb">
            <i class="fas fa-home"></i> Home / Dashboard
        </div>
        
        <div class="quick-actions">
            <a href="movies/add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
            <a href="movies/manage.php" class="btn btn-success">
                <i class="fas fa-list"></i> Manage Movies
            </a>
            <a href="../index.php" class="btn" style="background: rgba(0,217,255,0.2); color: #00D9FF;">
                <i class="fas fa-globe"></i> View Website
            </a>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-film"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['total_movies']) ?></div>
                <div class="stat-label">Total Movies</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-masks-theater"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['total_genres']) ?></div>
                <div class="stat-label">Genres</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tv"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['total_platforms']) ?></div>
                <div class="stat-label">OTT Platforms</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['total_views']) ?></div>
                <div class="stat-label">Total Views</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['featured_movies']) ?></div>
                <div class="stat-label">Featured Movies</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stat-value"><?= number_format($stats['trending_movies']) ?></div>
                <div class="stat-label">Trending Movies</div>
            </div>
        </div>
        
        <h2 class="section-title">Recent Movies</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentMovies as $movie): ?>
                <tr>
                    <td><?= $movie['id'] ?></td>
                    <td><?= htmlspecialchars($movie['title']) ?></td>
                    <td><?= $movie['release_year'] ?></td>
                    <td><i class="fas fa-star" style="color: #FFD700;"></i> <?= $movie['rating'] ?></td>
                    <td><?= number_format($movie['views']) ?></td>
                    <td>
                        <?php if ($movie['is_featured']): ?>
                            <span style="background: rgba(229,9,20,0.2); color: #E50914; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem;">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        <?php endif; ?>
                        <?php if ($movie['is_trending']): ?>
                            <span style="background: rgba(255,69,0,0.2); color: #FF4500; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem;">
                                <i class="fas fa-fire"></i> Trending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= timeAgo($movie['created_at']) ?></td>
                    <td>
                        <a href="movies/edit.php?id=<?= $movie['id'] ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85rem;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
