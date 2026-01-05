-- ============================================================================
-- Database Initialization Script
-- ============================================================================
-- This script sets up the database schema for the blog application.
-- It creates tables for Users, Posts, and Comments and establishes
-- relational constraints (Foreign Keys) with cascading deletion.
-- ============================================================================

-- 1. Users Table
-- Stores authentication details.
-- 'username' must be unique to prevent duplicates.
-- 'password_hash' stores the argon2/bcrypt hash, never plain text.
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Posts Table
-- Stores the actual blog entries.
-- Contains a Foreign Key link to the 'users' table (the author).
-- ON DELETE CASCADE: If a user is deleted, all their posts are automatically deleted.
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Comments Table
-- Stores user discussions on posts.
-- links to BOTH 'posts' (where it belongs) and 'users' (who wrote it).
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;