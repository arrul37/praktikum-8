-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS db_app_user;
USE db_app_user;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    aktif BOOLEAN DEFAULT TRUE,
    CONSTRAINT chk_email CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create an initial admin user (password: Admin123)
INSERT INTO users (username, password, nama_lengkap, email, role) VALUES 
('admin', '$2y$10$8HxWhlz8NmU.XL3UBxk1auYmUV4nMDgx1pHi0Q7yUw9ttYr0ZRHR.', 'Administrator', 'admin@example.com', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- Create indexes for better performance
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_role ON users(role);
