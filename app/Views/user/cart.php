<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .page-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        .cart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .cart-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .book-img-small {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .quantity-input {
            width: 70px;
            text-align: center;
            border-radius: 20px;
        }
        .summary-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            position: sticky;
            top: 20px;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff6b6b;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
  <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                <i class="fas fa-book me-2"></i>Toko Buku
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('user/catalog') ?>">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('user/orders') ?>">Pesanan Saya</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
              <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="<?= base_url('user/cart') ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($total_items > 0): ?>
                                <span class="cart-badge" id="cart-badge"><?= $total_items ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?= base_url('assets/images/profiles/' . ($user['profile_image'] ?? 'default-profile.jpg')) ?>" 
                                 alt="Profile" class="rounded-circle me-2" width="32" height="32"
                                 onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                            <?= $user['fullname'] ?: $user['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('user/profile') ?>"><i class="fas fa-user me-2"></i>Edit Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0"><i class="fas fa-shopping-cart me-3"></i>Keranjang Belanja</h1>
                    <p class="mb-0 mt-2">Kelola items dalam keranjang Anda</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= base_url('user/catalog') ?>" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($cart_items)): ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-card">
                        <h5 class="mb-4">Item dalam Keranjang (<?= $total_items ?> item)</h5>
                        
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item" data-book-id="<?= $item['book_id'] ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="<?= !empty($item['cover_image']) ? base_url('assets/images/covers/' . $item['cover_image']) : 'https://via.placeholder.com/80x100/667eea/ffffff?text=No+Image' ?>" 
                                             alt="<?= esc($item['title']) ?>" class="book-img-small">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><?= esc($item['title']) ?></h6>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-user me-1"></i><?= esc($item['author']) ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-boxes me-1"></i>Stok tersedia: <?= $item['stock'] ?>
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                    data-action="decrease" data-book-id="<?= $item['book_id'] ?>">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control quantity-input mx-2" 
                                                   value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>"
                                                   data-book-id="<?= $item['book_id'] ?>">
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                    data-action="increase" data-book-id="<?= $item['book_id'] ?>"
                                                    <?= $item['quantity'] >= $item['stock'] ? 'disabled' : '' ?>>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <h6 class="mb-0 item-price" data-price="<?= $item['price'] ?>">Rp <?= number_format($item['price'], 0, ',', '.') ?></h6>
                                        <small class="text-muted">per item</small>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <h5 class="mb-2 item-total">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></h5>
                                        <button class="btn btn-sm btn-outline-danger remove-item-btn" 
                                                data-book-id="<?= $item['book_id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h5 class="mb-4">Ringkasan Pesanan</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal (<span id="item-count"><?= $total_items ?></span> item):</span>
                            <span id="subtotal">Rp <?= number_format($total_price, 0, ',', '.') ?></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim:</span>
                            <span class="text-success">GRATIS</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total:</h5>
                            <h5 class="text-primary" id="total-price">Rp <?= number_format($total_price, 0, ',', '.') ?></h5>
                        </div>
                        
                        <a href="<?= base_url('user/checkout') ?>" class="btn btn-custom w-100 mb-3" id="checkout-btn">
                            <i class="fas fa-credit-card me-2"></i>Checkout
                        </a>
                        
                        <button class="btn btn-outline-danger w-100 mb-3" id="clear-cart-btn">
                            <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                        </button>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Pembayaran aman & terpercaya
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty Cart -->
            <div class="row">
                <div class="col-12">
                    <div class="cart-card">
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                            <h3 class="text-muted mb-3">Keranjang Anda Kosong</h3>
                            <p class="text-muted mb-4">Sepertinya Anda belum menambahkan apapun ke keranjang. Mari mulai berbelanja!</p>
                            <a href="<?= base_url('user/catalog') ?>" class="btn btn-custom btn-lg">
                                <i class="fas fa-search me-2"></i>Jelajahi Katalog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book me-2"></i>Toko Buku Online</h5>
                    <p class="text-muted mb-0">Platform terbaik untuk menemukan dan membeli buku favorit Anda.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; 2024 Toko Buku Online. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show alert function
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 4000);
        }

        // Update cart display
        function updateCartDisplay(data) {
            // Update cart badge
            const cartBadge = document.getElementById('cart-badge');
            if (cartBadge) {
                if (data.cart_count > 0) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.style.display = 'flex';
                } else {
                    cartBadge.style.display = 'none';
                }
            }

            // Update summary
            document.getElementById('item-count').textContent = data.cart_count;
            document.getElementById('subtotal').textContent = 'Rp ' + data.total_price;
            document.getElementById('total-price').textContent = 'Rp ' + data.total_price;
        }

        // Make GET request with parameters
        function makeGetRequest(url, params) {
            const urlParams = new URLSearchParams(params);
            const fullUrl = `${url}?${urlParams.toString()}`;
            
            return fetch(fullUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
        }

        // Quantity button handlers
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const bookId = this.dataset.bookId;
                const action = this.dataset.action;
                const quantityInput = document.querySelector(`input[data-book-id="${bookId}"]`);
                let newQuantity = parseInt(quantityInput.value);
                const maxStock = parseInt(quantityInput.getAttribute('max'));

                if (action === 'increase') {
                    if (newQuantity >= maxStock) {
                        showAlert('warning', 'Stok tidak mencukupi');
                        return;
                    }
                    newQuantity++;
                } else if (action === 'decrease') {
                    if (newQuantity <= 1) {
                        return;
                    }
                    newQuantity--;
                }

                updateCartQuantity(bookId, newQuantity);
            });
        });

        // Quantity input change handler
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const bookId = this.dataset.bookId;
                let quantity = parseInt(this.value);
                const maxStock = parseInt(this.getAttribute('max'));

                if (quantity < 1) {
                    quantity = 1;
                    this.value = 1;
                }

                if (quantity > maxStock) {
                    quantity = maxStock;
                    this.value = maxStock;
                    showAlert('warning', 'Quantity melebihi stok yang tersedia');
                }

                updateCartQuantity(bookId, quantity);
            });
        });

        // Update cart quantity function
        function updateCartQuantity(bookId, quantity) {
            const params = {
                'book_id': bookId,
                'quantity': quantity
            };

            makeGetRequest('<?= base_url('user/updateCart') ?>', params)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update item total
                    const cartItem = document.querySelector(`[data-book-id="${bookId}"]`);
                    const priceElement = cartItem.querySelector('.item-price');
                    const price = parseInt(priceElement.dataset.price);
                    const totalElement = cartItem.querySelector('.item-total');
                    
                    totalElement.textContent = 'Rp ' + (price * quantity).toLocaleString('id-ID');
                    
                    // Update quantity input
                    const quantityInput = cartItem.querySelector('.quantity-input');
                    quantityInput.value = quantity;
                    
                    // Update buttons
                    const increaseBtn = cartItem.querySelector('[data-action="increase"]');
                    const maxStock = parseInt(quantityInput.getAttribute('max'));
                    increaseBtn.disabled = quantity >= maxStock;

                    // Update summary
                    updateCartDisplay(data);
                    
                    showAlert('success', 'Keranjang berhasil diperbarui');
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat memperbarui keranjang');
            });
        }

        // Remove item handlers
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const bookId = this.dataset.bookId;
                
                if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                    removeFromCart(bookId);
                }
            });
        });

        // Remove from cart function
        function removeFromCart(bookId) {
            const params = {
                'book_id': bookId
            };

            makeGetRequest('<?= base_url('user/removeFromCart') ?>', params)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove item from display
                    const cartItem = document.querySelector(`[data-book-id="${bookId}"]`);
                    cartItem.remove();
                    
                    // Update summary
                    updateCartDisplay(data);
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload(); // Reload to show empty cart message
                    }
                    
                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat menghapus item');
            });
        }

        // Clear cart handler
        document.getElementById('clear-cart-btn')?.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
                clearCart();
            }
        });

        // Clear cart function
        function clearCart() {
            const params = {};

            makeGetRequest('<?= base_url('user/clearCart') ?>', params)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        location.reload(); // Reload to show empty cart message
                    }, 1000);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat mengosongkan keranjang');
            });
        }
    </script>
</body>
</html>