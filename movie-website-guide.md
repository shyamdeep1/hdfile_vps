# Movie Links Provider Website - Comprehensive Development Guide

## Project Overview
Build a modern, user-attractive movie links provider website using PHP with emphasis on stunning frontend design inspired by leading OTT platforms (Netflix, Disney+, Amazon Prime, HBO Max). The website should feature an intuitive admin panel for content management and a rich, engaging user experience.

---

## Technology Stack

### Backend
- **Language**: PHP 8.0+
- **Database**: MySQL 8.0+
- **Session Management**: PHP Sessions
- **Architecture**: MVC Pattern (Model-View-Controller)

### Frontend
- **HTML5** with semantic markup
- **CSS3** with modern features (Grid, Flexbox, Animations)
- **JavaScript (ES6+)** for interactivity
- **AJAX** for dynamic content loading
- **Font**: Google Fonts (Poppins, Inter, or Montserrat)
- **Icons**: Font Awesome 6 or Lucide Icons

### Additional Libraries
- **Swiper.js**: For carousels and sliders
- **AOS (Animate On Scroll)**: For scroll animations
- **jQuery**: For simplified DOM manipulation (optional)
- **Particle.js**: For background effects (optional)

---

## Project Structure

```
movie-website/
│
├── index.php                          # Landing page
├── config/
│   ├── database.php                   # Database connection
│   └── config.php                     # Site configuration
│
├── admin/
│   ├── index.php                      # Admin dashboard
│   ├── login.php                      # Admin login
│   ├── logout.php                     # Logout handler
│   ├── movies/
│   │   ├── add.php                    # Add movie
│   │   ├── edit.php                   # Edit movie
│   │   ├── delete.php                 # Delete movie
│   │   └── list.php                   # List all movies
│   ├── genres/
│   │   └── manage.php                 # Manage genres
│   ├── ott/
│   │   └── manage.php                 # Manage OTT platforms
│   └── includes/
│       ├── header.php
│       ├── sidebar.php
│       └── footer.php
│
├── pages/
│   ├── movies.php                     # All movies page
│   ├── genres.php                     # Genres listing
│   ├── ott-platforms.php              # OTT platforms page
│   ├── latest.php                     # Latest releases
│   ├── trending.php                   # Trending movies
│   ├── top-rated.php                  # Top rated movies
│   ├── about.php                      # About us
│   ├── contact.php                    # Contact page
│   ├── privacy.php                    # Privacy policy
│   ├── terms.php                      # Terms of service
│   ├── faq.php                        # FAQ page
│   └── movie-detail.php               # Single movie page
│
├── includes/
│   ├── header.php                     # Site header
│   ├── footer.php                     # Site footer
│   ├── navbar.php                     # Navigation bar
│   └── functions.php                  # Helper functions
│
├── assets/
│   ├── css/
│   │   ├── style.css                  # Main stylesheet
│   │   ├── admin.css                  # Admin panel styles
│   │   ├── responsive.css             # Responsive design
│   │   └── animations.css             # Custom animations
│   ├── js/
│   │   ├── main.js                    # Main JavaScript
│   │   ├── admin.js                   # Admin panel JS
│   │   └── slider.js                  # Slider functionality
│   ├── images/
│   │   ├── banners/                   # Hero banners
│   │   ├── logos/                     # OTT logos
│   │   ├── placeholders/              # Placeholder images
│   │   └── bg/                        # Background images
│   └── uploads/                       # User uploaded content
│
└── api/
    ├── get-movies.php                 # AJAX endpoint for movies
    ├── search.php                     # Search functionality
    └── filter.php                     # Filter movies
```

---

## Database Schema

### Create the following tables:

#### 1. admin_users
```sql
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 2. movies
```sql
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    release_year INT,
    duration VARCHAR(20),
    rating DECIMAL(3,1),
    poster_url VARCHAR(500),
    banner_url VARCHAR(500),
    trailer_url VARCHAR(500),
    watch_links TEXT,
    is_featured BOOLEAN DEFAULT 0,
    is_trending BOOLEAN DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 3. genres
