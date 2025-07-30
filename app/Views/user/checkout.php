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
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        .checkout-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        .book-img-small {
            width: 60px;
            height: 75px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        .payment-method:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .payment-method.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }
        .payment-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .summary-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 25px;
            position: sticky;
            top: 20px;
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
                    <h1 class="mb-0"><i class="fas fa-credit-card me-3"></i>Checkout</h1>
                    <p class="mb-0 mt-2">Selesaikan pesanan Anda</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= base_url('user/cart') ?>" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="checkout-card">
                    <h5 class="mb-4"><i class="fas fa-list me-2"></i>Detail Pesanan (<?= $total_items ?> item)</h5>
                    
                    <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex align-items-center py-3 border-bottom">
                            <img src="<?= !empty($item['cover_image']) ? base_url('assets/images/covers/' . $item['cover_image']) : 'https://via.placeholder.com/60x75/667eea/ffffff?text=No+Image' ?>" 
                                 alt="<?= esc($item['title']) ?>" class="book-img-small me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= esc($item['title']) ?></h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i><?= esc($item['author']) ?>
                                </p>
                                <?php if (!empty($item['isbn'])): ?>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-barcode me-1"></i>ISBN: <?= esc($item['isbn']) ?>
                                    </p>
                                <?php endif; ?>
                                <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></h6>
                                <small class="text-muted">@ Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Payment Methods -->
                <div class="checkout-card">
                    <h5 class="mb-4"><i class="fas fa-wallet me-2"></i>Pilih Metode Pembayaran</h5>
                    
                    <?php if (!empty($payment_methods)): ?>
                        <?php 
                        $banks = array_filter($payment_methods, function($pm) { return $pm['type'] === 'bank'; });
                        $ewallets = array_filter($payment_methods, function($pm) { return $pm['type'] === 'ewallet'; });
                        ?>
                        
                        <?php if (!empty($banks)): ?>
                            <h6 class="text-muted mb-3">Bank Transfer</h6>
                            <?php foreach ($banks as $method): ?>
                                <div class="payment-method" data-payment="<?= $method['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="payment-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1"><?= esc($method['name']) ?></h6>
                                            <p class="text-muted mb-0"><?= esc($method['account_number']) ?> - <?= esc($method['account_name']) ?></p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-check-circle text-success d-none"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if (!empty($ewallets)): ?>
                            <h6 class="text-muted mb-3 mt-4">E-Wallet</h6>
                            <?php foreach ($ewallets as $method): ?>
                                <div class="payment-method" data-payment="<?= $method['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="payment-icon" style="background: linear-gradient(135deg, #ff6b6b, #ffa500);">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1"><?= esc($method['name']) ?></h6>
                                            <p class="text-muted mb-0"><?= esc($method['account_number']) ?> - <?= esc($method['account_name']) ?></p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-check-circle text-success d-none"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>Metode pembayaran tidak tersedia</h5>
                            <p class="text-muted">Silakan hubungi admin untuk informasi lebih lanjut</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="mb-4"><i class="fas fa-receipt me-2"></i>Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal (<?= $total_items ?> item):</span>
                        <span>Rp <?= number_format($total_price, 0, ',', '.') ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Ongkos Kirim:</span>
                        <span class="text-success">GRATIS</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Total Pembayaran:</h5>
                        <h5 class="text-primary">Rp <?= number_format($total_price, 0, ',', '.') ?></h5>
                    </div>
                    
                    <button type="button" class="btn btn-custom w-100 mb-3" id="processBtn" disabled onclick="processCheckout()">
                        <i class="fas fa-arrow-right me-2"></i>Lanjut ke Pembayaran
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
        let selectedPaymentMethod = null;

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove selection from all methods
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                    m.querySelector('.fa-check-circle').classList.add('d-none');
                });
                
                // Add selection to clicked method
                this.classList.add('selected');
                this.querySelector('.fa-check-circle').classList.remove('d-none');
                
                // Set selected payment method
                selectedPaymentMethod = this.getAttribute('data-payment');
                
                // Enable process button
                document.getElementById('processBtn').disabled = false;
            });
        });

        // Process checkout function
        function processCheckout() {
            if (!selectedPaymentMethod) {
                showAlert('danger', 'Silakan pilih metode pembayaran terlebih dahulu');
                return false;
            }

            // Show loading state
            const processBtn = document.getElementById('processBtn');
            const originalText = processBtn.innerHTML;
            processBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            processBtn.disabled = true;

            // Create a form and submit it to avoid URL parameter issues
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '<?= base_url('user/processCheckout') ?>';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payment_method';
            input.value = selectedPaymentMethod;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

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
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 4000);
        }

        // Show flash messages if any
        <?php if (session()->getFlashdata('error')): ?>
            setTimeout(() => {
                showAlert('danger', '<?= addslashes(session()->getFlashdata('error')) ?>');
            }, 500);
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            setTimeout(() => {
                showAlert('success', '<?= addslashes(session()->getFlashdata('success')) ?>');
            }, 500);
        <?php endif; ?>
    </script>
</body>
</html>