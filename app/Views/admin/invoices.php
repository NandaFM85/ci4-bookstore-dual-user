<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            min-height: calc(100vh - 40px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 15px 20px;
            margin: 5px 15px;
            border-radius: 15px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            transform: translateX(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .main-content {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin: 20px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .stats-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            padding: 25px;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }
        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .table-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .table-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 20px 25px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-success {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 205, 196, 0.4);
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .stats-label {
            font-size: 0.95rem;
            color: #6c757d;
            font-weight: 500;
        }
        .welcome-text {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .role-text {
            font-size: 1rem;
            opacity: 0.9;
        }
        .table {
            border-radius: 15px;
            overflow: hidden;
        }
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        .badge {
            padding: 8px 12px;
            font-size: 0.8rem;
            border-radius: 10px;
        }
        .alert {
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
        }
        .alert-success {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: white;
        }
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-4 text-center">
                        <i class="fas fa-book fa-3x text-white mb-3"></i>
                        <h4 class="text-white">Admin Panel</h4>
                    </div>
                    <nav class="nav flex-column px-3">
                        <a class="nav-link" href="<?= base_url('dashboard/admin') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="<?= base_url('admin/books') ?>">
                            <i class="fas fa-book me-2"></i>Kelola Buku
                        </a>
                        <a class="nav-link" href="<?= base_url('admin/users') ?>">
                            <i class="fas fa-users me-2"></i>Pengguna
                        </a>
                        <a class="nav-link active" href="<?= base_url('admin/invoices') ?>">
                            <i class="fas fa-file-invoice me-2"></i>Invoice
                        </a>
                        <hr class="text-white-50 mx-3">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<?= base_url('assets/images/profiles/' . $user['profile_image']) ?>" 
                                     alt="Profile" class="profile-img" 
                                     onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                            </div>
                            <div class="col">
                                <h3 class="welcome-text">
                                    <i class="fas fa-file-invoice me-3"></i>Kelola Invoice
                                </h3>
                                <p class="mb-2 role-text">
                                    Selamat datang, <strong><?= $user['fullname'] ?: $user['username'] ?>!</strong>
                                </p>
                                <p class="mb-0 role-text">
                                    <i class="fas fa-crown me-2"></i>Administrator
                                    <span class="ms-3">
                                        <i class="fas fa-envelope me-1"></i><?= $user['email'] ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('admin/export-invoices') ?>" class="btn btn-success">
                                    <i class="fas fa-file-excel me-2"></i>Export Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-primary"><?= number_format($stats['total_invoices']) ?></h4>
                                        <small class="stats-label">Total Invoice</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-success">Rp <?= number_format($stats['total_revenue'] / 1000000, 1) ?>M</h4>
                                        <small class="stats-label">Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #36b9cc, #28a745);">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-info"><?= number_format($stats['completed_orders']) ?></h4>
                                        <small class="stats-label">Completed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #f6c23e, #ffa500);">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-warning"><?= number_format($stats['pending_orders']) ?></h4>
                                        <small class="stats-label">Pending</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Invoice Table -->
                    <div class="table-card">
                        <div class="card-header">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-table me-2"></i>Daftar Invoice
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="invoiceTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama User</th>
                                            <th>Order Number</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($orders)): ?>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><strong><?= esc($order['id']) ?></strong></td>
                                                    <td>
                                                        <div class="fw-bold"><?= esc($order['fullname'] ?: $order['username']) ?></div>
                                                        <small class="text-muted">@<?= esc($order['username']) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info"><?= esc($order['order_number']) ?></span>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $statusClass = '';
                                                        switch ($order['status']) {
                                                            case 'pending_payment':
                                                                $statusClass = 'bg-warning';
                                                                break;
                                                            case 'pending_verification':
                                                                $statusClass = 'bg-info';
                                                                break;
                                                            case 'approved':
                                                                $statusClass = 'bg-primary';
                                                                break;
                                                            case 'on_shipping':
                                                                $statusClass = 'bg-secondary';
                                                                break;
                                                            case 'completed':
                                                                $statusClass = 'bg-success';
                                                                break;
                                                            case 'cancelled':
                                                                $statusClass = 'bg-danger';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-light text-dark';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $statusClass ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                        <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="fas fa-inbox fa-3x mb-3 d-block text-muted opacity-50"></i>
                                                    <h5>Belum ada invoice yang tersedia</h5>
                                                    <p class="mb-0">Invoice akan muncul setelah ada pesanan dari pelanggan</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#invoiceTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "order": [[ 5, "desc" ]], // Sort by created_at descending
                "pageLength": 25,
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                "columnDefs": [
                    { "width": "8%", "targets": 0 },
                    { "width": "20%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "12%", "targets": 4 },
                    { "width": "15%", "targets": 5 }
                ]
            });

            // Add hover effects to stats cards
            $('.stats-card').hover(
                function() {
                    $(this).css('transform', 'translateY(-5px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );

            // Animate numbers on page load
            window.addEventListener('load', function() {
                const numbers = document.querySelectorAll('.stats-number');
                numbers.forEach(number => {
                    const text = number.textContent;
                    const numericValue = parseInt(text.replace(/[^\d]/g, ''));
                    if (numericValue > 0) {
                        let currentValue = 0;
                        const increment = numericValue / 50;
                        
                        const timer = setInterval(() => {
                            currentValue += increment;
                            if (currentValue >= numericValue) {
                                number.textContent = text;
                                clearInterval(timer);
                            } else {
                                if (text.includes('Rp')) {
                                    number.textContent = 'Rp ' + Math.floor(currentValue / 1000000 * 10) / 10 + 'M';
                                } else {
                                    number.textContent = Math.floor(currentValue).toLocaleString();
                                }
                            }
                        }, 30);
                    }
                });
            });
        });
    </script>
</body>
</html>