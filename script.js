// Shopping cart functionality
let cart = [];

// Add to cart function - TIDAK MENAMPILKAN MODAL
function addToCart(id, name, price, buttonElement) {
    console.log('addToCart called - NO MODAL should show');
    
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }
    
    updateCartUI();
    showAddToCartNotification(name);
    
    // Add animation to button if provided
    if (buttonElement) {
        buttonElement.classList.add('added');
        buttonElement.innerHTML = '<i class="fas fa-check"></i>';
        
        setTimeout(() => {
            buttonElement.classList.remove('added');
            buttonElement.innerHTML = '<i class="fas fa-plus"></i>';
        }, 600);
    }
}

// Update cart UI
function updateCartUI() {
    const cartCount = document.querySelector('.cart-count');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Animate cart count if it changed
    if (cartCount && cartCount.textContent !== totalItems.toString()) {
        cartCount.classList.add('bounce');
        setTimeout(() => {
            cartCount.classList.remove('bounce');
        }, 600);
    }
    
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
    
    // Update modal content (tapi tidak menampilkan modal)
    updateCartModal();
}

// Update cart modal content
function updateCartModal() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const cartEmpty = document.querySelector('.cart-empty');
    const checkoutBtn = document.getElementById('checkoutBtn');
    
    if (!cartItems || !cartTotal || !cartEmpty || !checkoutBtn) return;
    
    if (cart.length === 0) {
        cartItems.style.display = 'none';
        cartEmpty.style.display = 'block';
        cartTotal.textContent = '0';
        checkoutBtn.innerHTML = '<i class="fas fa-tools"></i> Lihat Produk';
        checkoutBtn.onclick = () => {
            hideCartModal();
            scrollToProducts();
        };
        return;
    }
    
    cartItems.style.display = 'block';
    cartEmpty.style.display = 'none';
    
    let total = 0;
    cartItems.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        return `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <p>Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
                    <p class="item-subtotal">Subtotal: Rp ${itemTotal.toLocaleString('id-ID')}</p>
                </div>
                <div class="cart-item-controls">
                    <button onclick="decreaseQuantity(${item.id})">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="increaseQuantity(${item.id})">+</button>
                    <button onclick="removeFromCart(${item.id})" class="remove-btn">×</button>
                </div>
            </div>
        `;
    }).join('');
    
    cartTotal.textContent = total.toLocaleString('id-ID');
    
    // Update checkout button
    checkoutBtn.innerHTML = '<i class="fas fa-credit-card"></i> Checkout';
    checkoutBtn.onclick = showCheckoutModal;
}