```sql
CREATE TABLE genres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    color VARCHAR(20)
);
```

#### 4. movie_genres (Many-to-Many relationship)
```sql
CREATE TABLE movie_genres (
    movie_id INT,
    genre_id INT,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);
```

#### 5. ott_platforms
```sql
CREATE TABLE ott_platforms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    logo_url VARCHAR(500),
    color VARCHAR(20),
    description TEXT,
    display_order INT DEFAULT 0
);
```

#### 6. movie_ott (Many-to-Many relationship)
```sql
CREATE TABLE movie_ott (
    movie_id INT,
    ott_id INT,
    PRIMARY KEY (movie_id, ott_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (ott_id) REFERENCES ott_platforms(id) ON DELETE CASCADE
);
```

#### 7. cast_crew
```sql
CREATE TABLE cast_crew (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    role ENUM('actor', 'director', 'producer', 'writer') NOT NULL,
    photo_url VARCHAR(500),
    bio TEXT
);
```

#### 8. movie_cast
```sql
CREATE TABLE movie_cast (
    movie_id INT,
    cast_id INT,
    character_name VARCHAR(255),
    PRIMARY KEY (movie_id, cast_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (cast_id) REFERENCES cast_crew(id) ON DELETE CASCADE
);
```

---

## Design Guidelines (UI/UX)

### Color Scheme
Create a dark theme with vibrant accent colors:
- **Primary Background**: #0F0F0F (Netflix-inspired dark)
- **Secondary Background**: #1A1A1A
- **Card Background**: #1F1F1F with subtle gradient
- **Accent Color**: #E50914 (Netflix red) or #00D9FF (custom cyan)
- **Text Primary**: #FFFFFF
- **Text Secondary**: #B3B3B3
- **Hover Effects**: Gradient overlays and glow effects

### Typography
- **Headings**: Poppins Bold (700-900)
- **Body Text**: Inter Regular (400-500)
- **Font Sizes**:
  - Hero Title: 3.5rem - 4.5rem
  - Section Headings: 2rem - 2.5rem
  - Card Titles: 1rem - 1.2rem
  - Body Text: 0.95rem - 1rem

### Layout Principles
1. **Full-width hero section** with video/gradient background
2. **Grid-based card layouts** (responsive: 6 cols desktop, 4 tablet, 2 mobile)
3. **Horizontal scrolling sections** for movie rows
4. **Smooth scroll animations** with AOS
5. **Glass-morphism effects** on cards and overlays
6. **Parallax effects** on hero sections
7. **Micro-interactions** on hover and click

---

## Page-by-Page Specifications

### 1. Landing Page (index.php)

#### Hero Section
- **Full-screen hero banner** with featured movie backdrop
- **Animated gradient overlay**
- **Movie title in large typography** with fade-in animation
- **Short description** (2-3 lines)
- **CTA Buttons**: "Watch Now", "More Info" with icon
- **Auto-playing background video** (muted, optional)
- **Floating particle effects** in background

#### OTT Platform Section
- **Heading**: "Explore by Platform" with animated underline
- **4 Large Cards** with OTT logos:
  - Netflix
  - Amazon Prime Video
  - Disney+ Hotstar
  - HBO Max / Apple TV+
- **Card Design**:
  - Platform logo (large, centered)
  - Platform name
  - Movie count badge
  - Gradient background matching brand colors
  - Hover effect: Scale up + glow + show preview thumbnails
  - Click: Navigate to filtered movie page

#### Latest Movies Section
- **Heading**: "New Releases" with "View All" link
- **Horizontal scrolling carousel** with Swiper.js
- **Movie Cards**:
  - Poster image (2:3 aspect ratio)
  - Title overlay on hover
  - Rating badge (top-right)
  - Quick info on hover: Year, Duration, Genre
  - Play icon overlay
  - Smooth hover animation (lift + shadow)
- **Display**: 6-8 movies visible at once
- **Navigation**: Arrow buttons + drag functionality

#### Trending This Week
- **Similar to Latest Movies** but different styling
- **Add "Fire" icon** or trending badge
- **Animated number counters** showing views

