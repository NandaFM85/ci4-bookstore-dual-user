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
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .page-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
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
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        .btn-custom {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
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
        .filter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .category-btn {
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 8px 16px;
            margin: 2px;
            background: white;
            color: #495057;
            transition: all 0.3s;
        }
        .category-btn:hover, .category-btn.active {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
            color: white;
            border-color: transparent;
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
                    <h1 class="mb-0"><i class="fas fa-book-open me-3"></i>Katalog Buku</h1>
                    <p class="mb-0 mt-2">Temukan buku favorit Anda dari koleksi terlengkap</p>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="<?= base_url('user/catalog') ?>">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari judul buku, penulis..." 
                                   value="<?= esc($search ?? '') ?>">
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <h5 class="mb-3">Filter Kategori</h5>
                    <div class="d-flex flex-wrap">
                        <a href="<?= base_url('user/catalog') ?>" 
                           class="btn category-btn <?= empty($selected_category) ? 'active' : '' ?>">
                            Semua
                        </a>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <?php 
                                $categoryName = is_array($cat) ? ($cat['category'] ?? $cat['name'] ?? 'Unknown') : $cat;
                                ?>
                                <a href="<?= base_url('user/catalog?category=' . urlencode($categoryName)) ?>" 
                                   class="btn category-btn <?= ($selected_category ?? '') === $categoryName ? 'active' : '' ?>">
                                    <?= esc($categoryName) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>
                        <?php if ($search ?? false): ?>
                            Hasil pencarian "<?= esc($search) ?>" (<?= count($books) ?> buku)
                        <?php elseif ($selected_category ?? false): ?>
                            Kategori: <?= esc($selected_category) ?> (<?= count($books) ?> buku)
                        <?php else: ?>
                            Semua Buku (<?= count($books) ?> buku)
                        <?php endif; ?>
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="book-card">
                            <img src="<?= !empty($book['cover_image']) && !is_array($book['cover_image']) ? base_url('assets/images/covers/' . $book['cover_image']) : 'https://via.placeholder.com/200x250/667eea/ffffff?text=No+Image' ?>" 
                                 alt="<?= esc(is_array($book['title']) ? implode(' ', $book['title']) : $book['title']) ?>" class="book-img">
                            <div class="p-3">
                                <h5 class="mb-2" title="<?= esc(is_array($book['title']) ? implode(' ', $book['title']) : $book['title']) ?>">
                                    <?= strlen(is_array($book['title']) ? implode(' ', $book['title']) : $book['title']) > 25 ? substr(esc(is_array($book['title']) ? implode(' ', $book['title']) : $book['title']), 0, 25) . '...' : esc(is_array($book['title']) ? implode(' ', $book['title']) : $book['title']) ?>
                                </h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i>
                                    <?= strlen(is_array($book['author']) ? implode(', ', $book['author']) : $book['author']) > 20 ? substr(esc(is_array($book['author']) ? implode(', ', $book['author']) : $book['author']), 0, 20) . '...' : esc(is_array($book['author']) ? implode(', ', $book['author']) : $book['author']) ?>
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-tag me-1"></i>
                                    <?= esc(is_array($book['category']) ? implode(', ', $book['category']) : ($book['category'] ?? 'Tidak ada kategori')) ?>
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-boxes me-1"></i>
                                    Stok: <?= is_numeric($book['stock']) ? $book['stock'] : 0 ?>
                                </p>
                                <?php if (!empty($book['description'])): ?>
                                    <?php $description = is_array($book['description']) ? implode(' ', $book['description']) : $book['description']; ?>
                                    <p class="text-muted small mb-2" title="<?= esc($description) ?>">
                                        <?= strlen($description) > 50 ? substr(esc($description), 0, 50) . '...' : esc($description) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary h5 mb-0">
                                        Rp <?= number_format(is_numeric($book['price']) ? $book['price'] : 0, 0, ',', '.') ?>
                                    </span>
                                    <button class="btn btn-sm btn-custom add-to-cart-btn" 
                                            data-book-id="<?= is_numeric($book['id']) ? $book['id'] : 0 ?>"
                                            <?= (is_numeric($book['stock']) ? $book['stock'] : 0) <= 0 ? 'disabled' : '' ?>>
                                        <?php if ((is_numeric($book['stock']) ? $book['stock'] : 0) <= 0): ?>
                                            <i class="fas fa-times"></i> Habis
                                        <?php else: ?>
                                            <i class="fas fa-cart-plus"></i>
                                        <?php endif; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-5x text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada buku ditemukan</h4>
                        <p class="text-muted">
                            <?php if (($search ?? false) || ($selected_category ?? false)): ?>
                                Coba ubah kata kunci pencarian atau filter kategori
                            <?php else: ?>
                                Belum ada buku tersedia saat ini
                            <?php endif; ?>
                        </p>
                        <?php if (($search ?? false) || ($selected_category ?? false)): ?>
                            <a href="<?= base_url('user/catalog') ?>" class="btn btn-custom">
                                <i class="fas fa-arrow-left me-2"></i>Lihat Semua Buku
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
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
                        this.innerHTML = '<i class="fas fa-check"></i> Ditambahkan';
                        this.classList.add('btn-success');
                        this.classList.remove('btn-custom');
                        
                        // Update cart count
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
                        
                        // Show alert
                        showAlert('success', data.message);
                        
                        // Reset button after 3 seconds
                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-custom');
                            this.disabled = false;
                        }, 3000);
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
    </script>
</body>
</html>