<?php
$pageTitle = 'Home';
$pageDescription = 'Discover and watch unlimited movies across all major streaming platforms';
include 'includes/header.php';

// Get featured movie for hero section
$featuredMovies = getMovies($pdo, ['featured' => true], 1);
$heroMovie = $featuredMovies[0] ?? null;

// Get latest movies
$latestMovies = getMovies($pdo, [], 12);

// Get trending movies
$trendingMovies = getMovies($pdo, ['trending' => true], 12);

// Get genres
$genres = getAllGenres($pdo);

// Get OTT platforms
$ottPlatforms = getAllOTT($pdo);

// Get statistics
$statsStmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM movies) as total_movies,
        (SELECT COUNT(*) FROM genres) as total_genres,
        (SELECT COUNT(*) FROM ott_platforms) as total_platforms,
        (SELECT SUM(views) FROM movies) as total_views
");
$stats = $statsStmt->fetch();

// Get featured movies for hero slider (up to 5)
$heroMovies = getMovies($pdo, ['featured' => true], 5);
?>

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0f0f0f 100%) center/cover;">
    <div class="container">
        <div class="hero-slider">
            <?php if (!empty($heroMovies)): ?>
                <?php foreach ($heroMovies as $index => $movie): ?>
                    <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>" style="background: linear-gradient(to right, rgba(15, 15, 15, 0.9) 30%, rgba(15, 15, 15, 0.3) 70%, rgba(15, 15, 15, 0.9) 100%), url('<?= htmlspecialchars($movie['poster_url']) ?>') center/cover;">
                        <div class="hero-content" data-aos="fade-up">
                            <div class="hero-poster-container">
                                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                                     alt="<?= htmlspecialchars($movie['title']) ?>"
                                     class="hero-poster"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/300x450/1A1A1A/E50914?text=<?= urlencode($movie['title']) ?>';">
                            </div>
                            <div class="hero-info">
                                <h1 class="hero-title"><?= htmlspecialchars($movie['title']) ?></h1>
                                <div class="hero-meta">
                                    <span class="meta-badge"><i class="fas fa-calendar"></i> <?= $movie['release_year'] ?></span>
                                    <span class="meta-badge"><i class="fas fa-star"></i> <?= $movie['rating'] ?>/10</span>
                                    <span class="meta-badge"><i class="fas fa-clock"></i> <?= $movie['duration'] ?></span>
                                </div>
                                <p class="hero-description"><?= htmlspecialchars(substr($movie['description'], 0, 180)) ?>...</p>
                                <div class="hero-buttons">
                                    <a href="pages/movie-detail.php?slug=<?= $movie['slug'] ?>" class="btn btn-primary">
                                        <i class="fas fa-play"></i> Watch Now
                                    </a>
                                    <a href="pages/movie-detail.php?slug=<?= $movie['slug'] ?>" class="btn btn-secondary">
                                        <i class="fas fa-info-circle"></i> More Info
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="hero-slide active" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
                    <div class="hero-content" style="text-align: center;">
                        <h1 class="hero-title">Welcome to HDFile.Live</h1>
                        <p class="hero-description">Discover unlimited movies across all streaming platforms</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (count($heroMovies) > 1): ?>
        <!-- Slider Navigation -->
        <div class="hero-nav">
            <button class="hero-nav-btn prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-nav-btn next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Slider Dots -->
        <div class="hero-dots">
            <?php foreach ($heroMovies as $index => $movie): ?>
                <span class="dot <?= $index === 0 ? 'active' : '' ?>" onclick="currentSlide(<?= $index ?>)"></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hero-slide {
    display: none;
    min-height: 80vh;
    padding: 80px 0;
    background-size: cover !important;
    background-position: center !important;
    position: relative;
}

.hero-slide.active {
    display: block;
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
}

.hero-poster-container {
    flex-shrink: 0;
}

.hero-poster {
    width: 300px;
    height: 450px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
    transition: transform 0.3s ease;
}

.hero-poster:hover {
    transform: scale(1.05);
}

.hero-info {
    flex: 1;
    color: white;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #E50914, #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.meta-badge {
    background: rgba(229, 9, 20, 0.2);
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    border: 1px solid rgba(229, 9, 20, 0.3);
}

.hero-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 30px;
    color: #d4d4d4;
}