#### Genres Section
- **Heading**: "Browse by Genre"
- **Grid of genre cards** (4x2 on desktop)
- **Card Design**:
  - Genre name
  - Background: Blurred collage of genre movies
  - Movie count
  - Icon representing genre
  - Color-coded borders
- **Hover**: Zoom effect with color shift

#### Top Rated Section
- **Heading**: "Critics' Choice"
- **Featured grid layout** (1 large + 4 medium cards)
- **Show star ratings** prominently
- **Include critic score** if available

#### Continue Watching Section (Future Feature)
- **Personalized section** (show placeholder for now)
- **Progress bars** on movie cards
- **"Resume" button** on hover

#### Featured Collections
- **Curated lists**: "Oscar Winners", "Superhero Movies", "Romantic Comedies"
- **Banner-style cards** with collection artwork
- **Movie count badge**

#### Newsletter Signup
- **Stylish section** with gradient background
- **Heading**: "Never Miss a Release"
- **Email input** with animated button
- **Privacy note** below

#### Statistics Section
- **Animated counters**:
  - Total Movies
  - Genres
  - OTT Platforms
  - Happy Users
- **Icon for each statistic**
- **Count-up animation** on scroll

---

### 2. Movie Detail Page (movie-detail.php)

#### Hero Section
- **Large banner backdrop** (blurred movie poster)
- **Movie poster** (left side, elevated with shadow)
- **Movie Information** (right side):
  - Title (large)
  - Tagline (if available)
  - Rating (stars + numeric)
  - Year | Duration | Genres (inline)
  - Description (3-4 lines)
- **Action Buttons**:
  - Watch Now (multiple links dropdown)
  - Add to Favorites
  - Share
  - Trailer

#### Watch Links Section
- **Prominent cards** for each OTT platform
- **Platform logo + "Watch on Netflix"**
- **Link type badge**: "Free", "Subscription", "Rent"
- **Opens in new tab**

#### Details Section
- **Tabs**: Overview | Cast & Crew | Similar Movies | Reviews
- **Overview Tab**:
  - Full synopsis
  - Director, Producer, Writer info with photos
  - Language, Country
  - Box Office (if available)
- **Cast & Crew Tab**:
  - Grid of cast members
  - Photo + Name + Character
  - Hover: Show bio preview
- **Similar Movies Tab**:
  - Carousel of related movies
- **Reviews Tab**:
  - User reviews section (future feature placeholder)

#### Trailer Section
- **Embedded video player** (YouTube/Vimeo)
- **Lightbox popup** option

---

### 3. All Movies Page (movies.php)

#### Filter Sidebar
- **Filters**:
  - Genre (checkboxes)
  - OTT Platform (checkboxes)
  - Year (range slider)
  - Rating (range slider)
  - Sort by: Latest, Rating, Title A-Z
- **Apply Filters** button
- **Clear All** button
- **Sticky sidebar** on scroll

#### Movies Grid
- **Responsive grid** (6-4-2 columns)
- **Movie cards** with:
  - Poster
  - Title
  - Year, Rating
  - Genre badges
  - OTT platform icons
- **Pagination** or **Infinite scroll**
- **Loading skeleton** while fetching

#### Search Bar
- **Large search bar** at top
- **Real-time search** with AJAX
- **Search suggestions** dropdown

---

### 4. Genres Page (genres.php)

- **Hero section** with genre description
- **Large genre cards** in grid
- **Click to filter movies** by that genre
- **Show featured movie** from each genre

---

### 5. OTT Platforms Page (ott-platforms.php)

- **Hero section** with OTT descriptions
- **Large platform cards**
- **Platform statistics**: Movie count, Ratings
- **Click to view movies** from that platform

---

### 6. About Page (about.php)

- **Hero section**: "About Our Platform"
- **Mission & Vision** section
- **Team section** (with placeholder profiles)
- **Statistics** section
- **Why Choose Us** features grid
- **Timeline** of website milestones

---

### 7. Contact Page (contact.php)

