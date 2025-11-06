<?php
$pageTitle = 'About Us';
$pageDescription = 'Learn more about HDFile.live - Your ultimate movie discovery platform';
require_once '../includes/header.php';
?>

<style>
.about-hero {
    background: linear-gradient(135deg, var(--accent-color) 0%, #8B0000 100%);
    padding: 120px 0 80px;
    text-align: center;
    margin-top: 80px;
}

.about-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.about-section {
    padding: 80px 0;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.feature-card {
    background: var(--card-bg);
    padding: 40px 30px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-10px);
    border-color: var(--accent-color);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: rgba(229, 9, 20, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: var(--accent-color);
}

.feature-card h3 {
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.feature-card p {
    color: var(--text-secondary);
    line-height: 1.6;
}
</style>

<section class="about-hero">
    <div class="container" data-aos="fade-up">
        <h1>About HDFile.live</h1>
        <p style="font-size: 1.2rem; max-width: 700px; margin: 0 auto; opacity: 0.95;">
            Your one-stop destination for discovering movies across all major streaming platforms
        </p>
    </div>
</section>

<section class="about-section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;" data-aos="fade-up">
            <h2 class="section-title text-center">Our Mission</h2>
            <p style="font-size: 1.1rem; color: var(--text-secondary); line-height: 1.8; text-align: center;">
                At HDFile.live, we're passionate about making movie discovery simple and enjoyable. 
                We aggregate content from major streaming platforms, helping you find where to watch 
                your favorite movies without the hassle of searching multiple services.
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="0">
                <div class="feature-icon">
                    <i class="fas fa-film"></i>
                </div>
                <h3>Vast Collection</h3>
                <p>Browse through thousands of movies across all genres and platforms</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Easy Discovery</h3>
                <p>Find movies quickly with our powerful search and filter options</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-tv"></i>
                </div>
                <h3>Multi-Platform</h3>
                <p>See which streaming services have your desired movie instantly</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Quality Content</h3>
                <p>Curated collection of top-rated and trending movies</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile Friendly</h3>
                <p>Access our platform from any device, anywhere, anytime</p>
            </div>
            
            <div class="feature-card" data-aos="zoom-in" data-aos-delay="500">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Fast & Reliable</h3>
                <p>Lightning-fast performance with regular updates</p>
            </div>
        </div>
    </div>
</section>

<section class="about-section" style="background: var(--secondary-bg);">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;" data-aos="fade-up">
            <h2 class="section-title text-center">Why Choose Us?</h2>
            <p style="font-size: 1.05rem; color: var(--text-secondary); line-height: 1.8; margin-bottom: 40px;">
                We're dedicated to providing the best movie discovery experience. Our platform is constantly 
                updated with the latest releases, and we work hard to ensure all information is accurate and up-to-date.
            </p>
            
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <h3 style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 10px;">100%</h3>
                    <p style="color: var(--text-secondary);">Free to Use</p>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h3 style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 10px;">24/7</h3>
                    <p style="color: var(--text-secondary);">Always Available</p>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h3 style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 10px;">∞</h3>
                    <p style="color: var(--text-secondary);">Unlimited Access</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-section">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="section-title text-center">Get Started Today</h2>
        <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 30px;">
            Start exploring our vast collection of movies now
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="movies.php" class="btn btn-primary">
                <i class="fas fa-film"></i> Browse Movies
            </a>
            <a href="contact.php" class="btn btn-secondary">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
