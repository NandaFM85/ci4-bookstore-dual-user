<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Toko Buku</title>
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
        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .order-card:hover {
            transform: translateY(-2px);
        }
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending_payment {
            background: #fff3cd;
            color: #856404;
        }
        .status-pending_verification {
            background: #cff4fc;
            color: #055160;
        }
        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-on_shipping {
            background: #d4edda;
            color: #155724;
        }
        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .book-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .book-item:last-child {
            border-bottom: none;
        }
        .book-img {
            width: 60px;
            height: 75px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .tracking-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Invoice Styles */
        .invoice-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-logo {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .invoice-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .invoice-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }
        .invoice-total {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        .invoice-book-img {
            width: 40px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-container, .invoice-container * {
                visibility: visible;
            }
            .invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
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

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="fas fa-list me-2"></i>Pesanan Saya
                </h2>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag fa-5x mb-4 text-muted"></i>
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                <a href="/user/catalog" class="btn btn-custom">
                    <i class="fas fa-shopping-cart me-2"></i>Mulai Berbelanja
                </a>
            </div>
        <?php else: ?>
            <!-- Order Cards -->
            <?php foreach ($orders as $order): ?>
                <div class="card order-card" data-order-id="<?= $order['id'] ?>">
                    <div class="order-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-1">
                                    <i class="fas fa-receipt me-2"></i><?= $order['order_number'] ?>
                                </h5>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d M Y, H:i', strtotime($order['created_at'])) ?> WIB
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?php 
                                        $statusMap = [
                                            'pending_payment' => 'Menunggu Pembayaran',
                                            'pending_verification' => 'Menunggu Verifikasi',
                                            'approved' => 'Disetujui',
                                            'on_shipping' => 'Sedang Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                        echo $statusMap[$order['status']] ?? ucfirst($order['status']);
                                    ?>
                                </span>
                                <div class="mt-2">
                                    <strong class="fs-5">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Order Items -->
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="book-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="<?= base_url('assets/images/covers/' . ($item['cover_image'] ?? 'default-book.jpg')) ?>" 
                                             alt="<?= esc($item['title']) ?>" class="book-img">
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1 fw-bold"><?= esc($item['title']) ?></h6>
                                        <p class="text-muted mb-1">
                                            <small><i class="fas fa-user me-1"></i><?= esc($item['author']) ?></small>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <small>Qty: <strong><?= $item['quantity'] ?></strong> x Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                                        </p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="fw-bold fs-6">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Tracking Info (for shipping orders) -->
                        <?php if ($order['status'] == 'on_shipping'): ?>
                            <div class="tracking-info mx-3 mb-3">
                                <h6 class="mb-2"><i class="fas fa-truck me-2 text-primary"></i>Informasi Pengiriman</h6>
                                <p class="mb-1"><strong>Nomor Resi:</strong> <span class="text-primary">JNE<?= str_pad($order['id'], 9, '0', STR_PAD_LEFT) ?></span></p>
                                <p class="mb-0"><strong>Catatan:</strong> Paket sedang dalam perjalanan ke alamat tujuan</p>
                            </div>
                        <?php endif; ?>

                        <!-- Order Actions -->
                        <div class="p-3 bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-box me-1"></i>
                                        Total <?= $order['total_items'] ?> item
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <!-- Show different buttons based on order status -->
                                    <?php if ($order['status'] == 'on_shipping'): ?>
                                        <button class="btn btn-success btn-sm me-2" onclick="confirmOrder(<?= $order['id'] ?>)">
                                            <i class="fas fa-check me-1"></i>Terima Pesanan
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($order['status'] == 'pending_payment'): ?>
                                        <a href="/user/payment/<?= $order['id'] ?>" class="btn btn-warning btn-sm me-2">
                                            <i class="fas fa-credit-card me-1"></i>Bayar Sekarang
                                        </a>
                                    <?php endif; ?>

                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm" onclick="showInvoice(<?= $order['id'] ?>)">
                                            <i class="fas fa-file-invoice me-1"></i>Invoice
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= base_url('user/printInvoice/' . $order['id']) ?>" target="_blank"">
                                                <i class="fas fa-print me-2"></i>Print Invoice
                                            </a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('user/downloadInvoice/' . $order['id']) ?>">
                                                <i class="fas fa-download me-2"></i>Download PDF
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-invoice me-2"></i>Invoice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="invoiceContent">
                    <!-- Invoice content will be loaded here -->
                    <div class="text-center p-5">
                        <div class="loading-spinner mx-auto mb-3"></div>
                        <p>Memuat invoice...</p>
                    </div>
                </div>
                <div class="modal-footer no-print">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printInvoiceFromModal()">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <button type="button" class="btn btn-info" onclick="openInvoiceTab()">
                        <i class="fas fa-external-link-alt me-2"></i>Buka di Tab Baru
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadInvoiceFromModal()">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="alertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i id="toastIcon" class="fas fa-info-circle me-2"></i>
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastBody">
                <!-- Toast message will be inserted here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        let currentOrderId = null;
        let currentInvoiceData = null;

        // Show toast notification
        function showToast(type, title, message) {
            const toast = document.getElementById('alertToast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastBody = document.getElementById('toastBody');
            
            const types = {
                'success': { class: 'text-success', icon: 'fa-check-circle' },
                'error': { class: 'text-danger', icon: 'fa-exclamation-triangle' },
                'info': { class: 'text-info', icon: 'fa-info-circle' },
                'warning': { class: 'text-warning', icon: 'fa-exclamation-triangle' }
            };
            
            const config = types[type] || types['info'];
            
            toastIcon.className = `fas ${config.icon} me-2 ${config.class}`;
            toastTitle.textContent = title;
            toastBody.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Confirm order completion
        function confirmOrder(orderId) {
            if (confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?')) {
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                button.disabled = true;

                // Make AJAX request to confirm order
                fetch(`/user/confirm-order?order_id=${orderId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Berhasil', data.message);
                        // Update status badge and buttons
                        const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
                        const statusBadge = orderCard.querySelector('.status-badge');
                        statusBadge.className = 'status-badge status-completed';
                        statusBadge.textContent = 'Selesai';
                        
                        // Update action buttons
                        const actionDiv = orderCard.querySelector('.bg-light .col-md-6:last-child');
                        actionDiv.innerHTML = `
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm" onclick="showInvoice(${orderId})">
                                    <i class="fas fa-file-invoice me-1"></i>Invoice
                                </button>
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="printInvoiceTab(${orderId})">
                                        <i class="fas fa-print me-2"></i>Print Invoice
                                    </a></li>
                                    <li><a class="dropdown-item" href="/user/download-invoice/${orderId}">
                                        <i class="fas fa-download me-2"></i>Download PDF
                                    </a></li>
                                </ul>
                            </div>
                        `;
                    } else {
                        showToast('error', 'Error', data.message);
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'Terjadi kesalahan saat memproses pesanan');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }

        // Show invoice in modal
        function showInvoice(orderId) {
            currentOrderId = orderId;
            const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            
            // Reset content
            const invoiceContent = document.getElementById('invoiceContent');
            invoiceContent.innerHTML = `
                <div class="text-center p-5">
                    <div class="loading-spinner mx-auto mb-3"></div>
                    <p>Memuat invoice...</p>
                </div>
            `;
            
            modal.show();
            
            // Fetch invoice data
            fetch(`/user/get-invoice?order_id=${orderId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentInvoiceData = data.data;
                    renderInvoice(data.data);
                } else {
                    invoiceContent.innerHTML = `
                        <div class="text-center p-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>Error</h5>
                            <p>${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                invoiceContent.innerHTML = `
                    <div class="text-center p-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5>Error</h5>
                        <p>Terjadi kesalahan saat memuat invoice</p>
                    </div>
                `;
            });
        }

        // Render invoice content
        function renderInvoice(data) {
            const { order, company } = data;
            const invoiceContent = document.getElementById('invoiceContent');
            
            invoiceContent.innerHTML = `
                <div class="invoice-container">
                    <!-- Invoice Header -->
                    <div class="invoice-header">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-logo">${company.name}</div>
                                <p class="mb-1">${company.address}</p>
                                <p class="mb-1">Telp: ${company.phone}</p>
                                <p class="mb-1">Email: ${company.email}</p>
                                <p class="mb-0">Website: ${company.website}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h2 class="mb-3">INVOICE</h2>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="text-end"><strong>No. Invoice:</strong></td>
                                        <td class="text-end">${order.order_number}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Tanggal:</strong></td>
                                        <td class="text-end">${order.formatted_created_date}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Status:</strong></td>
                                        <td class="text-end"><span class="badge bg-primary">${order.status_text}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Tagihan Kepada:</h5>
                            <address>
                                <strong>${order.fullname || order.username}</strong><br>
                                ${order.email}<br>
                                ${order.address || 'Alamat belum diisi'}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Metode Pembayaran:</h5>
                            <p><strong>${order.payment_method_name}</strong></p>
                            ${order.account_info ? `<p class="text-muted small">${order.account_info}</p>` : ''}
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>ISBN</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${order.items.map((item, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        <img src="/assets/images/covers/${item.cover_image || 'default-book.jpg'}" 
                                             alt="${item.title}" class="invoice-book-img">
                                    </td>
                                    <td>${item.title}</td>
                                    <td>${item.author}</td>
                                    <td>${item.isbn || '-'}</td>
                                    <td class="text-end">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                                    <td class="text-center">${item.quantity}</td>
                                    <td class="text-end">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</td>
                                </tr>
                            `).join('')}
                            <tr class="invoice-total">
                                <td colspan="7" class="text-end"><strong>TOTAL</strong></td>
                                <td class="text-end"><strong>Rp ${Number(order.total_amount).toLocaleString('id-ID')}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Footer -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6>Catatan:</h6>
                            <p class="text-muted small">
                                Terima kasih atas pembelian Anda. Jika ada pertanyaan mengenai invoice ini, 
                                silakan hubungi customer service kami.
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="small text-muted">
                                Invoice ini dicetak pada: ${order.formatted_print_date}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }

        // Print invoice from modal
        function printInvoiceFromModal() {
            window.print();
        }

        // Open invoice in new tab for printing
        function printInvoiceTab(orderId) {
            if (!currentInvoiceData) {
                // Fetch data first if not available
                fetch(`/user/get-invoice?order_id=${orderId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        openInvoiceInNewTab(data.data);
                    } else {
                        showToast('error', 'Error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'Terjadi kesalahan saat memuat invoice');
                });
            } else {
                openInvoiceInNewTab(currentInvoiceData);
            }
        }

        // Open invoice tab
        function openInvoiceTab() {
            if (currentInvoiceData) {
                openInvoiceInNewTab(currentInvoiceData);
            } else {
                showToast('warning', 'Peringatan', 'Data invoice belum dimuat');
            }
        }

        // Open invoice in new tab
        function openInvoiceInNewTab(data) {
            const { order, company } = data;
            const newWindow = window.open('', '_blank');
            
            newWindow.document.write(\`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice \${order.order_number}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .invoice-container { max-width: 800px; margin: 0 auto; }
                        .invoice-header { border-bottom: 2px solid #667eea; padding-bottom: 20px; margin-bottom: 30px; }
                        .invoice-logo { font-size: 28px; font-weight: bold; color: #667eea; }
                        .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        .invoice-table th, .invoice-table td { border: 1px solid #dee2e6; padding: 12px; }
                        .invoice-table th { background-color: #f8f9fa; }
                        .invoice-total { background-color: #667eea; color: white; font-weight: bold; }
                        .invoice-book-img { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-container">
                        <!-- Invoice Header -->
                        <div class="invoice-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-logo">\${company.name}</div>
                                    <p class="mb-1">\${company.address}</p>
                                    <p class="mb-1">Telp: \${company.phone}</p>
                                    <p class="mb-1">Email: \${company.email}</p>
                                    <p class="mb-0">Website: \${company.website}</p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h2 class="mb-3">INVOICE</h2>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="text-end"><strong>No. Invoice:</strong></td>
                                            <td class="text-end">\${order.order_number}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end"><strong>Tanggal:</strong></td>
                                            <td class="text-end">\${order.formatted_created_date}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end"><strong>Status:</strong></td>
                                            <td class="text-end"><span class="badge bg-primary">\${order.status_text}</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Tagihan Kepada:</h5>
                                <address>
                                    <strong>\${order.fullname || order.username}</strong><br>
                                    \${order.email}<br>
                                    \${order.address || 'Alamat belum diisi'}
                                </address>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Metode Pembayaran:</h5>
                                <p><strong>\${order.payment_method_name}</strong></p>
                                \${order.account_info ? \`<p class="text-muted small">\${order.account_info}</p>\` : ''}
                            </div>
                        </div>

                        <!-- Items Table -->
                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>ISBN</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                \${order.items.map((item, index) => \`
                                    <tr>
                                        <td>\${index + 1}</td>
                                        <td>
                                            <img src="/assets/images/covers/\${item.cover_image || 'default-book.jpg'}" 
                                                 alt="\${item.title}" class="invoice-book-img">
                                        </td>
                                        <td>\${item.title}</td>
                                        <td>\${item.author}</td>
                                        <td>\${item.isbn || '-'}</td>
                                        <td class="text-end">Rp \${Number(item.price).toLocaleString('id-ID')}</td>
                                        <td class="text-center">\${item.quantity}</td>
                                        <td class="text-end">Rp \${(item.price * item.quantity).toLocaleString('id-ID')}</td>
                                    </tr>
                                \`).join('')}
                                <tr class="invoice-total">
                                    <td colspan="7" class="text-end"><strong>TOTAL</strong></td>
                                    <td class="text-end"><strong>Rp \${Number(order.total_amount).toLocaleString('id-ID')}</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Footer -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Catatan:</h6>
                                <p class="text-muted small">
                                    Terima kasih atas pembelian Anda. Jika ada pertanyaan mengenai invoice ini, 
                                    silakan hubungi customer service kami.
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="small text-muted">
                                    Invoice ini dicetak pada: \${order.formatted_print_date}
                                </p>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class="text-center mt-4 no-print">
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Print Invoice
                            </button>
                            <button class="btn btn-secondary ms-2" onclick="window.close()">
                                <i class="fas fa-times me-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                    
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"><\/script>
                </body>
                </html>
            \`);
            
            newWindow.document.close();
        }

        // Download invoice as PDF
        function downloadInvoiceFromModal() {
            if (!currentInvoiceData) {
                showToast('warning', 'Peringatan', 'Data invoice belum dimuat');
                return;
            }

            const { order } = currentInvoiceData;
            
            // Show loading
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating PDF...';
            button.disabled = true;

            // Use html2canvas and jsPDF to generate PDF
            const invoiceElement = document.querySelector('.invoice-container');
            
            html2canvas(invoiceElement, {
                scale: 2,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF.jsPDF('p', 'mm', 'a4');
                
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 0;
                
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                pdf.save(`Invoice_${order.order_number}.pdf`);
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                showToast('success', 'Berhasil', 'Invoice berhasil didownload');
            }).catch(error => {
                console.error('Error generating PDF:', error);
                showToast('error', 'Error', 'Gagal membuat PDF');
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Check for flash messages
        document.addEventListener('DOMContentLoaded', function() {
            // Check for success message
            <?php if (session()->getFlashdata('success')): ?>
                showToast('success', 'Berhasil', '<?= session()->getFlashdata('success') ?>');
            <?php endif; ?>

            // Check for error message
            <?php if (session()->getFlashdata('error')): ?>
                showToast('error', 'Error', '<?= session()->getFlashdata('error') ?>');
            <?php endif; ?>

            // Check for info message
            <?php if (session()->getFlashdata('info')): ?>
                showToast('info', 'Info', '<?= session()->getFlashdata('info') ?>');
            <?php endif; ?>
        });

        // Prevent dropdown from closing when clicking inside
        document.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown-menu')) {
                e.stopPropagation();
            }
        });

        // Auto-refresh order status (optional)
        setInterval(function() {
            // Only refresh if user is actively viewing the page
            if (document.visibilityState === 'visible') {
                const pendingOrders = document.querySelectorAll('[data-order-id]');
                pendingOrders.forEach(orderCard => {
                    const statusBadge = orderCard.querySelector('.status-badge');
                    if (statusBadge && statusBadge.classList.contains('status-pending_verification')) {
                        // Could implement auto-refresh for pending orders
                        // For now, just add a subtle animation to indicate it's being processed
                        statusBadge.style.animation = 'pulse 2s infinite';
                    }
                });
            }
        }, 30000); // Check every 30 seconds

        // Add pulse animation for pending orders
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.7; }
                100% { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>