- **Contact form**:
  - Name, Email, Subject, Message
  - Animated input fields
  - Submit button with loading state
- **Contact information**:
  - Email, Phone (optional)
  - Social media links
- **Map embed** (optional)

---

### 8. FAQ Page (faq.php)

- **Accordion-style FAQ**
- **Categories**: General, Technical, Account, Legal
- **Search FAQ** functionality
- **Animated expand/collapse**

---

### 9. Privacy Policy & Terms (privacy.php, terms.php)

- **Clean typography**
- **Table of contents** (sticky sidebar)
- **Section anchors**
- **Last updated date**

---

## Admin Panel Specifications

### Design Theme
- **Modern dashboard** with dark sidebar
- **Clean, minimal interface**
- **Color scheme**: Dark blue + white accents
- **Icons**: For all menu items

### Admin Login Page (admin/login.php)
- **Centered login form**
- **Glassmorphism effect**
- **Gradient background**
- **Username/Email + Password fields**
- **Remember Me checkbox**
- **Animated error messages**

### Dashboard (admin/index.php)
- **Statistics cards**:
  - Total Movies
  - Total Genres
  - OTT Platforms
  - Views Today
- **Recent activity** feed
- **Quick actions** buttons
- **Charts**: Movies by genre, Views over time

### Movies Management (admin/movies/)

#### List Movies (list.php)
- **Data table** with:
  - Thumbnail
  - Title
  - Year
  - Rating
  - Views
  - Actions (Edit, Delete)
- **Search and filter**
- **Pagination**
- **Bulk actions** (future)

#### Add Movie (add.php)
- **Multi-step form**:
  - Step 1: Basic Info (Title, Description, Year)
  - Step 2: Media (Poster, Banner, Trailer)
  - Step 3: Classification (Genres, OTT Platforms)
  - Step 4: Watch Links
- **Image upload preview**
- **WYSIWYG editor** for description
- **Genre multi-select** with checkboxes
- **OTT platform multi-select**
- **Dynamic watch links** (add/remove inputs)
- **Save as Draft** option

#### Edit Movie (edit.php)
- **Same form as Add** but pre-filled
- **Show current images** with replace option
- **Update button**

#### Delete Movie (delete.php)
- **Confirmation modal**
- **Soft delete** option (archive instead of permanent delete)

### Genres Management (admin/genres/manage.php)
- **CRUD operations** for genres
- **Add/Edit modal**
- **Assign icon and color**
- **Reorder genres** (drag and drop)

### OTT Platforms Management (admin/ott/manage.php)
- **CRUD operations** for platforms
- **Upload logo**
- **Set display order**
- **Platform statistics**

### Settings (admin/settings.php)
- **Site settings**: Site name, description, logo
- **Admin profile**: Change password, email
- **Appearance**: Accent color, theme options

---

## Additional Features to Implement

### Frontend Features

1. **Dark/Light Theme Toggle**
   - Switch in navbar
   - Persist preference in localStorage
   - Smooth theme transition

2. **Search Functionality**
   - Global search in navbar
   - Live search results dropdown
   - Search history
   - Advanced search page

3. **Favorites System** (Future)
   - Add to favorites button
   - Favorites page
   - localStorage initially, database later

4. **Loading States**
   - Skeleton screens for content loading
   - Spinner for AJAX requests
   - Progress bar for page transitions

5. **Error Handling**
   - 404 page with custom design
   - Friendly error messages
   - Retry functionality

6. **Accessibility**
   - ARIA labels
   - Keyboard navigation
   - Screen reader support
   - Focus indicators

7. **Performance**
   - Lazy loading images
   - Infinite scroll for long lists
   - Minified CSS/JS
   - Image optimization

8. **Social Features**
   - Share buttons (Facebook, Twitter, WhatsApp)
   - Embed movie cards
   - Social meta tags (Open Graph)

9. **Breadcrumbs**
   - Navigation breadcrumbs on all pages
   - Schema.org markup

10. **Back to Top Button**
    - Smooth scroll to top
    - Show/hide on scroll

### Backend Features

