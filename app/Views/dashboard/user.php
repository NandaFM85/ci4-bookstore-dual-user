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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
            border-left: 4px solid #667eea;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin: 0 auto 20px;
        }
        .book-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            overflow: hidden;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .book-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            height: 100%;
        }
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin: 0 auto 15px;
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

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3">Selamat datang, <?= $user['fullname'] ?: $user['username'] ?>!</h1>
                    <p class="lead mb-4">Temukan koleksi buku terbaik dan nikmati pengalaman berbelanja yang menyenangkan.</p>
                    <a href="<?= base_url('user/catalog') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Jelajahi Katalog
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <img src="<?= base_url('assets/images/profiles/' . ($user['profile_image'] ?? 'default-profile.jpg')) ?>" 
                         alt="Profile" class="profile-img"
                         onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                    <h4 class="mt-3"><?= $user['fullname'] ?: $user['username'] ?></h4>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i><?= $user['email'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h5>Keranjang</h5>
                    <h3 class="text-primary" id="cart-count-display"><?= $cart_count ?></h3>
                    <p class="text-muted">Item dalam keranjang</p>
                    <a href="<?= base_url('user/cart') ?>" class="btn btn-sm btn-custom">Lihat Keranjang</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5>Total Buku</h5>
                    <h3 class="text-success"><?= count($recent_books) ?>+</h3>
                    <p class="text-muted">Buku tersedia</p>
                    <a href="<?= base_url('user/catalog') ?>" class="btn btn-sm btn-custom">Jelajahi</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #ff6b6b, #ffa500);">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5>Wishlist</h5>
                    <h3 class="text-warning">0</h3>
                    <p class="text-muted">Buku favorit</p>
                    <button class="btn btn-sm btn-custom" disabled>Segera Hadir</button>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ff6b6b, #ffa500);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h4>Katalog Lengkap</h4>
                    <p class="text-muted">Ribuan buku dari berbagai genre dan penulis terkenal dunia.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4>Pengiriman Cepat</h4>
                    <p class="text-muted">Dapatkan buku favorit Anda dengan pengiriman yang cepat dan aman.</p> 
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Toko Buku Online Terlengkap</h4>
                    <p class="text-muted">Cari Buku Yang Anda Suka ayo jelajahi sekarang</p>
                </div>
            </div>
        </div>

        <!-- Recent Books Section -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-fire text-danger me-2"></i>Buku Terbaru</h2>
                <a href="<?= base_url('user/catalog') ?>" class="btn btn-custom">
                    <i class="fas fa-eye me-2"></i>Lihat Semua
                </a>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <?php if (isset($recent_books) && !empty($recent_books)): ?>
                <?php foreach ($recent_books as $book): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="book-card">
                            <img src="<?= !empty($book['cover_image']) ? base_url('assets/images/covers/' . $book['cover_image']) : 'https://via.placeholder.com/200x250/667eea/ffffff?text=No+Image' ?>" 
                                 alt="<?= esc($book['title']) ?>" class="book-img">
                            <div class="p-3">
                                <h5 class="mb-2" title="<?= esc($book['title']) ?>">
                                    <?= strlen($book['title']) > 20 ? substr(esc($book['title']), 0, 20) . '...' : esc($book['title']) ?>
                                </h5>
                                <p class="text-muted mb-2"><i class="fas fa-user me-1"></i><?= esc($book['author']) ?></p>
                                <p class="text-muted small mb-2"><i class="fas fa-boxes me-1"></i>Stok: <?= $book['stock'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">Rp <?= number_format($book['price'], 0, ',', '.') ?></span>
                                    <button class="btn btn-sm btn-custom add-to-cart-btn" 
                                            data-book-id="<?= $book['id'] ?>"
                                            <?= $book['stock'] <= 0 ? 'disabled' : '' ?>>
                                        <?php if ($book['stock'] <= 0): ?>
                                            <i class="fas fa-times"></i> Habis
                                        <?php else: ?>
                                            <i class="fas fa-cart-plus"></i>
                                        <?php endif; ?>
                                    </button>
                                </div>
                                <?php if ($book['stock'] <= 0): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-danger">Stok Habis</span>
                                    </div>
                                <?php elseif ($book['stock'] <= 5): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-warning">Stok Terbatas</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-5x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada buku tersedia</h4>
                        <p class="text-muted">Silakan cek kembali nanti atau hubungi admin</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <h3 class="mb-4"><i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat</h3>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="<?= base_url('user/catalog') ?>" class="text-decoration-none">
                    <div class="feature-card text-center">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5>Cari Buku</h5>
                        <p class="text-muted small">Temukan buku yang Anda cari</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="<?= base_url('user/cart') ?>" class="text-decoration-none">
                    <div class="feature-card text-center">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5>Keranjang</h5>
                        <p class="text-muted small">Lihat item dalam keranjang</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="<?= base_url('user/profile') ?>" class="text-decoration-none">
                    <div class="feature-card text-center">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #ff6b6b, #ffa500);">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5>Profil</h5>
                        <p class="text-muted small">Edit informasi profil</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book me-2"></i>Toko Buku Online</h5>
                    <p class="text-muted">Platform terbaik untuk menemukan dan membeli buku favorit Anda.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">&copy; 2024 Toko Buku Online. All rights reserved.</p>
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
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        // Add to cart functionality using GET method
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                
                const bookId = this.dataset.bookId;
                const originalHTML = this.innerHTML;
                
                // Show loading
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;

                // Create GET request URL with parameters
                const params = new URLSearchParams({
                    'book_id': bookId,
                    'quantity': 1
                });
                
                const url = `<?= base_url('user/addToCart') ?>?${params.toString()}`;

                // Send GET request
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success
                        this.innerHTML = '<i class="fas fa-check"></i>';
                        this.classList.add('btn-success');
                        this.classList.remove('btn-custom');
                        
                        // Update cart count in navbar
                        const cartBadge = document.getElementById('cart-badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                        } else if (data.cart_count > 0) {
                            // Create badge if it doesn't exist
                            const cartLink = document.querySelector('.nav-link[href*="cart"]');
                            if (cartLink) {
                                const badge = document.createElement('span');
                                badge.className = 'cart-badge';
                                badge.id = 'cart-badge';
                                badge.textContent = data.cart_count;
                                cartLink.appendChild(badge);
                            }
                        }

                        // Update cart count in stats card
                        const cartCountDisplay = document.getElementById('cart-count-display');
                        if (cartCountDisplay) {
                            cartCountDisplay.textContent = data.cart_count;
                        }
                        
                        // Show alert
                        showAlert('success', data.message);
                        
                        // Reset button after 2 seconds
                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-custom');
                            this.disabled = false;
                        }, 2000);
                    } else {
                        // Show error
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                    showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
                });
            });
        });

        // Show welcome message on page load
        window.addEventListener('load', function() {
            setTimeout(() => {
                showAlert('info', `Selamat datang, <?= $user['fullname'] ?: $user['username'] ?>! Selamat berbelanja.`);
            }, 1000);
        });
    </script>
</body>
</html>