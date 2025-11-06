<?php
$pageTitle = 'Browse Genres';
$pageDescription = 'Explore movies by genre';
require_once '../includes/header.php';

$genres = getAllGenres($pdo);
?>

<style>
.genres-hero {
    background: linear-gradient(135deg, #8B00FF 0%, #E50914 100%);
    padding: 120px 0 80px;
    text-align: center;
    margin-top: 80px;
}

.genres-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.genres-section {
    padding: 80px 0;
}
</style>

<section class="genres-hero">
    <div class="container" data-aos="fade-up">
        <h1>Browse by Genre</h1>
        <p style="font-size: 1.2rem; opacity: 0.95;">
            Discover movies tailored to your taste
        </p>
    </div>
</section>

<section class="genres-section">
    <div class="container">
        <div class="genre-grid">
            <?php foreach ($genres as $index => $genre): 
                $movieCount = $pdo->query("SELECT COUNT(*) FROM movie_genres WHERE genre_id = {$genre['id']}")->fetchColumn();
            ?>
                <a href="movies.php?genre=<?= $genre['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="genre-card" data-aos="flip-left" data-aos-delay="<?= $index * 100 ?>">
                        <div class="genre-icon">
                            <i class="fas <?= $genre['icon'] ?>"></i>
                        </div>
                        <h3 class="genre-name"><?= htmlspecialchars($genre['name']) ?></h3>
                        <p class="genre-description"><?= htmlspecialchars($genre['description']) ?></p>
                        <span class="genre-count"><?= $movieCount ?> Movies</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
