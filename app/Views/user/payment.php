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
        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
        }
        .payment-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .order-number {
            background: rgba(255,255,255,0.2);
            padding: 10px 15px;
            border-radius: 25px;
            display: inline-block;
        }
        .payment-info {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .barcode-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            border: 3px dashed #28a745;
            margin-bottom: 20px;
        }
        .barcode-image {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            background: white;
            padding: 20px;
            margin: 20px 0;
        }
        .scan-button {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .scan-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .btn-custom {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
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
        .copy-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .copy-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .breadcrumb {
            background: none;
            padding: 0;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: #6c757d;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .payment-status {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success-status {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .modal-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            color: white;
            border-bottom: none;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .modal-body {
            padding: 2rem;
        }
        .confirmation-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        .amount-highlight {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD  100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 15px 0;
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

    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/orders') ?>">Pesanan Saya</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Payment Header -->
                <div class="card payment-card mb-4">
                    <div class="card-header payment-header text-center p-4">
                        <h3 class="mb-3"><i class="fas fa-qrcode me-2"></i>Pembayaran Digital</h3>
                        <div class="order-number">
                            <i class="fas fa-receipt me-2"></i>Order: <?= $order['order_number'] ?>
                        </div>
                        <p class="mt-3 mb-0">Total Pembayaran: <strong>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></p>
                    </div>
                </div>

                <!-- Payment Status -->
                <?php if ($order['status'] == 'completed'): ?>
                <div class="payment-status success-status">
                    <h5 class="mb-2"><i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil!</h5>
                    <p class="mb-0">Pesanan Anda telah selesai dan siap untuk diproses.</p>
                </div>
                <?php else: ?>
                <div class="payment-status">
                    <h5 class="mb-2"><i class="fas fa-clock me-2"></i>Menunggu Pembayaran</h5>
                    <p class="mb-0">Silakan scan barcode di bawah untuk menyelesaikan pembayaran.</p>
                </div>
                <?php endif; ?>

                <!-- Payment Instructions -->
                <div class="payment-info">
                    <h5 class="mb-3">
                        <i class="fas fa-<?= $payment_method['type'] == 'bank' ? 'university' : ($payment_method['type'] == 'ewallet' ? 'wallet' : 'coins') ?> me-2"></i>
                        <?= $payment_method['name'] ?>
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nomor Rekening/Akun:</strong></p>
                            <div class="d-flex align-items-center">
                                <span class="me-2"><?= $payment_method['account_number'] ?></span>
                                <button class="copy-btn" onclick="copyToClipboard('<?= $payment_method['account_number'] ?>')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Atas Nama:</strong></p>
                            <p class="mb-0"><?= $payment_method['account_name'] ?></p>
                        </div>
                    </div>
                    <?php if (!empty($payment_method['description'])): ?>
                    <div class="mt-3">
                        <small><i class="fas fa-info-circle me-1"></i><?= $payment_method['description'] ?></small>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Barcode Payment Section -->
                <?php if ($order['status'] != 'completed'): ?>
                <div class="card payment-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Pembayaran dengan Barcode</h5>
                    </div>
                    <div class="card-body">
                        <div class="barcode-section">
                            <h4 class="text-success mb-3">
                                <i class="fas fa-mobile-alt me-2"></i>Scan Barcode untuk Bayar
                            </h4>
                            
                            <!-- Dummy Barcode Image -->
                            <div class="pulse-animation">
                                <img src="<?= base_url('assets/images/barcode/barcode') ?>" 
                                     alt="Payment Barcode" 
                                     class="barcode-image"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDMwMCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMTUwIiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB4PSIxMCIgeT0iMjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjExMCIgZmlsbD0iYmxhY2siLz4KPHJlY3QgeD0iMTgiIHk9IjIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMTAiIGZpbGw9ImJsYWNrIi8+CjxyZWN0IHg9IjI0IiB5PSIyMCIgd2lkdGg9IjYiIGhlaWdodD0iMTEwIiBmaWxsPSJibGFjayIvPgo8cmVjdCB4PSIzNCIgeT0iMjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjExMCIgZmlsbD0iYmxhY2siLz4KPHJlY3QgeD0iNDAiIHk9IjIwIiB3aWR0aD0iNCIgaGVpZ2h0PSIxMTAiIGZpbGw9ImJsYWNrIi8+CjxyZWN0IHg9IjQ4IiB5PSIyMCIgd2lkdGg9IjIiIGhlaWdodD0iMTEwIiBmaWxsPSJibGFjayIvPgo8cmVjdCB4PSI1NCIgeT0iMjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjExMCIgZmlsbD0iYmxhY2siLz4KPHJlY3QgeD0iNjIiIHk9IjIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMTAiIGZpbGw9ImJsYWNrIi8+CjxyZWN0IHg9IjY4IiB5PSIyMCIgd2lkdGg9IjYiIGhlaWdodD0iMTEwIiBmaWxsPSJibGFjayIvPgo8cmVjdCB4PSI3OCIgeT0iMjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjExMCIgZmlsbD0iYmxhY2siLz4KPHJlY3QgeD0iODQiIHk9IjIwIiB3aWR0aD0iNCIgaGVpZ2h0PSIxMTAiIGZpbGw9ImJsYWNrIi8+CjxyZWN0IHg9IjkyIiB5PSIyMCIgd2lkdGg9IjIiIGhlaWdodD0iMTEwIiBmaWxsPSJibGFjayIvPgo8cmVjdCB4PSI5OCIgeT0iMjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjExMCIgZmlsbD0iYmxhY2siLz4KPHRleHQgeD0iMTUwIiB5PSIxNDAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0iYmxhY2siIHRleHQtYW5jaG9yPSJtaWRkbGUiPk9SRDEyMzQ1Njc4OTA8L3RleHQ+Cjwvc3ZnPg=='">
                            </div>
                            
                            <p class="text-muted mt-3 mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Gunakan aplikasi mobile banking atau e-wallet untuk scan barcode ini
                            </p>
                            
                            <!-- Scan Button -->
                            <button type="button" class="btn scan-button" data-bs-toggle="modal" data-bs-target="#paymentConfirmModal">
                                <i class="fas fa-qrcode me-2"></i>Konfirmasi Pembayaran
                            </button>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Pembayaran akan langsung dikonfirmasi setelah scan berhasil
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Pembayaran Berhasil!</strong> Terima kasih atas pembayaran Anda. Pesanan sedang diproses.
                </div>
                <?php endif; ?>

                <!-- Order Items Summary -->
                <div class="card payment-card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detail Pesanan (<?= count($order['items']) ?> Item)</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php foreach ($order['items'] as $index => $item): ?>
                            <div class="d-flex align-items-center py-2 <?= $index < count($order['items']) - 1 ? 'border-bottom' : '' ?>">
                                <img src="<?= !empty($item['cover_image']) ? base_url('assets/images/covers/' . $item['cover_image']) : 'https://via.placeholder.com/50x60/667eea/ffffff?text=No+Image' ?>" 
                                     alt="<?= esc($item['title']) ?>" class="me-3" style="width: 50px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= esc($item['title']) ?></h6>
                                    <small class="text-muted">by <?= esc($item['author']) ?></small><br>
                                    <small class="text-muted">Qty: <?= $item['quantity'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                            <h5 class="mb-0">Total Pembayaran:</h5>
                            <h5 class="mb-0 text-primary">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="<?= base_url('user/orders') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentConfirmModal" tabindex="-1" aria-labelledby="paymentConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentConfirmModalLabel">
                        <i class="fas fa-credit-card me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="confirmation-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4 class="mb-3">Konfirmasi Pembayaran</h4>
                    <p class="mb-3">Apakah Anda yakin telah melakukan pembayaran sebesar:</p>
                    <div class="amount-highlight">
                        Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                    </div>
                    <p class="text-muted mb-4">
                        Pesanan akan langsung dikonfirmasi sebagai selesai setelah Anda mengkonfirmasi pembayaran ini.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Pastikan pembayaran telah berhasil dilakukan sebelum mengkonfirmasi.</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <a href="<?= base_url('user/pay?action=complete&order_id=' . $order['id']) ?>" 
                       class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Ya, Konfirmasi Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show feedback
                const btn = event.target.closest('.copy-btn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = 'rgba(40, 167, 69, 0.2)';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.style.background = 'rgba(255,255,255,0.2)';
                }, 1000);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const btn = event.target.closest('.copy-btn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = 'rgba(40, 167, 69, 0.2)';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.style.background = 'rgba(255,255,255,0.2)';
                }, 1000);
            });
        }

        // Auto refresh if payment is pending (optional)
        <?php if ($order['status'] == 'completed'): ?>
        // You can add auto-refresh logic here if needed
        <?php endif; ?>
    </script>
</body>
</html>