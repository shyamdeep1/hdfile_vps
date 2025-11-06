<?php
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: ../index.php');
    exit;
}

require_once '../includes/header.php';

$movie = getMovieBySlug($pdo, $slug);
if (!$movie) {
    header('Location: ../index.php');
    exit;
}

// Increment view count
incrementViews($pdo, $movie['id']);

// Get movie genres
$genres = getMovieGenres($pdo, $movie['id']);

// Get movie OTT platforms
$ottPlatforms = getMovieOTT($pdo, $movie['id']);

// Parse screenshots (stored as JSON array)
$screenshots = !empty($movie['screenshots']) ? json_decode($movie['screenshots'], true) : [];

// Get similar movies (same genre)
$genreIds = array_column($genres, 'id');
$similarMovies = [];
if (!empty($genreIds)) {
    $placeholders = implode(',', array_fill(0, count($genreIds), '?'));
    $stmt = $pdo->prepare("
        SELECT DISTINCT m.* FROM movies m
        INNER JOIN movie_genres mg ON m.id = mg.movie_id
        WHERE mg.genre_id IN ($placeholders) AND m.id != ?
        LIMIT 12
    ");
    $stmt->execute([...$genreIds, $movie['id']]);
    $similarMovies = $stmt->fetchAll();
}

$pageTitle = $movie['title'];
$pageDescription = substr($movie['description'], 0, 160);
?>

<style>
.movie-detail-hero {
    position: relative;
    min-height: 600px;
    margin-top: 80px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.movie-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.movie-backdrop img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.3) blur(2px);
}

.movie-backdrop::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(15,15,15,0.95) 40%, transparent 80%);
}

.movie-detail-content {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 40px;
    padding: 40px 0;
}

.movie-poster-large {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.8);
}

.movie-poster-large img {
    width: 100%;
    height: auto;
}

.movie-details h1 {
    font-size: 3rem;
    margin-bottom: 15px;
}

.movie-tagline {
    font-size: 1.2rem;
    color: #B3B3B3;
    font-style: italic;
    margin-bottom: 20px;
}

.movie-metadata {
    display: flex;
    gap: 25px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.movie-rating {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: 700;
}

.movie-rating i {
    color: #FFD700;
}

.movie-description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #E5E5E5;
    margin-bottom: 30px;
}

.movie-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.watch-links-section {
    background: var(--secondary-bg);
    padding: 60px 0;
}

