<?php
require_once 'config.php';
requireLogin();

$action = $_POST['action'] ?? '';

// Function untuk upload logo
function uploadLogo($file) {
    $uploadDir = 'uploads/';
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
    $filename = 'logo_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    } else {
        throw new Exception('Gagal mengupload file.');
    }
}

// Function untuk hapus logo lama
function deleteOldLogo($filename) {
    if ($filename && $filename !== 'logo.png' && file_exists('uploads/' . $filename)) {
        unlink('uploads/' . $filename);
    }
}

switch ($action) {
    case 'update_settings':
        if ($_POST) {
            try {
                // Ambil data dari form
                $settings = [
                    'site_name' => trim($_POST['site_name']),
                    'site_tagline' => trim($_POST['site_tagline']),
                    'site_description' => trim($_POST['site_description']),
                    'site_phone' => trim($_POST['site_phone']),
                    'whatsapp_number' => trim($_POST['whatsapp_number']),
                    'site_email' => trim($_POST['site_email']),
                    'site_address' => trim($_POST['site_address']),
                    'hero_title' => trim($_POST['hero_title']),
                    'hero_subtitle' => trim($_POST['hero_subtitle']),
                    'products_title' => trim($_POST['products_title']),
                    'products_subtitle' => trim($_POST['products_subtitle'])
                ];
                
                // Validasi data wajib
                foreach ($settings as $key => $value) {
                    if (empty($value)) {
                        throw new Exception('Semua field wajib diisi!');
                    }
                }
                
                // Validasi email
                if (!filter_var($settings['site_email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Format email tidak valid!');
                }
                
                // Handle upload logo jika ada
                if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
                    $oldLogo = getSetting('site_logo', 'logo.png');
                    $newLogo = uploadLogo($_FILES['site_logo']);
                    
                    if ($newLogo) {
                        // Hapus logo lama
                        deleteOldLogo($oldLogo);
                        $settings['site_logo'] = $newLogo;
                    }
                }
                
                // Update semua pengaturan
                $success = true;
                foreach ($settings as $key => $value) {
                    if (!updateSetting($key, $value)) {
                        $success = false;
                        break;
                    }
                }
                
                if ($success) {
                    $_SESSION['success'] = 'Pengaturan berhasil disimpan!';
                } else {
                    $_SESSION['error'] = 'Gagal menyimpan beberapa pengaturan!';
                }
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Gagal menyimpan pengaturan: ' . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'Data tidak valid!';
        }
        header('Location: admin-settings.php');
        break;
        
    default:
        header('Location: admin-settings.php');
        break;
}
?>