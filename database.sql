-- Database untuk website alat listrik Jayakarta Electric
CREATE DATABASE jayakarta_electric;
USE jayakarta_electric;

-- Tabel admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pengaturan website
CREATE TABLE website_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert admin default (username: admin, password: password)
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample products with electrical tools theme
INSERT INTO products (name, description, price, category, image) VALUES 
('Bor Listrik Bosch GSB 550', 'Bor listrik berkualitas tinggi dengan daya 550W, cocok untuk berbagai material', 450000, 'tools', 'bor-listrik.jpg'),
('Tang Ampere Digital Fluke', 'Tang ampere digital dengan akurasi tinggi untuk pengukuran arus listrik', 850000, 'electrical', 'tang-ampere.jpg'),
('Gerinda Tangan Makita 9553HN', 'Gerinda tangan 4 inch dengan motor powerful untuk pemotongan dan penghalusan', 320000, 'tools', 'gerinda-tangan.jpg'),
('Multimeter Digital Sanwa', 'Multimeter digital profesional untuk pengukuran tegangan, arus, dan resistansi', 275000, 'electrical', 'multimeter.jpg'),
('Mesin Las Inverter 200A', 'Mesin las inverter portable dengan teknologi IGBT untuk pengelasan berkualitas', 1250000, 'industrial', 'mesin-las.jpg');

-- Insert default website settings
INSERT INTO website_settings (setting_key, setting_value) VALUES 
('site_name', 'Jayakarta Electric'),
('site_tagline', 'Solusi Alat Listrik Terpercaya'),
('site_description', 'Dapatkan alat listrik berkualitas tinggi dengan harga terjangkau. Kami menyediakan berbagai peralatan listrik untuk kebutuhan rumah tangga dan industri dengan garansi resmi.'),
('site_logo', 'logo.png'),
('whatsapp_number', '6281234567890'),
('site_address', 'Jl. Jayakarta Electric No. 123, Jakarta'),
('site_email', 'info@jayakartaelectric.com'),
('site_phone', '+62 812-3456-7890'),
('hero_title', 'Solusi Terpercaya Alat Listrik Berkualitas'),
('hero_subtitle', 'Dapatkan alat listrik berkualitas tinggi dengan harga terjangkau'),
('products_title', 'Produk Unggulan Kami'),
('products_subtitle', 'Pilihan alat listrik berkualitas dengan harga terjangkau dan bergaransi resmi');