1. **Security**
   - SQL injection prevention (prepared statements)
   - XSS protection (htmlspecialchars)
   - CSRF tokens for forms
   - Password hashing (password_hash)
   - Session security
   - Input validation and sanitization

2. **AJAX API Endpoints**
   - `/api/get-movies.php` - Fetch movies with filters
   - `/api/search.php` - Search movies
   - `/api/filter.php` - Apply filters
   - Return JSON responses

3. **Image Upload**
   - Validate file types (jpg, png, webp)
   - Resize images
   - Generate thumbnails
   - Store in organized folders

4. **Slug Generation**
   - Auto-generate slugs from titles
   - Ensure uniqueness
   - Use in URLs for SEO

5. **View Counter**
   - Increment on movie page visit
   - Prevent duplicate counts (session-based)

6. **Logging**
   - Admin activity log
   - Error logging
   - Access logs

---

## Styling Guidelines

### CSS Architecture

```css
/* Use CSS Custom Properties for theming */
:root {
    --primary-bg: #0F0F0F;
    --secondary-bg: #1A1A1A;
    --card-bg: #1F1F1F;
    --accent-color: #E50914;
    --text-primary: #FFFFFF;
    --text-secondary: #B3B3B3;
    --border-color: rgba(255, 255, 255, 0.1);
    --hover-transform: scale(1.05);
    --transition-speed: 0.3s;
}

/* Glassmorphism Card */
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Movie Card Hover Effect */
.movie-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.movie-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

/* Gradient Text */
.gradient-text {
    background: linear-gradient(45deg, #E50914, #FF6B6B);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Smooth Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--secondary-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--accent-color);
    border-radius: 4px;
}
```

### Animation Examples

```css
/* Fade In Up Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pulse Animation for Buttons */
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(229, 9, 20, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(229, 9, 20, 0);
    }
}

/* Loading Spinner */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
```

---

## JavaScript Functionality

### Required Interactions

1. **Smooth Scroll**
```javascript
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
```

2. **Lazy Loading Images**
```javascript
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
});

images.forEach(img => imageObserver.observe(img));
```

3. **AJAX Movie Loading**
```javascript
async function loadMovies(filter = {}) {
    try {
        const response = await fetch('/api/get-movies.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(filter)
        });
        const data = await response.json();
        renderMovies(data.movies);
    } catch (error) {
        console.error('Error loading movies:', error);
    }
}
```

4. **Search with Debounce**
```javascript
let searchTimeout;
document.getElementById('search-input').addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(e.target.value);
    }, 500);
});
```

5. **Modal/Popup System**
```javascript
class Modal {
    constructor(selector) {
        this.modal = document.querySelector(selector);
    }
    
    open() {
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    close() {
        this.modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}
```

---

## Responsive Breakpoints

```css
/* Mobile First Approach */

/* Small devices (phones, 576px and down) */
@media (max-width: 576px) {
    .movie-grid { grid-template-columns: repeat(2, 1fr); }
    .hero-title { font-size: 2rem; }
}

/* Medium devices (tablets, 768px and down) */
@media (max-width: 768px) {
    .movie-grid { grid-template-columns: repeat(3, 1fr); }
    .navbar { flex-direction: column; }
}

/* Large devices (desktops, 992px and down) */
@media (max-width: 992px) {
    .movie-grid { grid-template-columns: repeat(4, 1fr); }
}

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) {
    .movie-grid { grid-template-columns: repeat(6, 1fr); }
    .container { max-width: 1400px; }
}
```

---

## SEO Optimization

1. **Meta Tags** (in header)
```php
<title><?= $pageTitle ?> | MovieHub</title>
<meta name="description" content="<?= $pageDescription ?>">
<meta name="keywords" content="movies, streaming, OTT, watch online">
<meta property="og:title" content="<?= $pageTitle ?>">
<meta property="og:description" content="<?= $pageDescription ?>">
<meta property="og:image" content="<?= $pageImage ?>">
<meta property="og:url" content="<?= $currentUrl ?>">
<meta name="twitter:card" content="summary_large_image">
```

