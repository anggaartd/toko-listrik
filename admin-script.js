// Modal functions
function showAddProductModal() {
    document.getElementById('addProductModal').style.display = 'block';
}

function hideAddProductModal() {
    document.getElementById('addProductModal').style.display = 'none';
    document.getElementById('addProductForm').reset();
    // Clear image preview
    document.getElementById('imagePreview').innerHTML = '';
}

// Edit product function
function editProduct(id) {
    // Redirect to edit page or show edit modal
    window.location.href = `admin-edit.php?id=${id}`;
}

// Delete product function
function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?\n\nGambar produk juga akan dihapus secara permanen.')) {
        window.location.href = `admin-actions.php?action=delete&id=${id}`;
    }
}

// Image preview function
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF.');
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.products-table tbody tr');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const productName = row.querySelector('.product-info h4').textContent.toLowerCase();
                const productDesc = row.querySelector('.product-info p').textContent.toLowerCase();
                const category = row.querySelector('.category-badge').textContent.toLowerCase();
                
                if (productName.includes(searchTerm) || 
                    productDesc.includes(searchTerm) || 
                    category.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Image preview for add product modal
    const addImageInput = document.getElementById('image');
    if (addImageInput) {
        addImageInput.addEventListener('change', function() {
            previewImage(this, 'imagePreview');
        });
    }
    
    // Image preview for edit product page
    const editImageInput = document.querySelector('input[name="image"]');
    if (editImageInput && document.getElementById('imagePreview')) {
        editImageInput.addEventListener('change', function() {
            previewImage(this, 'imagePreview');
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('addProductModal');
        if (event.target === modal) {
            hideAddProductModal();
        }
    });
    
    // Close modal with close button
    const closeBtn = document.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', hideAddProductModal);
    }
    
    // Form validation
    const form = document.getElementById('addProductForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const description = document.getElementById('description').value.trim();
            const price = document.getElementById('price').value;
            const category = document.getElementById('category').value;
            
            if (!name || !description || !price || !category) {
                e.preventDefault();
                alert('Harap isi semua field yang diperlukan!');
                return false;
            }
            
            if (price <= 0) {
                e.preventDefault();
                alert('Harga harus lebih dari 0!');
                return false;
            }
        });
    }
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Format price input
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});