.platform-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.platform-card {
    background: var(--card-bg);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.platform-card:hover {
    transform: translateY(-5px);
    border-color: var(--accent-color);
}

.platform-logo {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 15px;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
}

.tab {
    padding: 15px 30px;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.tab.active {
    color: var(--accent-color);
    border-bottom: 3px solid var(--accent-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.download-section {
    background: var(--card-bg);
    padding: 40px;
    border-radius: 15px;
    border: 1px solid var(--border-color);
    margin-bottom: 30px;
}

.download-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.download-btn {
    background: linear-gradient(135deg, var(--accent-color), #ff0a16);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.download-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(229, 9, 20, 0.4);
    border-color: white;
}

.download-btn.disabled {
    background: rgba(255,255,255,0.05);
    color: var(--text-secondary);
    cursor: not-allowed;
    opacity: 0.5;
}

.download-btn.disabled:hover {
    transform: none;
    box-shadow: none;
}

.download-quality {
    font-size: 1.5rem;
    font-weight: 800;
}

.download-size {
    font-size: 0.9rem;
    opacity: 0.9;
}

.screenshots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.screenshot-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s;
}

.screenshot-item:hover {
    transform: scale(1.05);
}

.screenshot-item img {
    width: 100%;
    height: auto;
    display: block;
}

@media (max-width: 768px) {
    .movie-detail-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .movie-details h1 {
        font-size: 2rem;
    }
    
    .movie-poster-large {
        max-width: 300px;
        margin: 0 auto;
    }
}
</style>

<!-- Movie Detail Hero -->
<section class="movie-detail-hero">
    <div class="movie-backdrop">
        <img src="<?= htmlspecialchars($movie['banner_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
    </div>
    
    <div class="container">
        <div class="movie-detail-content">
            <div class="movie-poster-large" data-aos="fade-right">
                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            </div>
            
            <div class="movie-details" data-aos="fade-left">
                <h1><?= htmlspecialchars($movie['title']) ?></h1>
                
                <div class="movie-metadata">
                    <div class="movie-rating">
                        <i class="fas fa-star"></i>
                        <span><?= $movie['rating'] ?>/10</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i> <?= $movie['release_year'] ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i> <?= $movie['duration'] ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i> <?= number_format($movie['views']) ?> views
                    </div>
                </div>
                
                <div class="movie-genres" style="margin-bottom: 25px;">
                    <?php foreach ($genres as $genre): ?>
                        <span class="genre-badge" style="padding: 8px 20px; font-size: 0.9rem; background: rgba(<?= hexdec(substr($genre['color'], 1, 2)) ?>, <?= hexdec(substr($genre['color'], 3, 2)) ?>, <?= hexdec(substr($genre['color'], 5, 2)) ?>, 0.2); color: <?= $genre['color'] ?>;">
                            <i class="fas <?= $genre['icon'] ?>"></i> <?= htmlspecialchars($genre['name']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                
                <p class="movie-description"><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
                
                <div class="movie-actions">
                    <?php if ($movie['trailer_url']): ?>
                        <button onclick="openTrailer('<?= $movie['trailer_url'] ?>')" class="btn btn-primary">
                            <i class="fas fa-play"></i> Watch Trailer
                        </button>
                    <?php endif; ?>
                    <button onclick="shareMovie('<?= addslashes($movie['title']) ?>', '<?= SITE_URL ?>/pages/movie-detail.php?slug=<?= $movie['slug'] ?>')" class="btn btn-secondary">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                    <button onclick="addToFavorites(<?= $movie['id'] ?>)" class="btn btn-secondary">
                        <i class="fas fa-heart"></i> Add to Favorites
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Download Section -->
<section class="section" style="background: var(--secondary-bg);">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">
            <i class="fas fa-download"></i> Download Options
        </h2>
        
        <div class="download-section" data-aos="fade-up">
            <div class="download-buttons">
                <?php if (!empty($movie['download_480p'])): ?>
                    <a href="<?= htmlspecialchars($movie['download_480p']) ?>" class="download-btn" download>
                        <i class="fas fa-download" style="font-size: 2rem;"></i>
                        <span class="download-quality">480p</span>
                        <span class="download-size">SD Quality • ~400MB</span>
                    </a>
                <?php else: ?>
                    <div class="download-btn disabled">
                        <i class="fas fa-lock" style="font-size: 2rem;"></i>
                        <span class="download-quality">480p</span>
                        <span class="download-size">Not Available</span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['download_720p'])): ?>
                    <a href="<?= htmlspecialchars($movie['download_720p']) ?>" class="download-btn" download>
                        <i class="fas fa-download" style="font-size: 2rem;"></i>
                        <span class="download-quality">720p</span>
                        <span class="download-size">HD Quality • ~800MB</span>
                    </a>
                <?php else: ?>
                    <div class="download-btn disabled">
                        <i class="fas fa-lock" style="font-size: 2rem;"></i>
                        <span class="download-quality">720p</span>
                        <span class="download-size">Not Available</span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['download_1080p'])): ?>
                    <a href="<?= htmlspecialchars($movie['download_1080p']) ?>" class="download-btn" download>
                        <i class="fas fa-download" style="font-size: 2rem;"></i>
                        <span class="download-quality">1080p</span>
                        <span class="download-size">Full HD • ~1.5GB</span>
                    </a>
                <?php else: ?>
                    <div class="download-btn disabled">
                        <i class="fas fa-lock" style="font-size: 2rem;"></i>
                        <span class="download-quality">1080p</span>
                        <span class="download-size">Not Available</span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['download_4k'])): ?>
                    <a href="<?= htmlspecialchars($movie['download_4k']) ?>" class="download-btn" download>
                        <i class="fas fa-download" style="font-size: 2rem;"></i>
                        <span class="download-quality">4K UHD</span>
                        <span class="download-size">Ultra HD • ~3GB</span>
                    </a>
                <?php else: ?>
                    <div class="download-btn disabled">
                        <i class="fas fa-lock" style="font-size: 2rem;"></i>
                        <span class="download-quality">4K UHD</span>
                        <span class="download-size">Not Available</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: rgba(229, 9, 20, 0.1); border-radius: 10px; border-left: 4px solid var(--accent-color);">
                <p style="margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: var(--accent-color);"></i>
                    <span><strong>Note:</strong> Download links are provided for your convenience. Please ensure you have the right to download this content.</span>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Screenshots Section -->
