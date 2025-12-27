<?php
require_once 'config.php';

// Ambil data produk dari database
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'aktif' ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung statistik
$total_products = count($products);
$active_products = count(array_filter($products, function($p) { return $p['status'] == 'aktif'; }));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jayakarta Electric - Solusi Alat Listrik Terpercaya</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= getLogo() ?>">
    <link rel="shortcut icon" type="image/png" href="<?= getLogo() ?>">
    <link rel="apple-touch-icon" href="<?= getLogo() ?>">
    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <img src="<?= getLogo() ?>" alt="Logo" style="width: 24px; height: 24px; margin-right: 0.5rem;">
                <span><?= strtoupper(getSetting('site_name', 'JAYAKARTA ELECTRIC')) ?></span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#products">Produk</a></li>
                <li><a href="#contact">Kontak</a></li>
                <li><a href="admin-login.php" class="admin-link"><i class="fas fa-user-shield"></i> Admin</a></li>
            </ul>
            <div class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <!-- Floating particles -->
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        
        <div class="hero-content">
            <div class="hero-text">
                <div class="badge">
                    <i class="fas fa-bolt"></i>
                    100% ORIGINAL & BERGARANSI
                </div>
                <h1><?= getSetting('hero_title', 'Solusi <span class="highlight">Terpercaya</span><br>Alat Listrik<br>Berkualitas.') ?></h1>
                <p><?= getSetting('hero_subtitle', getSiteDescription()) ?></p>
                <div class="hero-buttons">
                    <button class="btn-primary" onclick="scrollToProducts()">
                        <i class="fas fa-tools"></i>
                        Lihat Produk
                    </button>
                    <button class="btn-secondary" onclick="contactWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp Admin
                    </button>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?= getLogo() ?>" alt="<?= getSetting('site_name', 'Jayakarta Electric') ?> - <?= getSetting('site_tagline', 'Alat Listrik Berkualitas') ?>">
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products-section">
        <div class="container">
            <h2><?= getSetting('products_title', 'Produk Unggulan Kami') ?></h2>
            <p class="section-subtitle"><?= getSetting('products_subtitle', 'Pilihan alat listrik berkualitas dengan harga terjangkau dan bergaransi resmi') ?></p>
            
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                <div class="product-item" data-category="<?= htmlspecialchars($product['category']) ?>">
                    <div class="product-image">
                        <?php if ($product['image'] && file_exists('uploads/products/' . $product['image'])): ?>
                            <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=300&h=200&fit=crop" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <div class="product-overlay">
                            <button class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>, this)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <div class="product-price">
                            <span class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                            <span class="category-badge <?= $product['category'] ?>"><?= strtoupper($product['category']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span><?= getSitePhone() ?></span>
                </div>
                <div class="contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <span><?= getSitePhone() ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span><?= getSiteEmail() ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?= getSiteAddress() ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="cartItems"></div>
                <div class="cart-empty" style="display: none;">
                    <p>Keranjang masih kosong!</p>
                    <p>Pilih produk alat listrik favorit Anda dulu ya.</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="cart-total">
                    <strong>Total: Rp <span id="cartTotal">0</span></strong>
                </div>
                <button class="btn-primary" id="checkoutBtn">Lihat Menu</button>
            </div>
        </div>
    </div>

    <!-- Checkout Form Modal -->
    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-clipboard-list"></i> Detail Pengiriman</h3>
                <span class="close" onclick="hideCheckoutModal()">&times;</span>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="customerName">
                            <i class="fas fa-user"></i>
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input type="text" id="customerName" name="customerName" required placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <div class="form-group">
                        <label for="customerAddress">
                            <i class="fas fa-map-marker-alt"></i>
                            Alamat Lengkap <span class="required">*</span>
                        </label>
                        <textarea id="customerAddress" name="customerAddress" rows="3" required placeholder="Masukkan alamat lengkap untuk pengiriman"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="customerNotes">
                            <i class="fas fa-sticky-note"></i>
                            Catatan (Opsional)
                        </label>
                        <textarea id="customerNotes" name="customerNotes" rows="2" placeholder="Catatan khusus untuk pesanan (opsional)"></textarea>
                    </div>
                    
                    <div class="order-summary">
                        <h4><i class="fas fa-receipt"></i> Ringkasan Pesanan</h4>
                        <div id="checkoutSummary"></div>
                        <div class="checkout-total">
                            <strong>Total Pembayaran: Rp <span id="checkoutTotal">0</span></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="hideCheckoutModal()">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </button>
                    <button type="submit" class="btn-whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        Pesan via WhatsApp
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pass WhatsApp number from PHP to JavaScript
        const WHATSAPP_NUMBER = "<?= getWhatsAppNumber() ?>";
    </script>
    <script src="script.js?v=<?= time() ?>"></script>
</body>
</html>