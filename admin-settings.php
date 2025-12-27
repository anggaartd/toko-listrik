<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website - Jayakarta Electric</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= getLogo() ?>">
    <link rel="shortcut icon" type="image/png" href="<?= getLogo() ?>">
    <link rel="apple-touch-icon" href="<?= getLogo() ?>">
    
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="admin-page">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?= getLogo() ?>" alt="Logo" style="width: 24px; height: 24px; margin-right: 0.5rem;">
            <span>Admin Panel</span>
        </div>
        <nav class="sidebar-nav">
            <a href="admin-dashboard.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="admin-settings.php" class="nav-item active">
                <i class="fas fa-cog"></i>
                Pengaturan
            </a>
            <a href="admin-logout.php" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i>
                Keluar Dashboard
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <div>
                <h1>Pengaturan Website</h1>
                <p>Kelola pengaturan dan konfigurasi website</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Settings Form -->
        <div class="settings-container">
            <form method="POST" action="admin-settings-actions.php" enctype="multipart/form-data" class="settings-form">
                <input type="hidden" name="action" value="update_settings">
                
                <!-- Website Info Section -->
                <div class="settings-section">
                    <h3><i class="fas fa-globe"></i> Informasi Website</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="site_name">
                                <i class="fas fa-building"></i>
                                Nama Website
                            </label>
                            <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars(getSiteName()) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_tagline">
                                <i class="fas fa-tag"></i>
                                Tagline
                            </label>
                            <input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars(getSiteTagline()) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description">
                            <i class="fas fa-align-left"></i>
                            Deskripsi Website
                        </label>
                        <textarea id="site_description" name="site_description" rows="3" required><?= htmlspecialchars(getSiteDescription()) ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_logo">
                            <i class="fas fa-image"></i>
                            Logo Website
                        </label>
                        <input type="file" id="site_logo" name="site_logo" accept="image/*" class="file-input">
                        <div class="file-info">
                            <small>Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah logo.</small>
                        </div>
                        
                        <div class="current-logo">
                            <label>Logo Saat Ini:</label>
                            <img src="<?= getLogo() ?>" alt="Current Logo" style="max-width: 100px; height: auto; border-radius: 8px; margin-top: 0.5rem;">
                        </div>
                    </div>
                </div>

                <!-- Contact Info Section -->
                <div class="settings-section">
                    <h3><i class="fas fa-address-book"></i> Informasi Kontak</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="site_phone">
                                <i class="fas fa-phone"></i>
                                Nomor Telepon
                            </label>
                            <input type="text" id="site_phone" name="site_phone" value="<?= htmlspecialchars(getSitePhone()) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_number">
                                <i class="fab fa-whatsapp"></i>
                                Nomor WhatsApp
                            </label>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?= htmlspecialchars(getWhatsAppNumber()) ?>" required>
                            <small>Format: 6281234567890 (tanpa tanda +)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" id="site_email" name="site_email" value="<?= htmlspecialchars(getSiteEmail()) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_address">
                            <i class="fas fa-map-marker-alt"></i>
                            Alamat
                        </label>
                        <textarea id="site_address" name="site_address" rows="2" required><?= htmlspecialchars(getSiteAddress()) ?></textarea>
                    </div>
                </div>

                <!-- Hero Section Settings -->
                <div class="settings-section">
                    <h3><i class="fas fa-star"></i> Pengaturan Hero Section</h3>
                    <div class="form-group">
                        <label for="hero_title">
                            <i class="fas fa-heading"></i>
                            Judul Hero
                        </label>
                        <input type="text" id="hero_title" name="hero_title" value="<?= htmlspecialchars(getSetting('hero_title', 'Solusi Terpercaya Alat Listrik Berkualitas')) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hero_subtitle">
                            <i class="fas fa-paragraph"></i>
                            Subtitle Hero
                        </label>
                        <textarea id="hero_subtitle" name="hero_subtitle" rows="2" required><?= htmlspecialchars(getSetting('hero_subtitle', 'Dapatkan alat listrik berkualitas tinggi dengan harga terjangkau')) ?></textarea>
                    </div>
                </div>

                <!-- Products Section Settings -->
                <div class="settings-section">
                    <h3><i class="fas fa-boxes"></i> Pengaturan Section Produk</h3>
                    <div class="form-group">
                        <label for="products_title">
                            <i class="fas fa-heading"></i>
                            Judul Section Produk
                        </label>
                        <input type="text" id="products_title" name="products_title" value="<?= htmlspecialchars(getSetting('products_title', 'Produk Unggulan Kami')) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="products_subtitle">
                            <i class="fas fa-paragraph"></i>
                            Subtitle Section Produk
                        </label>
                        <textarea id="products_subtitle" name="products_subtitle" rows="2" required><?= htmlspecialchars(getSetting('products_subtitle', 'Pilihan alat listrik berkualitas dengan harga terjangkau dan bergaransi resmi')) ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pengaturan
                    </button>
                    <a href="admin-dashboard.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
    .settings-container {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .settings-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .settings-section:last-of-type {
        border-bottom: none;
        margin-bottom: 2rem;
    }
    
    .settings-section h3 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.3rem;
        background: linear-gradient(45deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f3f4;
    }
    
    .current-logo {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .current-logo label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .settings-container {
            padding: 1.5rem;
        }
    }
    </style>

    <script src="admin-script.js"></script>
</body>
</html>