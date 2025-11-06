// HDFile.live - Main JavaScript

// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Mobile Menu Toggle
const mobileToggle = document.getElementById('mobileToggle');
const navMenu = document.getElementById('navMenu');

if (mobileToggle) {
    mobileToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        const icon = this.querySelector('i');
        if (navMenu.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    if (navMenu && navMenu.classList.contains('active')) {
        if (!navMenu.contains(event.target) && !mobileToggle.contains(event.target)) {
            navMenu.classList.remove('active');
            const icon = mobileToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }
});

// Search Functionality
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.classList.remove('active');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 500);
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.remove('active');
        }
    });
}

async function performSearch(query) {
    try {
        const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        if (data.success && data.movies.length > 0) {
            displaySearchResults(data.movies);
        } else {
            searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: #B3B3B3;">No movies found</div>';
            searchResults.classList.add('active');
        }
    } catch (error) {
        console.error('Search error:', error);
    }
}

function displaySearchResults(movies) {
    let html = '';
    movies.forEach(movie => {
        html += `
            <a href="pages/movie-detail.php?slug=${movie.slug}" class="search-result-item" style="display: flex; padding: 15px; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.3s;">
                <img src="${movie.poster_url}" alt="${movie.title}" style="width: 50px; height: 75px; object-fit: cover; border-radius: 5px;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 5px;">${movie.title}</div>
                    <div style="font-size: 0.85rem; color: #B3B3B3;">
                        ${movie.release_year} • <i class="fas fa-star" style="color: #FFD700; font-size: 0.8rem;"></i> ${movie.rating}
                    </div>
                </div>
            </a>
        `;
    });
    searchResults.innerHTML = html;
    searchResults.classList.add('active');
    
    // Add hover effects
    document.querySelectorAll('.search-result-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255,255,255,0.05)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.background = 'transparent';
        });
    });
}

// Back to Top Button
const backToTop = document.getElementById('backToTop');

window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
        backToTop.classList.add('active');
    } else {
        backToTop.classList.remove('active');
    }
});

if (backToTop) {
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Smooth Scrolling for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Lazy Loading Images with error handling
const images = document.querySelectorAll('img[data-src]');
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.add('loaded');
            observer.unobserve(img);
        }
    });
}, {
    rootMargin: '50px'
});

images.forEach(img => imageObserver.observe(img));

// Add error handling for all images
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
        // Replace broken image with placeholder
        if (!this.hasAttribute('data-error-handled')) {
            this.setAttribute('data-error-handled', 'true');
            this.src = 'https://via.placeholder.com/500x750/1A1A1A/E50914?text=No+Image';
            console.warn('Image failed to load:', this.getAttribute('src'));
        }
    });
});

// Initialize Swiper Carousels
document.addEventListener('DOMContentLoaded', function() {
    // Check if Swiper is loaded
    if (typeof Swiper === 'undefined') {
        console.error('Swiper library not loaded');
        return;
    }
    
    // Initialize all swiper instances
    const swiperElements = document.querySelectorAll('.swiper');
    
    swiperElements.forEach((swiperEl, index) => {
        // Make sure the element has required classes
        if (!swiperEl.querySelector('.swiper-wrapper')) {
            console.warn('Swiper wrapper not found in', swiperEl);
            return;
        }
        
        try {
            new Swiper(swiperEl, {
                slidesPerView: 2,
                spaceBetween: 20,
                navigation: {
                    nextEl: swiperEl.querySelector('.swiper-button-next'),
                    prevEl: swiperEl.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: swiperEl.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 5,
                        spaceBetween: 25
                    },
                    1200: {
                        slidesPerView: 6,
                        spaceBetween: 25
                    }
                },
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                }
            });
            console.log('Swiper initialized for element', index);
        } catch (error) {
            console.error('Error initializing Swiper:', error);
        }
    });
});

// Newsletter Form Submission
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        // Show success message
        alert('Thank you for subscribing! We\'ll keep you updated with the latest releases.');
        this.reset();
    });
}

// View Counter for Movie Pages
function incrementViewCount(movieId) {
    fetch('api/increment-view.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ movie_id: movieId })
    });
}

// Add to Favorites (Future Feature)
function addToFavorites(movieId) {
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (!favorites.includes(movieId)) {
        favorites.push(movieId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        showNotification('Added to favorites!');
    } else {
        showNotification('Already in favorites!');
    }
}

// Show Notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        background: ${type === 'success' ? '#10B981' : '#EF4444'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 9999;
        animation: slideInFromRight 0.5s ease-out;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideInFromRight 0.5s ease-out reverse';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Counter Animation for Statistics
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-count'));
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current).toLocaleString();
        }
    }, 16);
}

// Observe counters and animate when visible
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
            animateCounter(entry.target);
            entry.target.classList.add('counted');
        }
    });
}, { threshold: 0.5 });

counters.forEach(counter => counterObserver.observe(counter));

// Reveal elements on scroll
const revealElements = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, { threshold: 0.1 });

revealElements.forEach(element => revealObserver.observe(element));

// Share Movie Function
function shareMovie(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(error => console.log('Error sharing:', error));
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(url);
        showNotification('Link copied to clipboard!');
    }
}

// Trailer Modal
function openTrailer(url) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    `;
    
    const videoId = url.split('v=')[1] || url.split('/').pop();
    modal.innerHTML = `
        <div style="position: relative; width: 90%; max-width: 1000px;">
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow='auto';" 
                    style="position: absolute; top: -40px; right: 0; background: transparent; border: none; color: white; font-size: 2rem; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <div style="position: relative; padding-bottom: 56.25%; height: 0;">
                <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    });
}

// Console log for development
console.log('%cHDFile.live', 'color: #E50914; font-size: 24px; font-weight: bold;');
console.log('%cYour ultimate movie discovery platform', 'color: #B3B3B3; font-size: 14px;');

// Mobile-specific optimizations
(function() {
    // Detect if user is on mobile
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    
    if (isMobile) {
        // Add mobile class to body
        document.body.classList.add('mobile-device');
        
        // Prevent zoom on input focus for iOS
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                if (window.innerWidth < 768) {
                    const viewport = document.querySelector('meta[name=viewport]');
                    viewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
                }
            });
            
            input.addEventListener('blur', function() {
                const viewport = document.querySelector('meta[name=viewport]');
                viewport.content = 'width=device-width, initial-scale=1.0';
            });
        });
        
        // Close mobile menu when nav link is clicked
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        });
        
        // Smooth scroll behavior for mobile
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Optimize images for mobile - lazy loading
        const images = document.querySelectorAll('img');
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => {
                if (img.dataset.src) {
                    imageObserver.observe(img);
                }
            });
        }
        
        // Touch swipe for hero slider
        let touchStartX = 0;
        let touchEndX = 0;
        
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            heroSlider.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            heroSlider.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                if (touchEndX < touchStartX - swipeThreshold) {
                    // Swipe left - next slide
                    if (typeof changeSlide === 'function') {
                        changeSlide(1);
                    }
                }
                if (touchEndX > touchStartX + swipeThreshold) {
                    // Swipe right - previous slide
                    if (typeof changeSlide === 'function') {
                        changeSlide(-1);
                    }
                }
            }
        }
    }
    
    // Orientation change handler
    window.addEventListener('orientationchange', function() {
        // Reload styles on orientation change for better layout
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 100);
    });
    
    // Performance optimization - reduce animations on low-end devices
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (connection && (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g')) {
        document.body.classList.add('reduce-motion');
        // Disable AOS animations on slow connections
        if (typeof AOS !== 'undefined') {
            AOS.init({
                disable: true
            });
        }
    }
})();
