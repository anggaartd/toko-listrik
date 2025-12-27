<?php
// Script untuk download sample images dari Unsplash
// Jalankan sekali untuk mendapatkan sample images

$images = [
    'dimsum1.jpg' => 'https://images.unsplash.com/photo-1563379091339-03246963d51a?w=400&h=300&fit=crop',
    'dimsum2.jpg' => 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=400&h=300&fit=crop',
    'dimsum3.jpg' => 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?w=400&h=300&fit=crop',
    'dimsum4.jpg' => 'https://images.unsplash.com/photo-1563379091339-03246963d51a?w=400&h=300&fit=crop&sat=-100'
];

foreach ($images as $filename => $url) {
    $imageData = file_get_contents($url);
    if ($imageData) {
        file_put_contents('uploads/products/' . $filename, $imageData);
        echo "Downloaded: $filename\n";
    }
}

echo "Sample images downloaded successfully!\n";
echo "You can now delete this file (sample-images.php)\n";
?>