// Show add to cart notification (bukan modal)
function showAddToCartNotification(productName) {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <span>${productName} ditambahkan ke keranjang!</span>
        </div>
    `;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Show cart modal - HANYA DIPANGGIL SAAT KLIK ICON KERANJANG
function showCartModal() {
    console.log('showCartModal called - Modal will show');
    
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Hide cart modal
function hideCartModal() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Increase quantity
function increaseQuantity(id) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.quantity += 1;
        updateCartUI();
    }
}

// Decrease quantity
function decreaseQuantity(id) {
    const item = cart.find(item => item.id === id);
    if (item && item.quantity > 1) {
        item.quantity -= 1;
        updateCartUI();
    }
}

// Remove from cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCartUI();
}

// Scroll to products
function scrollToProducts() {
    const productsSection = document.getElementById('products');
    if (productsSection) {
        productsSection.scrollIntoView({
            behavior: 'smooth'
        });
    }
}

// Scroll to menu (keep for backward compatibility)
function scrollToMenu() {
    scrollToProducts();
}

// Show checkout modal
function showCheckoutModal() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    
    // Update checkout summary
    updateCheckoutSummary();
    
    // Hide cart modal and show checkout modal
    hideCartModal();
    const checkoutModal = document.getElementById('checkoutModal');
    if (checkoutModal) {
        checkoutModal.style.display = 'block';
    }
}

// Hide checkout modal
function hideCheckoutModal() {
    const checkoutModal = document.getElementById('checkoutModal');
    if (checkoutModal) {
        checkoutModal.style.display = 'none';
    }
    // Clear form
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.reset();
    }
}

// Update checkout summary
function updateCheckoutSummary() {
    const checkoutSummary = document.getElementById('checkoutSummary');
    const checkoutTotal = document.getElementById('checkoutTotal');
    
    if (!checkoutSummary || !checkoutTotal) return;
    
    let total = 0;
    checkoutSummary.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        return `
            <div class="summary-item">
                <span class="item-name">${item.name}</span>
                <span class="item-qty">${item.quantity}x</span>
                <span class="item-price">Rp ${itemTotal.toLocaleString('id-ID')}</span>
            </div>
        `;
    }).join('');
    
    checkoutTotal.textContent = total.toLocaleString('id-ID');
}

// Checkout to WhatsApp with customer details
function checkoutToWhatsApp() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;
    
    const formData = new FormData(form);
    
    const customerName = formData.get('customerName').trim();
    const customerAddress = formData.get('customerAddress').trim();
    const customerNotes = formData.get('customerNotes').trim();
    
    if (!customerName || !customerAddress) {
        alert('Harap isi nama dan alamat lengkap!');
        return;
    }
    
    // Format pesan WhatsApp
    let message = "⚡ *PESANAN JAYAKARTA ELECTRIC* ⚡\n\n";
    
    // Customer details
    message += "👤 *Data Pelanggan:*\n";
    message += `Nama: ${customerName}\n`;
    message += `Alamat: ${customerAddress}\n`;
    if (customerNotes) {
        message += `Catatan: ${customerNotes}\n`;
    }
    message += "\n";
    
    // Order details
    message += "📋 *Detail Pesanan:*\n";
    let total = 0;
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        message += `${index + 1}. ${item.name}\n`;
        message += `   Qty: ${item.quantity}x\n`;
        message += `   Harga: Rp ${item.price.toLocaleString('id-ID')}\n`;
        message += `   Subtotal: Rp ${itemTotal.toLocaleString('id-ID')}\n\n`;
    });
    
    message += `💰 *TOTAL PEMBAYARAN: Rp ${total.toLocaleString('id-ID')}*\n\n`;
    message += "📍 Mohon konfirmasi pesanan ini.\n";
    message += "Terima kasih! 🙏";
    
    // Encode message untuk URL
    const encodedMessage = encodeURIComponent(message);
    
    // Nomor WhatsApp (dari database)
    const phoneNumber = WHATSAPP_NUMBER;
    
    // Buat URL WhatsApp
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
    
    // Buka WhatsApp
    window.open(whatsappURL, '_blank');
    
    // Clear cart dan close modal setelah checkout
    cart = [];
    updateCartUI();
    hideCheckoutModal();
    
    // Show success message
    alert('Pesanan berhasil dikirim ke WhatsApp! Terima kasih.');
}

// Contact WhatsApp function
function contactWhatsApp() {
    const message = "Halo! Saya ingin bertanya tentang produk alat listrik di Jayakarta Electric. Terima kasih! ⚡";
    const encodedMessage = encodeURIComponent(message);
    const phoneNumber = WHATSAPP_NUMBER; // Nomor WhatsApp dari database
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
    window.open(whatsappURL, '_blank');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Cart icon click - HANYA INI YANG MENAMPILKAN MODAL KERANJANG
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', showCartModal);
    }
    
    // Close modal buttons
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            hideCartModal();
            hideCheckoutModal();
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const cartModal = document.getElementById('cartModal');
        const checkoutModal = document.getElementById('checkoutModal');
        
        if (event.target === cartModal) {
            hideCartModal();
        }
        if (event.target === checkoutModal) {
            hideCheckoutModal();
        }
    });
    
    // Checkout form submit
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            checkoutToWhatsApp();
        });
    }
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.product-item, .products-section h2, .section-subtitle').forEach(el => {
        observer.observe(el);
    });
    
    // Parallax effect for hero background
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero');
        if (hero) {
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        }
    });
});

// Add CSS for cart items
const cartItemCSS = `
<style>
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-info h4 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
}

.cart-item-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.item-subtotal {
    font-weight: 600;
    color: #ff6b35 !important;
    margin-top: 0.25rem !important;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cart-item-controls button {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-controls button:hover {
    background: #f8f9fa;
}

.remove-btn {
    background: #dc3545 !important;
    color: white !important;
    border-color: #dc3545 !important;
}

.remove-btn:hover {
    background: #c82333 !important;
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', cartItemCSS);