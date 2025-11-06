-- hdfile.live Movie Website Database Setup
-- Execute this file in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS hdfile_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hdfile_live;

-- Admin Users Table
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@hdfile.live');

-- Movies Table
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

-- Genres Table
CREATE TABLE genres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    color VARCHAR(20)
);

-- Insert default genres
INSERT INTO genres (name, slug, description, icon, color) VALUES
('Action', 'action', 'Explosive adventures and thrilling stunts', 'fa-explosion', '#E50914'),
('Comedy', 'comedy', 'Laugh out loud moments', 'fa-face-laugh', '#FFB800'),
('Drama', 'drama', 'Compelling stories and performances', 'fa-masks-theater', '#00A8E1'),
('Horror', 'horror', 'Spine-chilling scares', 'fa-ghost', '#8B00FF'),
('Romance', 'romance', 'Love stories that touch the heart', 'fa-heart', '#FF1493'),
('Sci-Fi', 'sci-fi', 'Future worlds and technology', 'fa-rocket', '#00D9FF'),
('Thriller', 'thriller', 'Edge of your seat suspense', 'fa-bolt', '#FF4500'),
('Animation', 'animation', 'Animated adventures for all ages', 'fa-wand-sparkles', '#32CD32');

-- Movie Genres (Many-to-Many)
CREATE TABLE movie_genres (
    movie_id INT,
    genre_id INT,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);

-- OTT Platforms Table
CREATE TABLE ott_platforms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    logo_url VARCHAR(500),
    color VARCHAR(20),
    description TEXT,
    display_order INT DEFAULT 0
);

-- Insert default OTT platforms
INSERT INTO ott_platforms (name, slug, logo_url, color, description, display_order) VALUES
('Netflix', 'netflix', 'assets/images/logos/netflix.png', '#E50914', 'Stream unlimited movies and TV shows', 1),
('Amazon Prime', 'amazon-prime', 'assets/images/logos/prime.png', '#00A8E1', 'Watch award-winning originals and more', 2),
('Disney+ Hotstar', 'disney-hotstar', 'assets/images/logos/hotstar.png', '#1A98FF', 'Disney, Marvel, Star Wars and more', 3),
('HBO Max', 'hbo-max', 'assets/images/logos/hbo.png', '#B026FF', 'Home of HBO originals and blockbusters', 4);

-- Movie OTT (Many-to-Many)
CREATE TABLE movie_ott (
    movie_id INT,
    ott_id INT,
    PRIMARY KEY (movie_id, ott_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (ott_id) REFERENCES ott_platforms(id) ON DELETE CASCADE
);

-- Cast & Crew Table
CREATE TABLE cast_crew (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    role ENUM('actor', 'director', 'producer', 'writer') NOT NULL,
    photo_url VARCHAR(500),
    bio TEXT
);

-- Movie Cast (Many-to-Many)
CREATE TABLE movie_cast (
    movie_id INT,
    cast_id INT,
    character_name VARCHAR(255),
    PRIMARY KEY (movie_id, cast_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (cast_id) REFERENCES cast_crew(id) ON DELETE CASCADE
);

-- Sample Movies Data
INSERT INTO movies (title, slug, description, release_year, duration, rating, poster_url, banner_url, trailer_url, is_featured, is_trending, views) VALUES
('The Dark Knight', 'the-dark-knight', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 2008, '2h 32min', 9.0, 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg', 'https://image.tmdb.org/t/p/original/hkBaDkMWbLaf8B1lsWsKX7Ew3Xq.jpg', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', 1, 1, 15420),
('Inception', 'inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.', 2010, '2h 28min', 8.8, 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg', 'https://image.tmdb.org/t/p/original/s3TBrRGB1iav7gFOCNx3H31MoES.jpg', 'https://www.youtube.com/watch?v=YoHD9XEInc0', 1, 1, 12850),
('Interstellar', 'interstellar', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.', 2014, '2h 49min', 8.6, 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'https://image.tmdb.org/t/p/original/xu9zaAevzQ5nnrsXN6JcahLnG4i.jpg', 'https://www.youtube.com/watch?v=zSWdZVtXT7E', 1, 1, 11200);

-- Link movies to genres
INSERT INTO movie_genres (movie_id, genre_id) VALUES
(1, 1), (1, 3), (1, 7),  -- The Dark Knight: Action, Drama, Thriller
(2, 1), (2, 6), (2, 7),  -- Inception: Action, Sci-Fi, Thriller
(3, 3), (3, 6), (3, 1);  -- Interstellar: Drama, Sci-Fi, Action

-- Link movies to OTT platforms
INSERT INTO movie_ott (movie_id, ott_id) VALUES
(1, 1), (1, 2),  -- The Dark Knight on Netflix, Prime
(2, 1), (2, 3),  -- Inception on Netflix, Hotstar
(3, 2), (3, 4);  -- Interstellar on Prime, HBO