2. **Semantic HTML**
   - Use `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`
   - Proper heading hierarchy (h1, h2, h3)
   - Alt text for all images

3. **Schema Markup** (for movie pages)
```php
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Movie",
    "name": "<?= $movie['title'] ?>",
    "description": "<?= $movie['description'] ?>",
    "datePublished": "<?= $movie['release_year'] ?>",
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $movie['rating'] ?>",
        "bestRating": "10"
    }
}
</script>
```

4. **Clean URLs**
   - Use `.htaccess` for URL rewriting
   - Example: `/movie/the-dark-knight` instead of `/movie-detail.php?id=123`

---

## Development Checklist

### Phase 1: Setup
- [ ] Create database and tables
- [ ] Set up project folder structure
- [ ] Configure database connection
- [ ] Create base includes (header, footer, navbar)
- [ ] Set up CSS architecture with variables
- [ ] Import external libraries (Swiper, AOS, Font Awesome)

### Phase 2: Admin Panel
- [ ] Create admin login system
- [ ] Build admin dashboard with statistics
- [ ] Implement movie CRUD operations
- [ ] Add genre management
- [ ] Add OTT platform management
- [ ] Implement image upload functionality
- [ ] Add form validation

### Phase 3: Frontend Pages
- [ ] Create landing page with hero section
- [ ] Add OTT platform buttons section
- [ ] Implement latest movies carousel
- [ ] Add trending movies section
- [ ] Create genre browsing section
- [ ] Build movie detail page
- [ ] Create all movies page with filters
- [ ] Add search functionality
- [ ] Build additional pages (About, Contact, FAQ, etc.)

### Phase 4: Styling & Animations
- [ ] Apply dark theme styling
- [ ] Add hover effects to all interactive elements
- [ ] Implement scroll animations with AOS
- [ ] Create loading states and skeletons
- [ ] Add micro-interactions
- [ ] Implement responsive design
- [ ] Add smooth transitions

### Phase 5: JavaScript Functionality
- [ ] Implement smooth scrolling
- [ ] Add lazy loading for images
- [ ] Create AJAX endpoints for dynamic content
- [ ] Build search with debounce
- [ ] Add modal/popup system
- [ ] Implement infinite scroll or pagination
- [ ] Add theme toggle functionality

### Phase 6: Additional Features
- [ ] Add breadcrumb navigation
- [ ] Implement favorites system
- [ ] Create 404 error page
- [ ] Add social sharing buttons
- [ ] Implement view counter
- [ ] Add newsletter signup form
- [ ] Create sitemap

### Phase 7: Optimization & Testing
- [ ] Optimize images
- [ ] Minify CSS and JavaScript
- [ ] Test on multiple browsers
- [ ] Test responsive design on devices
- [ ] Check accessibility
- [ ] Test all forms and validations
- [ ] Implement security measures
- [ ] Add error handling
- [ ] Test performance with Lighthouse

### Phase 8: Deployment
- [ ] Set up production database
- [ ] Configure production environment
- [ ] Upload files to server
- [ ] Test all functionality on live site
- [ ] Set up backup system
- [ ] Monitor for errors

---

## Code Standards

### PHP
- Use PSR-12 coding standards
- Always use prepared statements for database queries
- Sanitize all user inputs
- Use meaningful variable names
- Comment complex logic
- Separate business logic from presentation

### HTML
- Use semantic HTML5 elements
- Proper indentation (2 or 4 spaces)
- Include alt text for images
- Use data attributes for JavaScript hooks
- Close all tags properly

### CSS
- Use BEM naming convention for classes
- Group related properties
- Mobile-first approach
- Use CSS variables for theming
- Avoid !important unless necessary

### JavaScript
- Use ES6+ features
- Use const/let instead of var
- Use async/await for asynchronous code
- Handle errors with try-catch
- Use meaningful function and variable names
- Add comments for complex logic

---

## Sample Code Snippets

### Database Connection (config/database.php)
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'movie_website');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

