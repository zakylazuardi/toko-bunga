CREATE DATABASE IF NOT EXISTS toko_bunga_florist CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toko_bunga_florist;

CREATE TABLE admins (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE umkm (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 slug VARCHAR(150) NOT NULL UNIQUE,
 template VARCHAR(30) NOT NULL DEFAULT 'template1',
 description TEXT,
 address TEXT,
 phone VARCHAR(30),
 email VARCHAR(150),
 hours VARCHAR(100),
 hero_title VARCHAR(255),
 hero_text TEXT,
 hero_image VARCHAR(255),
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 umkm_id INT NOT NULL,
 name VARCHAR(150) NOT NULL,
 category VARCHAR(100),
 description TEXT,
 price DECIMAL(12,2) DEFAULT 0,
 variation VARCHAR(255),
 stock_status ENUM('Ready','Terbatas','Pre-Order','Habis') DEFAULT 'Ready',
 image VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (umkm_id) REFERENCES umkm(id) ON DELETE CASCADE
);

CREATE TABLE testimonials (
 id INT AUTO_INCREMENT PRIMARY KEY,
 umkm_id INT NOT NULL,
 name VARCHAR(100) NOT NULL,
 rating TINYINT NOT NULL DEFAULT 5,
 message TEXT NOT NULL,
 status ENUM('Pending','Published','Rejected') DEFAULT 'Published',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (umkm_id) REFERENCES umkm(id) ON DELETE CASCADE
);

CREATE TABLE gallery (
 id INT AUTO_INCREMENT PRIMARY KEY,
 umkm_id INT NOT NULL,
 title VARCHAR(150),
 category VARCHAR(100),
 image VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (umkm_id) REFERENCES umkm(id) ON DELETE CASCADE
);


CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    activity VARCHAR(100) NOT NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id)
        REFERENCES admins(id)
        ON DELETE SET NULL
);


INSERT INTO admins (name,username,password) VALUES ('Super Admin','admin', '$2y$12$j6NC7yvOEeQzSf8SwURE7.rVG/0IA/40B1qGWkzHJ3dhc30CTuRs.');
-- Password demo di atas: admin123


-- Tabel untuk mencatat aktivitas yang dilakukan admin.
CREATE TABLE IF NOT EXISTS activity_logs (
 id INT AUTO_INCREMENT PRIMARY KEY,
 admin_id INT NULL,
 activity VARCHAR(100) NOT NULL,
 description TEXT NULL,
 ip_address VARCHAR(45) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_activity_logs_created_at (created_at),
 CONSTRAINT fk_activity_logs_admin
   FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);
