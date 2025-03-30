-- ===========================
-- DATABASE CREATION
-- ===========================
CREATE DATABASE IF NOT EXISTS venue_db;
USE venue_db;

-- ===========================
-- VENUES TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS venues (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id INT UNSIGNED NOT NULL,  
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    lat DECIMAL(10,6) NOT NULL,
    lng DECIMAL(10,6) NOT NULL,
    capacity INT NOT NULL CHECK (capacity > 0),
    tags JSON NOT NULL,  
    category VARCHAR(255) NOT NULL,
    category2 ENUM('low price', 'mid price', 'high price') NOT NULL,
    category3 ENUM('5', '6', '7', '8', '10', '12', '15', '20', '25') NOT NULL,
    image VARCHAR(255) DEFAULT 'uploads/default_court.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Indexes for performance
    INDEX idx_category (category),
    INDEX idx_price_range (category2),
    INDEX idx_capacity_range (category3),
    INDEX idx_location (lat, lng)
);




-- ===========================
-- VENUE DETAILS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS venue_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id INT UNSIGNED NOT NULL,  
    venue_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    maps_url VARCHAR(255) DEFAULT NULL,
    facebook VARCHAR(255) DEFAULT NULL,
    twitter VARCHAR(255) DEFAULT NULL,
    instagram VARCHAR(255) DEFAULT NULL,
    header_photo VARCHAR(255) NOT NULL,
    main_image VARCHAR(255) NOT NULL,
    video_tour VARCHAR(255) DEFAULT NULL,
    thumbnails JSON NOT NULL,  -- ✅ JSON format for multiple image paths
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
);


-- ===========================
-- USERS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  
    profile_image VARCHAR(255) NULL DEFAULT '/venue_locator/images/default_profile.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ensure no duplicate index before adding
DROP INDEX IF EXISTS idx_users_username ON users;
CREATE INDEX idx_users_username ON users (username);

-- ===========================
-- ADMINS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert a sample admin user (Ensure you hash passwords properly in PHP)
INSERT INTO admins (username, password) 
VALUES 
    ('admin', '$2y$10$abcdefghijABCDEFGHIJabcdefghijABCDEFGHIJabcdefghijABCDEFGHIJ')
ON DUPLICATE KEY UPDATE username=username;

-- ===========================
-- BOOKINGS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id INT UNSIGNED NOT NULL,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    num_attendees INT NOT NULL CHECK (num_attendees > 0),
    total_cost DECIMAL(10,2) NOT NULL CHECK (total_cost >= 0),
    payment_method ENUM('Cash', 'Credit/Debit', 'Online') NOT NULL,
    shared_booking BOOLEAN NOT NULL DEFAULT FALSE,
    id_photo VARCHAR(255) NULL,
    status ENUM('Pending', 'Canceled', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
);

-- ===========================
-- USER ADMIN TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS user_admin (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) NULL DEFAULT '/venue_locator/images/default_profile.png',
    school_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================
-- PERFORMANCE INDEXING (Fixed)
-- ===========================
DROP INDEX IF EXISTS idx_users_username ON users;
CREATE INDEX idx_users_username ON users (username);

DROP INDEX IF EXISTS idx_venue_name ON venues;
CREATE INDEX idx_venue_name ON venues (name);

DROP INDEX IF EXISTS idx_event_date ON bookings;
CREATE INDEX idx_event_date ON bookings (event_date);

-- ===========================
-- TEST QUERIES TO VERIFY DATA
-- ===========================
SHOW TABLES;

-- Check structure of tables
DESCRIBE users;
DESCRIBE venues;
DESCRIBE bookings;

-- Check total records in tables
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM venues;
SELECT COUNT(*) FROM bookings;

-- View sample data
SELECT * FROM users;
SELECT * FROM venue_details;
SELECT id, image FROM venues;
SELECT username, profile_image FROM users;
ALTER TABLE venues MODIFY category3 INT NOT NULL;


-- Verify foreign keys are correctly set
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'venue_db' AND REFERENCED_TABLE_NAME IS NOT NULL;
