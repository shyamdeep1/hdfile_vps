<?php
// Site Configuration
define('SITE_NAME', 'HDFile.live');
define('SITE_URL', 'http://localhost/hdfile.live');
define('SITE_DESCRIPTION', 'Watch unlimited movies and TV shows online');
define('SITE_KEYWORDS', 'movies, streaming, OTT, watch online, Netflix, Prime, Disney+');

// Paths
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', ASSETS_URL . '/uploads');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
