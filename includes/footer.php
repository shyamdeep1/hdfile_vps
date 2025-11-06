    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-film"></i> <?= SITE_NAME ?></h3>
                    <p>Your ultimate destination for discovering movies across all major streaming platforms.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/pages/movies.php">Browse Movies</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/genres.php">Genres</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/ott-platforms.php">OTT Platforms</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/about.php">About Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Help & Support</h4>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/pages/faq.php">FAQ</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/contact.php">Contact Us</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/privacy.php">Privacy Policy</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <p>Subscribe to get updates on new releases</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
                <p>Made with <i class="fas fa-heart"></i> for movie lovers</p>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
