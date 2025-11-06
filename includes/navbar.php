<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-wrapper">
            <a href="<?= SITE_URL ?>" class="logo">
                <i class="fas fa-film"></i>
                <span class="logo-text"><?= SITE_NAME ?></span>
            </a>
            
            <div class="nav-menu" id="navMenu">
                <a href="<?= SITE_URL ?>" class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="<?= SITE_URL ?>/pages/movies.php" class="nav-link <?= $current_page == 'movies' ? 'active' : '' ?>">
                    <i class="fas fa-film"></i> Movies
                </a>
                <a href="<?= SITE_URL ?>/pages/genres.php" class="nav-link <?= $current_page == 'genres' ? 'active' : '' ?>">
                    <i class="fas fa-masks-theater"></i> Genres
                </a>
                <a href="<?= SITE_URL ?>/pages/ott-platforms.php" class="nav-link <?= $current_page == 'ott-platforms' ? 'active' : '' ?>">
                    <i class="fas fa-tv"></i> Platforms
                </a>
                <a href="<?= SITE_URL ?>/pages/about.php" class="nav-link <?= $current_page == 'about' ? 'active' : '' ?>">
                    <i class="fas fa-info-circle"></i> About
                </a>
            </div>
            
            <div class="nav-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search movies...">
                    <i class="fas fa-search"></i>
                    <div class="search-results" id="searchResults"></div>
                </div>
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
