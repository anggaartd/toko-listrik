<?php
require_once 'config.php';
requireLogin();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Function untuk upload gambar
function uploadImage($file) {
    $uploadDir = 'uploads/products/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return null;
    }
    
    // Validasi tipe file
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF.');
    }
    
    // Validasi ukuran file
    if ($file['size'] > $maxSize) {
        throw new Exception('Ukuran file terlalu besar. Maksimal 2MB.');
    }
    
    // Generate nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    } else {
        throw new Exception('Gagal mengupload file.');
    }
}

// Function untuk hapus gambar lama
function deleteOldImage($filename) {
    if ($filename && file_exists('uploads/products/' . $filename)) {
        unlink('uploads/products/' . $filename);
    }
}

switch ($action) {
    case 'add':
        if ($_POST) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $category = $_POST['category'];
            $status = $_POST['status'];
            
            if ($name && $description && $price > 0 && $category && $status) {
                try {
                    // Upload gambar jika ada
                    $imageName = null;
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $imageName = uploadImage($_FILES['image']);
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, status, image) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $description, $price, $category, $status, $imageName]);
                    
                    $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Gagal menambahkan produk: ' . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = 'Harap isi semua field dengan benar!';
            }
        }
        header('Location: admin-dashboard.php');
        break;
        
    case 'edit':
        if ($_POST) {
            $id = intval($_POST['id']);
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $category = $_POST['category'];
            $status = $_POST['status'];
            
            if ($id && $name && $description && $price > 0 && $category && $status) {
                try {
                    // Ambil data produk lama
                    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    $oldProduct = $stmt->fetch();
                    
                    $imageName = $oldProduct['image']; // Keep old image by default
                    
                    // Upload gambar baru jika ada
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $newImageName = uploadImage($_FILES['image']);
                        if ($newImageName) {
                            // Hapus gambar lama
                            deleteOldImage($oldProduct['image']);
                            $imageName = $newImageName;
                        }
                    }
                    
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, status = ?, image = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category, $status, $imageName, $id]);
                    
                    $_SESSION['success'] = 'Produk berhasil diperbarui!';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Gagal memperbarui produk: ' . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = 'Harap isi semua field dengan benar!';
            }
        }
        header('Location: admin-dashboard.php');
        break;
        
    case 'delete':
        $id = intval($_GET['id']);
        if ($id) {
            try {
                // Ambil data produk untuk hapus gambar
                $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                
                if ($product) {
                    // Hapus gambar
                    deleteOldImage($product['image']);
                    
                    // Hapus produk dari database
                    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    
                    $_SESSION['success'] = 'Produk berhasil dihapus!';
                } else {
                    $_SESSION['error'] = 'Produk tidak ditemukan!';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Gagal menghapus produk: ' . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'ID produk tidak valid!';
        }
        header('Location: admin-dashboard.php');
        break;
        
    default:
        header('Location: admin-dashboard.php');
        break;
}
?>