### Helper Functions (includes/functions.php)
```php
<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function uploadImage($file, $directory = 'uploads/') {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $filename = $file['name'];
    $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    $newFilename = uniqid() . '.' . $fileExt;
    $destination = $directory . $newFilename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newFilename];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

function getMovies($filters = []) {
    global $pdo;
    
    $sql = "SELECT m.* FROM movies m WHERE 1=1";
    $params = [];
    
    if (!empty($filters['genre'])) {
        $sql .= " AND m.id IN (
            SELECT movie_id FROM movie_genres WHERE genre_id = :genre
        )";
        $params[':genre'] = $filters['genre'];
    }
    
    if (!empty($filters['ott'])) {
        $sql .= " AND m.id IN (
            SELECT movie_id FROM movie_ott WHERE ott_id = :ott
        )";
        $params[':ott'] = $filters['ott'];
    }
    
    if (!empty($filters['search'])) {
        $sql .= " AND m.title LIKE :search";
        $params[':search'] = '%' . $filters['search'] . '%';
    }
    
    $sql .= " ORDER BY m.created_at DESC";
    
    if (!empty($filters['limit'])) {
        $sql .= " LIMIT :limit";
        $params[':limit'] = (int)$filters['limit'];
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
?>
```

### Movie Card Component (HTML)
```html
<div class="movie-card" data-aos="fade-up">
    <div class="movie-poster">
        <img src="<?= $movie['poster_url'] ?>" 
             alt="<?= $movie['title'] ?>" 
             loading="lazy">
        <div class="movie-overlay">
            <a href="/movie/<?= $movie['slug'] ?>" class="play-button">
                <i class="fas fa-play"></i>
            </a>
        </div>
        <div class="rating-badge">
            <i class="fas fa-star"></i>
            <?= $movie['rating'] ?>
        </div>
    </div>
    <div class="movie-info">
        <h3 class="movie-title"><?= $movie['title'] ?></h3>
        <div class="movie-meta">
            <span><?= $movie['release_year'] ?></span>
            <span><?= $movie['duration'] ?></span>
        </div>
        <div class="movie-genres">
            <?php foreach($movie['genres'] as $genre): ?>
                <span class="genre-badge"><?= $genre ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
```

---

## Performance Tips

1. **Optimize Images**
   - Use WebP format with JPEG fallback
   - Compress images (TinyPNG, ImageOptim)
   - Use responsive images with srcset
   - Implement lazy loading

2. **Minimize HTTP Requests**
   - Combine CSS files
   - Combine JavaScript files
   - Use CSS sprites for icons
   - Use font icons instead of images

3. **Enable Caching**
   - Set proper cache headers
   - Use browser caching
   - Implement server-side caching for queries

4. **Code Optimization**
   - Minify CSS and JavaScript
   - Remove unused CSS
   - Defer non-critical JavaScript
   - Use async for external scripts

5. **Database Optimization**
   - Index frequently queried columns
   - Use LIMIT in queries
   - Avoid SELECT * queries
   - Use prepared statements

---

## Security Checklist

- [ ] Use prepared statements for all database queries
- [ ] Validate and sanitize all user inputs
- [ ] Implement CSRF protection for forms
- [ ] Use password_hash() for passwords
- [ ] Secure session configuration
- [ ] Implement rate limiting for login attempts
- [ ] Use HTTPS in production
- [ ] Set proper file permissions
- [ ] Disable error display in production
- [ ] Implement input length limits
- [ ] Use Content Security Policy headers
- [ ] Prevent directory listing
- [ ] Validate file uploads thoroughly
- [ ] Implement XSS protection

---

## Testing Scenarios

### Functional Testing
1. Admin can login with correct credentials
2. Admin can add a new movie with all fields
3. Admin can edit existing movie
4. Admin can delete a movie
5. Users can view all movies
6. Users can filter movies by genre
7. Users can filter movies by OTT platform
8. Search returns relevant results
9. Movie detail page displays all information
10. Carousel navigation works correctly

### UI/UX Testing
1. All buttons have hover effects
2. Animations trigger on scroll
3. Images load with lazy loading
4. Layout is responsive on all devices
5. Navigation is intuitive
6. Forms provide clear feedback
7. Error messages are user-friendly
8. Loading states are visible

