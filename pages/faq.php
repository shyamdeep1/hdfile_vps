<?php
$pageTitle = 'FAQ - Frequently Asked Questions';
require_once '../includes/header.php';
?>

<style>
.faq-hero {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    padding: 120px 0 80px;
    text-align: center;
    margin-top: 80px;
}

.faq-section {
    padding: 80px 0;
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    margin-bottom: 20px;
    overflow: hidden;
}

.faq-question {
    padding: 25px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s;
}

.faq-question:hover {
    background: rgba(229, 9, 20, 0.1);
}

.faq-answer {
    padding: 0 30px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s;
    color: var(--text-secondary);
    line-height: 1.8;
}

.faq-answer.active {
    padding: 25px 30px;
    max-height: 500px;
}
</style>

<section class="faq-hero">
    <div class="container" data-aos="fade-up">
        <h1>Frequently Asked Questions</h1>
        <p style="font-size: 1.2rem; opacity: 0.95;">
            Find answers to common questions
        </p>
    </div>
</section>

<section class="faq-section">
    <div class="container">
        <div data-aos="fade-up">
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>What is HDFile.live?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    HDFile.live is a movie discovery platform that helps you find where to watch your favorite movies across various streaming platforms like Netflix, Amazon Prime, Disney+ Hotstar, and more.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>Is HDFile.live free to use?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Yes! HDFile.live is completely free to use. We help you discover movies and show you where they're available to watch.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>Can I watch movies directly on HDFile.live?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    No, we don't host or stream movies. We provide information about where you can legally watch movies on various streaming platforms. You'll need a subscription to those platforms to watch the content.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>How often is the movie database updated?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    We regularly update our database with new releases and remove titles that are no longer available on streaming platforms.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>Can I request a movie to be added?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Yes! You can contact us through our contact page and suggest movies you'd like to see added to our database.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('active')">
                    <span>Do I need to create an account?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    No account is required to browse and search for movies on HDFile.live. However, we may add user features in the future that will require registration.
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 60px;" data-aos="fade-up">
            <h3 style="margin-bottom: 20px;">Still have questions?</h3>
            <a href="contact.php" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