.hero-buttons {
    display: flex;
    gap: 15px;
}

.hero-nav {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    transform: translateY(-50%);
}

.hero-nav-btn {
    background: rgba(229, 9, 20, 0.8);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.hero-nav-btn:hover {
    background: rgba(229, 9, 20, 1);
    transform: scale(1.1);
}

.hero-dots {
    text-align: center;
    padding: 20px 0;
}

.dot {
    height: 12px;
    width: 12px;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active, .dot:hover {
    background-color: #E50914;
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
    }
    
    .hero-poster {
        width: 200px;
        height: 300px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-buttons {
        justify-content: center;
    }
}
</style>

<script>
let slideIndex = 0;

function changeSlide(n) {
    showSlide(slideIndex += n);
}

function currentSlide(n) {
    showSlide(slideIndex = n);
}

function showSlide(n) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.dot');
    
    if (n >= slides.length) { slideIndex = 0; }
    if (n < 0) { slideIndex = slides.length - 1; }
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    if (slides[slideIndex]) {
        slides[slideIndex].classList.add('active');
    }
    if (dots[slideIndex]) {
        dots[slideIndex].classList.add('active');
    }
}

// Auto slide every 5 seconds
setInterval(() => {
    changeSlide(1);
}, 5000);
</script>

<!-- OTT Platforms Section -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Explore by Platform</h2>
            <a href="pages/ott-platforms.php" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="ott-grid">
            <?php 
                // Platform icon mapping with better SVGs
                $platformIcons = [
                    'Netflix' => '<div style="background: #E50914; padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif; letter-spacing: -2px;">NETFLIX</span></div>',
                    'Amazon Prime' => '<div style="background: linear-gradient(to right, #00A8E1, #146eb4); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">prime<br><small style="font-size: 0.6em;">video</small></span></div>',
                    'Disney+ Hotstar' => '<div style="background: linear-gradient(135deg, #1A98FF, #0E47A1); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">Disney+</span></div>',
                    'HBO Max' => '<div style="background: linear-gradient(135deg, #B026FF, #8B00FF); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">HBO<br><small style="font-size: 0.5em;">MAX</small></span></div>'
                ];
            
                foreach ($ottPlatforms as $index => $platform): 
                $movieCount = $pdo->query("SELECT COUNT(*) FROM movie_ott WHERE ott_id = {$platform['id']}")->fetchColumn();
            ?>
                <div class="ott-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <div class="ott-logo" style="height: auto; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                        <?php if (isset($platformIcons[$platform['name']])): ?>
                            <?= $platformIcons[$platform['name']] ?>
                        <?php else: ?>
                            <div style="font-size: 3rem; font-weight: 900; color: <?= $platform['color'] ?>;">
                                <?= strtoupper(substr($platform['name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="ott-name" style="color: <?= $platform['color'] ?>;"><?= htmlspecialchars($platform['name']) ?></h3>
                    <p class="ott-description"><?= htmlspecialchars($platform['description']) ?></p>
                    <span class="ott-count"><?= $movieCount ?> Movies</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Movies Section -->
<section class="section" style="background: var(--secondary-bg);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">New Releases</h2>
            <a href="pages/movies.php" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($latestMovies as $movie): 
                    $movieGenres = getMovieGenres($pdo, $movie['id']);
                ?>
                    <div class="swiper-slide">
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                                     alt="<?= htmlspecialchars($movie['title']) ?>"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/500x750/1A1A1A/E50914?text=<?= urlencode($movie['title']) ?>';">
                                <div class="movie-overlay">
                                    <a href="pages/movie-detail.php?slug=<?= $movie['slug'] ?>" class="play-button">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                                <div class="rating-badge">
                                    <i class="fas fa-star"></i> <?= $movie['rating'] ?>
                                </div>
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
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Trending Section -->
<?php if (!empty($trendingMovies)): ?>
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">
                <i class="fas fa-fire" style="color: #FF4500;"></i> Trending This Week
            </h2>
            <a href="pages/movies.php?filter=trending" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="movie-grid">
            <?php foreach (array_slice($trendingMovies, 0, 6) as $index => $movie): 
                $movieGenres = getMovieGenres($pdo, $movie['id']);
            ?>
                <div class="movie-card" data-aos="zoom-in" data-aos-delay="<?= $index * 50 ?>">
                    <div class="movie-poster">
                        <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                             alt="<?= htmlspecialchars($movie['title']) ?>"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/500x750/1A1A1A/FF4500?text=<?= urlencode($movie['title']) ?>';">
                        <div class="movie-overlay">
                            <a href="pages/movie-detail.php?slug=<?= $movie['slug'] ?>" class="play-button">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                        <div class="trending-badge">
                            <i class="fas fa-fire"></i> Trending
                        </div>
                        <div class="rating-badge">
                            <i class="fas fa-star"></i> <?= $movie['rating'] ?>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                        <div class="movie-meta">
                            <span><?= $movie['release_year'] ?></span>
                            <span>•</span>
                            <span><i class="fas fa-eye"></i> <?= number_format($movie['views']) ?></span>
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
    </div>
</section>
<?php endif; ?>

<!-- Genres Section -->
<section class="section" style="background: var(--secondary-bg);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Browse by Genre</h2>
            <a href="pages/genres.php" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="genre-grid">
            <?php foreach (array_slice($genres, 0, 8) as $index => $genre): 
                $genreCount = $pdo->query("SELECT COUNT(*) FROM movie_genres WHERE genre_id = {$genre['id']}")->fetchColumn();
            ?>
                <div class="genre-card" data-aos="flip-left" data-aos-delay="<?= $index * 100 ?>">
                    <div class="genre-icon">
                        <i class="fas <?= $genre['icon'] ?>"></i>
                    </div>
                    <h3 class="genre-name"><?= htmlspecialchars($genre['name']) ?></h3>
                    <p class="genre-description"><?= htmlspecialchars($genre['description']) ?></p>
                    <span class="genre-count"><?= $genreCount ?> Movies</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up" style="display: block;">
            <h2 class="section-title" style="text-align: center;">Our Platform in Numbers</h2>
        </div>
        
        <div class="ott-grid" style="text-align: center;">
            <div data-aos="zoom-in" data-aos-delay="0">
                <div class="ott-card">
                    <i class="fas fa-film" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 20px;"></i>
                    <h3 class="counter" data-count="<?= $stats['total_movies'] ?>" style="font-size: 2.5rem; margin-bottom: 10px;">0</h3>
                    <p style="color: var(--text-secondary);">Total Movies</p>
                </div>
            </div>
            
            <div data-aos="zoom-in" data-aos-delay="100">
                <div class="ott-card">
                    <i class="fas fa-masks-theater" style="font-size: 3rem; color: #00D9FF; margin-bottom: 20px;"></i>
                    <h3 class="counter" data-count="<?= $stats['total_genres'] ?>" style="font-size: 2.5rem; margin-bottom: 10px;">0</h3>
                    <p style="color: var(--text-secondary);">Genres</p>
                </div>
            </div>
            
            <div data-aos="zoom-in" data-aos-delay="200">
                <div class="ott-card">
                    <i class="fas fa-tv" style="font-size: 3rem; color: #FFB800; margin-bottom: 20px;"></i>
                    <h3 class="counter" data-count="<?= $stats['total_platforms'] ?>" style="font-size: 2.5rem; margin-bottom: 10px;">0</h3>
                    <p style="color: var(--text-secondary);">OTT Platforms</p>
                </div>
            </div>
            
            <div data-aos="zoom-in" data-aos-delay="300">
                <div class="ott-card">
                    <i class="fas fa-users" style="font-size: 3rem; color: #10B981; margin-bottom: 20px;"></i>
                    <h3 class="counter" data-count="<?= $stats['total_views'] ?>" style="font-size: 2.5rem; margin-bottom: 10px;">0</h3>
                    <p style="color: var(--text-secondary);">Total Views</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section" style="background: linear-gradient(135deg, #E50914 0%, #8B0000 100%); padding: 60px 0;">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 style="font-size: 2.5rem; margin-bottom: 15px;">Never Miss a Release</h2>
            <p style="font-size: 1.1rem; margin-bottom: 30px; color: rgba(255,255,255,0.9);">
                Subscribe to our newsletter and get notified about new movies and updates
            </p>
            <form class="newsletter-form" style="max-width: 500px; margin: 0 auto;">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit"><i class="fas fa-paper-plane"></i> Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