### Performance Testing
1. Page load time under 3 seconds
2. Images are optimized
3. No render-blocking resources
4. Lighthouse score above 90

### Browser Testing
- Chrome
- Firefox
- Safari
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## Deployment Instructions

### Server Requirements
- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- mod_rewrite enabled (Apache)

### Deployment Steps
1. **Export Database**
   - Export schema and data
   - Import on production server

2. **Upload Files**
   - Upload all files via FTP/SFTP
   - Exclude sensitive files (.env, config with credentials)

3. **Configure Production**
   - Update database credentials
   - Set error reporting to off
   - Enable production mode

4. **Set Permissions**
   - uploads/ folder: 755
   - PHP files: 644
   - No write permissions for PHP files

5. **Configure .htaccess**
   - Enable URL rewriting
   - Set up redirects
   - Security headers

6. **Test Everything**
   - Test all pages
   - Check forms
   - Verify database connections
   - Test admin panel

7. **Monitor**
   - Set up error logging
   - Monitor server resources
   - Check backup system

---

## Future Enhancements

1. **User Authentication**
   - User registration and login
   - Personalized profiles
   - Watch history

2. **Social Features**
   - User reviews and ratings
   - Comments on movies
   - Share to social media

3. **Advanced Filtering**
   - Multiple filter combinations
   - Save filter preferences
   - Custom collections

4. **Recommendations**
   - AI-based movie recommendations
   - "Because you watched" sections
   - Trending in your area

5. **API Integration**
   - TMDB API for movie data
   - IMDB ratings
   - Automatic poster fetching

6. **Mobile App**
   - React Native mobile app
   - Push notifications
   - Offline mode

7. **Advanced Admin Features**
   - Analytics dashboard
   - User management
   - Content scheduling
   - Bulk import from CSV

8. **Monetization**
   - Affiliate links integration
   - Premium memberships
   - Ad spaces management

---

## Reference Resources

### Design Inspiration
- Netflix (https://www.netflix.com)
- Disney+ (https://www.disneyplus.com)
- Amazon Prime Video (https://www.primevideo.com)
- HBO Max (https://www.hbomax.com)
- Apple TV+ (https://tv.apple.com)
- Hulu (https://www.hulu.com)
- Dribbble: Search "movie website" or "streaming platform"
- Behance: Search "OTT platform design"

### Code Resources
- Swiper.js: https://swiperjs.com/
- AOS: https://michalsnik.github.io/aos/
- Font Awesome: https://fontawesome.com/
- Google Fonts: https://fonts.google.com/
- CSS Tricks: https://css-tricks.com/
- MDN Web Docs: https://developer.mozilla.org/

### GitHub Repositories for Reference
- Search for: "movie website php"
- Search for: "streaming platform UI"
- Search for: "OTT website template"
- Look for: Admin dashboard templates

---

## Notes for GitHub Copilot Agent

When implementing this website:

1. **Start with database setup** - Create all tables first
2. **Build admin panel first** - This will help populate content for testing frontend
3. **Focus on one section at a time** - Complete each section before moving to next
4. **Use placeholders initially** - Real movie data can be added later
5. **Make it modular** - Each component should be reusable
6. **Comment your code** - Explain complex logic
7. **Test frequently** - Test each feature as you build it
8. **Follow the design principles** - Dark theme, modern UI, smooth animations
9. **Optimize as you go** - Don't wait until the end for optimization
10. **Make it extensible** - Structure code so features can be added easily

**Priority Order:**
1. Database + Config Setup
2. Admin Login System
3. Admin Movie Management (CRUD)
4. Landing Page with Hero Section
5. OTT Platform Section
6. Movie Listings and Carousels
7. Movie Detail Page
8. Additional Pages (About, Contact, etc.)
9. Search and Filter Functionality
10. Polish and Animations

Remember: **Focus on creating a visually stunning, user-attractive website that rivals top OTT platforms in design while maintaining clean, secure PHP code.**