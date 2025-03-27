-- Drop database if it exists and create a new one
DROP DATABASE IF EXISTS animate;
CREATE DATABASE animate;
USE animate;

-- Create admins table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'General',
    image_url VARCHAR(255) DEFAULT NULL,
    youtube_url VARCHAR(255) DEFAULT NULL,
    youtube_id VARCHAR(11) DEFAULT NULL,
    is_private BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create users table (referenced in queries.sql)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$YYxnpFMgUVBiDSn5COHKWO1bHIfnVJ.xvBjOWIkjsAB5f0gRrIkuy');

-- Insert sample posts
INSERT INTO posts (title, content, category, youtube_id, is_private) VALUES
('Getting Started with Web Animation', '<p>Web animation can bring your site to life and improve user experience. Here are some tips to get started...</p>', 'Development', NULL, FALSE),
('Modern Web Design Trends', '<p>Discover the latest trends in web design that are shaping the digital landscape in 2025...</p>', 'Design', NULL, FALSE);

-- Insert sample user
INSERT INTO users (username, email, password) VALUES
('user1', 'user1@example.com', '$2y$10$YYxnpFMgUVBiDSn5COHKWO1bHIfnVJ.xvBjOWIkjsAB5f0gRrIkuy');