<?php
$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with the HDFile.live team';
require_once '../includes/header.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errorMessage = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address';
    } else {
        // In production, send email or save to database
        $successMessage = 'Thank you for your message! We\'ll get back to you soon.';
    }
}
?>

<style>
.contact-hero {
    background: linear-gradient(135deg, var(--accent-color) 0%, #8B0000 100%);
    padding: 120px 0 80px;
    text-align: center;
    margin-top: 80px;
}

.contact-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.contact-section {
    padding: 80px 0;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-top: 40px;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.info-card {
    background: var(--card-bg);
    padding: 30px;
    border-radius: 15px;
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 20px;
}

.info-icon {
    width: 60px;
    height: 60px;
    background: rgba(229, 9, 20, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent-color);
    flex-shrink: 0;
}

.contact-form {
    background: var(--card-bg);
    padding: 40px;
    border-radius: 15px;
    border: 1px solid var(--border-color);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-secondary);
}

.form-control {
    width: 100%;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-color);
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid #10B981;
    color: #6EE7B7;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid #EF4444;
    color: #FCA5A5;
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-hero h1 {
        font-size: 2.5rem;
    }
}
</style>

<section class="contact-hero">
    <div class="container" data-aos="fade-up">
        <h1>Contact Us</h1>
        <p style="font-size: 1.2rem; max-width: 700px; margin: 0 auto; opacity: 0.95;">
            Have a question or feedback? We'd love to hear from you!
        </p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info" data-aos="fade-right">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 5px;">Email Us</h3>
                        <p style="color: var(--text-secondary);">support@hdfile.live</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 5px;">Response Time</h3>
                        <p style="color: var(--text-secondary);">Within 24-48 hours</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 5px;">Follow Us</h3>
                        <div style="display: flex; gap: 15px; margin-top: 10px;">
                            <a href="#" style="color: var(--text-secondary); transition: color 0.3s;">
                                <i class="fab fa-facebook fa-lg"></i>
                            </a>
                            <a href="#" style="color: var(--text-secondary); transition: color 0.3s;">
                                <i class="fab fa-twitter fa-lg"></i>
                            </a>
                            <a href="#" style="color: var(--text-secondary); transition: color 0.3s;">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                            <a href="#" style="color: var(--text-secondary); transition: color 0.3s;">
                                <i class="fab fa-youtube fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <h3 style="margin-bottom: 15px;">Frequently Asked Questions</h3>
                    <p style="color: var(--text-secondary); line-height: 1.6;">
                        Before reaching out, check out our <a href="faq.php" style="color: var(--accent-color);">FAQ page</a> 
                        for quick answers to common questions.
                    </p>
                </div>
            </div>
            
            <div class="contact-form" data-aos="fade-left">
                <h2 style="margin-bottom: 20px;">Send us a Message</h2>
                
                <?php if ($successMessage): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= $successMessage ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $errorMessage ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject *</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