<?php if (!empty($screenshots)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">
            <i class="fas fa-images"></i> Screenshots
        </h2>
        
        <div class="screenshots-grid">
            <?php foreach ($screenshots as $index => $screenshot): ?>
                <div class="screenshot-item" data-aos="zoom-in" data-aos-delay="<?= $index * 100 ?>">
                    <img src="<?= htmlspecialchars($screenshot) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Screenshot <?= $index + 1 ?>" onclick="openLightbox(this.src)">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Lightbox for screenshots -->
<div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 10000; padding: 20px;">
    <button onclick="closeLightbox()" style="position: absolute; top: 20px; right: 20px; background: var(--accent-color); border: none; color: white; padding: 15px 20px; border-radius: 50%; cursor: pointer; font-size: 1.5rem; z-index: 10001;">
        <i class="fas fa-times"></i>
    </button>
    <img id="lightbox-img" src="" style="max-width: 90%; max-height: 90%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border-radius: 10px;">
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox').style.display = 'block';
    document.getElementById('lightbox-img').src = src;
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});

// Close on click outside
document.getElementById('lightbox')?.addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>

<!-- Watch Links Section -->
<?php if (!empty($ottPlatforms)): ?>
<section class="watch-links-section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Where to Watch</h2>
        
        <div class="platform-cards">
            <?php foreach ($ottPlatforms as $platform): ?>
                <div class="platform-card" data-aos="zoom-in">
                    <div class="platform-logo" style="color: <?= $platform['color'] ?>;">
                        <?= strtoupper(substr($platform['name'], 0, 1)) ?>
                    </div>
                    <h3 style="margin-bottom: 10px;"><?= htmlspecialchars($platform['name']) ?></h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">
                        Available on <?= htmlspecialchars($platform['name']) ?>
                    </p>
                    <a href="#" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-external-link-alt"></i> Watch Now
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Similar Movies Section -->
<?php if (!empty($similarMovies)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">You May Also Like</h2>
        
        <div class="movie-grid">
            <?php foreach (array_slice($similarMovies, 0, 6) as $similar): 
                $similarGenres = getMovieGenres($pdo, $similar['id']);
            ?>
                <div class="movie-card" data-aos="fade-up">
                    <div class="movie-poster">
                        <img src="<?= htmlspecialchars($similar['poster_url']) ?>" alt="<?= htmlspecialchars($similar['title']) ?>">
                        <div class="movie-overlay">
                            <a href="movie-detail.php?slug=<?= $similar['slug'] ?>" class="play-button">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                        <div class="rating-badge">
                            <i class="fas fa-star"></i> <?= $similar['rating'] ?>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3 class="movie-title"><?= htmlspecialchars($similar['title']) ?></h3>
                        <div class="movie-meta">
                            <span><?= $similar['release_year'] ?></span>
                            <span>•</span>
                            <span><?= $similar['duration'] ?></span>
                        </div>
                        <div class="movie-genres">
                            <?php foreach (array_slice($similarGenres, 0, 2) as $genre): ?>
                                <span class="genre-badge"><?= htmlspecialchars($genre['name']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Trigger view count
document.addEventListener('DOMContentLoaded', function() {
    incrementViewCount(<?= $movie['id'] ?>);
});
</script>

<?php include '../includes/footer.php'; ?>
