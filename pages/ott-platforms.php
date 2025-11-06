<?php
$pageTitle = 'OTT Platforms';
$pageDescription = 'Explore movies by streaming platform';
require_once '../includes/header.php';

$platforms = getAllOTT($pdo);
?>

<style>
.platforms-hero {
    background: linear-gradient(135deg, #00D9FF 0%, #0066FF 100%);
    padding: 120px 0 80px;
    text-align: center;
    margin-top: 80px;
}

.platforms-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.platforms-section {
    padding: 80px 0;
}
</style>

<section class="platforms-hero">
    <div class="container" data-aos="fade-up">
        <h1>OTT Platforms</h1>
        <p style="font-size: 1.2rem; opacity: 0.95;">
            Find movies available on your favorite streaming services
        </p>
    </div>
</section>

<section class="platforms-section">
    <div class="container">
        <div class="ott-grid">
            <?php 
                // Platform icon mapping with better display
                $platformIcons = [
                    'Netflix' => '<div style="background: #E50914; padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif; letter-spacing: -2px;">NETFLIX</span></div>',
                    'Amazon Prime' => '<div style="background: linear-gradient(to right, #00A8E1, #146eb4); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 1.8rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">prime<br/><small style="font-size: 0.6em;">video</small></span></div>',
                    'Disney+ Hotstar' => '<div style="background: linear-gradient(135deg, #1A98FF, #0E47A1); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">Disney+</span></div>',
                    'HBO Max' => '<div style="background: linear-gradient(135deg, #B026FF, #8B00FF); padding: 15px 20px; border-radius: 10px; display: inline-block;"><span style="font-size: 2rem; font-weight: 900; color: white; font-family: Arial, sans-serif;">HBO<br/><small style="font-size: 0.5em;">MAX</small></span></div>'
                ];
            
                foreach ($platforms as $index => $platform): 
                $movieCount = $pdo->query("SELECT COUNT(*) FROM movie_ott WHERE ott_id = {$platform['id']}")->fetchColumn();
            ?>
                <a href="movies.php?ott=<?= $platform['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="ott-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="ott-logo" style="height: auto; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                            <?php if (isset($platformIcons[$platform['name']])): ?>
                                <?= $platformIcons[$platform['name']] ?>
                            <?php else: ?>
                                <div style="font-size: 4rem; font-weight: 900; color: <?= $platform['color'] ?>;">
                                    <?= strtoupper(substr($platform['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="ott-name" style="color: <?= $platform['color'] ?>;">
                            <?= htmlspecialchars($platform['name']) ?>
                        </h3>
                        <p class="ott-description"><?= htmlspecialchars($platform['description']) ?></p>
                        <span class="ott-count"><?= $movieCount ?> Movies</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
