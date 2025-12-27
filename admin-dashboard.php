<?php
require_once 'config.php';
requireLogin();

// Ambil statistik
$stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
$total_products = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as active FROM products WHERE status = 'aktif'");
$active_products = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(price) as total_value FROM products WHERE status = 'aktif'");
$total_value = $stmt->fetchColumn() ?: 0;

// Ambil semua produk
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Jayakarta Electric</title>
    
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
            <a href="admin-dashboard.php" class="nav-item active">
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
        <!-- Content Header -->
        <div class="content-header">
            <div>
                <h1>Manajemen Produk</h1>
                <p>Kelola produk  dengan mudah</p>
            </div>
            <button class="btn-primary" onclick="showAddProductModal()">
                <i class="fas fa-plus"></i>
                Tambah Produk Baru
            </button>
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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $total_products ?></h3>
                    <p>TOTAL PRODUK</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $active_products ?></h3>
                    <p>PRODUK AKTIF</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon value">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>Rp <?= number_format($total_value, 0, ',', '.') ?></h3>
                    <p>NILAI JUAL HARGA</p>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari produk..." id="searchInput">
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>PRODUK</th>
                        <th>KATEGORI</th>
                        <th>HARGA</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <div class="product-image">
                                    <?php if ($product['image'] && file_exists('uploads/products/' . $product['image'])): ?>
                                        <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php else: ?>
                                        <img src="https://images.unsplash.com/photo-1563379091339-03246963d51a?w=50&h=50&fit=crop" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4><?= htmlspecialchars($product['name']) ?></h4>
                                    <p><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="category-badge <?= $product['category'] ?>">
                                <?= strtoupper($product['category']) ?>
                            </span>
                        </td>
                        <td class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td>
                            <span class="status-badge <?= $product['status'] ?>">
                                <i class="fas fa-circle"></i>
                                <?= ucfirst($product['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editProduct(<?= $product['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteProduct(<?= $product['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus"></i> Tambah Produk Baru</h3>
                <span class="close">&times;</span>
            </div>
            <form id="addProductForm" method="POST" action="admin-actions.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-tag"></i>
                            Nama Produk
                        </label>
                        <input type="text" id="name" name="name" required placeholder="Masukkan nama produk">
                    </div>
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i>
                            Deskripsi
                        </label>
                        <textarea id="description" name="description" rows="3" required placeholder="Deskripsi produk"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">
                            <i class="fas fa-image"></i>
                            Gambar Produk
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" class="file-input">
                        <div class="file-info">
                            <small>Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        </div>
                        <div id="imagePreview" class="image-preview"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">
                                <i class="fas fa-money-bill-wave"></i>
                                Harga
                            </label>
                            <input type="number" id="price" name="price" required placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for="category">
                                <i class="fas fa-list"></i>
                                Kategori
                            </label>
                            <select id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="tools">Tools</option>
                                <option value="electrical">Electrical</option>
                                <option value="industrial">Industrial</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-toggle-on"></i>
                            Status
                        </label>
                        <select id="status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="hideAddProductModal()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="admin-script.js"></script>
</body>
</html>