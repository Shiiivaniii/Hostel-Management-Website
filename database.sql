
-- Create Database
CREATE DATABASE IF NOT EXISTS hostel_db;

-- Use Database
USE hostel_db;

-- ============================
-- Table: Admins (for login)
-- ============================

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin
-- Password is: admin123
-- (This is hashed version)
INSERT INTO admins (username, password)
VALUES (
    'admin',
    '$2y$10$wH1QkzV9vPz6G7X3F9hS0u5YyM5Hk2RzK8y9N1bXxWZxqFhM1jG7e'
);

-- ============================
-- Table: Announcements
-- ============================

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);