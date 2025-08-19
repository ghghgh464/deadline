-- Database setup for MMO Services
-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS mmo;
USE mmo;

-- Create admin_users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    role ENUM('admin', 'moderator') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert admin account (username: admin, password: admin1234)
INSERT INTO admin_users (username, password, email, full_name, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mmoservices.com', 'Administrator', 'admin')
ON DUPLICATE KEY UPDATE 
    password = VALUES(password),
    email = VALUES(email),
    full_name = VALUES(full_name);

-- Insert sample user accounts for testing
INSERT INTO users (username, password, email, full_name, phone) VALUES 
('user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user1@example.com', 'Nguyễn Văn A', '0123456789'),
('user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user2@example.com', 'Trần Thị B', '0987654321')
ON DUPLICATE KEY UPDATE 
    password = VALUES(password),
    email = VALUES(email),
    full_name = VALUES(full_name);

-- Insert sample products data
INSERT INTO products (name, description, price, image, category, stock) VALUES
('Netflix Premium', 'Tài khoản Netflix Premium 4K, không giới hạn thiết bị', 150000, 'netflix.png', 'Streaming', 50),
('Spotify Premium', 'Tài khoản Spotify Premium không quảng cáo', 120000, 'spotify premium.png', 'Music', 30),
('Microsoft 365', 'Gói Microsoft 365 Family với Word, Excel, PowerPoint', 800000, 'microsoft 365.png', 'Office', 20),
('Canva Pro', 'Tài khoản Canva Pro cho thiết kế đồ họa', 200000, 'canva pro.png', 'Design', 25),
('CapCut Pro', 'Tài khoản CapCut Pro cho chỉnh sửa video', 180000, 'capcut pro.png', 'Video', 15),
('Xbox Game Pass Ultimate', 'Tài khoản Xbox Game Pass Ultimate 3 tháng', 450000, 'xbox gamepass ultimate.png', 'Gaming', 40),
('Google Drive', 'Google Drive 2TB lưu trữ đám mây', 300000, 'google drive.png', 'Storage', 35),
('MB Bank', 'Tài khoản MB Bank với các tiện ích', 100000, 'mb bank.png', 'Banking', 10),
('MoMo', 'Tài khoản MoMo với ví điện tử', 80000, 'momo.png', 'Payment', 60),
('Tích Xanh', 'Tài khoản Tích Xanh cho doanh nghiệp', 250000, 'tichxanh.png', 'Business', 12);

-- Display created accounts info
SELECT 'Admin Account Created:' as info;
SELECT username, 'admin1234' as plain_password, role FROM admin_users WHERE username = 'admin';

SELECT 'User Accounts Created:' as info;
SELECT username, 'password' as plain_password FROM users LIMIT 2;

SELECT 'Products Count:' as info;
SELECT COUNT(*) as total_products FROM products; 