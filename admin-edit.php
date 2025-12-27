<?php
require_once 'config.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: admin-dashboard.php');
    exit();
}

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = 'Produk tidak ditemukan!';
    header('Location: admin-dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Jayakarta Electric</title>
    
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
            <a href="admin-settings.php" class="nav-item">
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
                <h1>Edit Produk</h1>
                <p>Perbarui informasi produk</p>
            </div>
            <a href="admin-dashboard.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Edit Form -->
        <div class="form-container">
            <form method="POST" action="admin-actions.php" class="edit-form" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-tag"></i>
                            Nama Produk
                        </label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">
                            <i class="fas fa-money-bill-wave"></i>
                            Harga
                        </label>
                        <input type="number" id="price" name="price" value="<?= $product['price'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-list"></i>
                            Kategori
                        </label>
                        <select id="category" name="category" required>
                            <option value="tools" <?= $product['category'] == 'tools' ? 'selected' : '' ?>>Tools</option>
                            <option value="electrical" <?= $product['category'] == 'electrical' ? 'selected' : '' ?>>Electrical</option>
                            <option value="industrial" <?= $product['category'] == 'industrial' ? 'selected' : '' ?>>Industrial</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-toggle-on"></i>
                            Status
                        </label>
                        <select id="status" name="status" required>
                            <option value="aktif" <?= $product['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $product['status'] == 'nonaktif' ? 'selected' : '' ?>>Non-aktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="description">
                        <i class="fas fa-align-left"></i>
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label for="image">
                        <i class="fas fa-image"></i>
                        Gambar Produk
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" class="file-input">
                    <div class="file-info">
                        <small>Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                    
                    <?php if ($product['image'] && file_exists('uploads/products/' . $product['image'])): ?>
                        <div class="current-image">
                            <label>Gambar Saat Ini:</label>
                            <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Current Image" style="max-width: 200px; height: auto; border-radius: 8px; margin-top: 0.5rem;">
                        </div>
                    <?php endif; ?>
                    
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="admin-dashboard.php" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .full-width {
        grid-column: 1 / -1;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
    </style>

    <script src="admin-script.js"></script>
</body>
</html>