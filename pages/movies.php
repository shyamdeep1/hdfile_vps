<?php
$pageTitle = 'All Movies';
$pageDescription = 'Browse our complete collection of movies';
require_once '../includes/header.php';

// Get all genres and OTT platforms for filters
$allGenres = getAllGenres($pdo);
$allOTT = getAllOTT($pdo);

// Get filter parameters
$filterGenre = $_GET['genre'] ?? '';
$filterOTT = $_GET['ott'] ?? '';
$searchQuery = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'latest';

// Build filter array
$filters = [];
if (!empty($filterGenre)) $filters['genre'] = $filterGenre;
if (!empty($filterOTT)) $filters['ott'] = $filterOTT;
if (!empty($searchQuery)) $filters['search'] = $searchQuery;

// Get movies
$movies = getMovies($pdo, $filters);

// Sort movies
if ($sortBy === 'rating') {
    usort($movies, function($a, $b) {
        return $b['rating'] <=> $a['rating'];
    });
} elseif ($sortBy === 'title') {
    usort($movies, function($a, $b) {
        return strcmp($a['title'], $b['title']);
    });
} elseif ($sortBy === 'year') {
    usort($movies, function($a, $b) {
        return $b['release_year'] <=> $a['release_year'];
    });
}
?>

<style>
.movies-page {
    margin-top: 100px;
    padding: 40px 0;
}

.movies-header {
    text-align: center;
    margin-bottom: 50px;
}

.movies-header h1 {
    font-size: 3rem;
    margin-bottom: 15px;
}

.filter-section {
    background: var(--secondary-bg);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 40px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-group label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-transform: uppercase;
}

.filter-group select,
.filter-group input {
    padding: 12px 15px;
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 1rem;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: var(--accent-color);
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: flex-end;
}

.movies-count {
    text-align: center;
    color: var(--text-secondary);
    margin-bottom: 30px;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.no-results i {
    font-size: 4rem;
    color: var(--accent-color);
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .movies-header h1 {
        font-size: 2rem;
    }
    
    .filter-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-actions .btn {
        width: 100%;
    }
}
</style>

<div class="movies-page">
    <div class="container">
        <div class="movies-header" data-aos="fade-up">
            <h1>Explore Movies</h1>
            <p>Discover your next favorite movie from our vast collection</p>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section" data-aos="fade-up">
            <form method="GET" action="">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" placeholder="Movie title..." value="<?= htmlspecialchars($searchQuery) ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label>Genre</label>
                        <select name="genre">
                            <option value="">All Genres</option>
                            <?php foreach ($allGenres as $genre): ?>
                                <option value="<?= $genre['id'] ?>" <?= $filterGenre == $genre['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($genre['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Platform</label>
                        <select name="ott">
                            <option value="">All Platforms</option>
                            <?php foreach ($allOTT as $platform): ?>
                                <option value="<?= $platform['id'] ?>" <?= $filterOTT == $platform['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($platform['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort">
                            <option value="latest" <?= $sortBy === 'latest' ? 'selected' : '' ?>>Latest Added</option>
                            <option value="rating" <?= $sortBy === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                            <option value="title" <?= $sortBy === 'title' ? 'selected' : '' ?>>Title A-Z</option>
                            <option value="year" <?= $sortBy === 'year' ? 'selected' : '' ?>>Release Year</option>
                        </select>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="movies.php" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Movies Count -->
        <?php if (!empty($movies)): ?>
            <div class="movies-count">
                Found <strong><?= count($movies) ?></strong> movie<?= count($movies) != 1 ? 's' : '' ?>
            </div>
        <?php endif; ?>
        
        <!-- Movies Grid -->
        <?php if (!empty($movies)): ?>
            <div class="movie-grid">
                <?php foreach ($movies as $index => $movie): 
                    $movieGenres = getMovieGenres($pdo, $movie['id']);
                ?>
                    <div class="movie-card" data-aos="fade-up" data-aos-delay="<?= ($index % 6) * 50 ?>">
                        <div class="movie-poster">
                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                            <div class="movie-overlay">
                                <a href="movie-detail.php?slug=<?= $movie['slug'] ?>" class="play-button">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                            <div class="rating-badge">
                                <i class="fas fa-star"></i> <?= $movie['rating'] ?>
                            </div>
                            <?php if ($movie['is_trending']): ?>
                                <div class="trending-badge">
                                    <i class="fas fa-fire"></i> Trending
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="movie-info">
                            <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                            <div class="movie-meta">
                                <span><?= $movie['release_year'] ?></span>
                                <span>•</span>
                                <span><?= $movie['duration'] ?></span>
                            </div>
                            <div class="movie-genres">
                                <?php foreach (array_slice($movieGenres, 0, 2) as $genre): ?>
                                    <span class="genre-badge"><?= htmlspecialchars($genre['name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results" data-aos="fade-up">
                <i class="fas fa-film"></i>
                <h2>No Movies Found</h2>
                <p>Try adjusting your filters or search query</p>
                <a href="movies.php" class="btn btn-primary" style="margin-top: 20px;">
                    <i class="fas fa-redo"></i> View All Movies
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
