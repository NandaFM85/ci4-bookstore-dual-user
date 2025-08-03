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
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
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
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
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
        .user-stats-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .user-stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }
        .user-stats-card .card-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
            border: none;
            padding: 20px 25px;
        }
        .recent-activity-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .recent-activity-card .card-header {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
            border: none;
            padding: 20px 25px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD 100%);
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
            background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 205, 196, 0.4);
        }
        
        /* Fixed responsive font size untuk stats */
        .stats-number {
            font-size: clamp(1.2rem, 2.5vw, 1.8rem) !important;
            font-weight: 700;
            margin-bottom: 0;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        .user-detail-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .user-stat-item {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .user-stat-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }
        .user-stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .user-stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .activity-item {
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                padding: 20px;
            }
            .stats-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            .stats-number {
                font-size: 1.3rem !important;
            }
            .stats-label {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-card {
                padding: 15px;
            }
            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            .stats-number {
                font-size: 1.1rem !important;
            }
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
                        <a class="nav-link active" href="<?= base_url('dashboard/admin') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="<?= base_url('admin/books') ?>">
                            <i class="fas fa-book me-2"></i>Kelola Buku
                        </a>
                        <a class="nav-link" href="<?= base_url('admin/users') ?>">
                            <i class="fas fa-users me-2"></i>Pengguna
                        </a>
                        <a class="nav-link" href="<?= base_url('admin/invoices') ?>">
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
                                    <i class="fas fa-tachometer-alt me-3"></i>Dashboard Admin
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
                        </div>
                    </div>

                    <!-- Stats Cards (Fixed) -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD);">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-primary"><?= number_format($stats['total_books'] ?? 0) ?></h4>
                                        <small class="stats-label">Total Buku</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD);">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h4 class="stats-number text-info"><?= number_format($stats['total_users'] ?? 0) ?></h4>
                                        <small class="stats-label">Total User</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, #9D1C3B 0%, #7D26CD);">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <?php
                                        // Function to format revenue safely
                                        function formatRevenue($amount) {
                                            if (!is_numeric($amount) || $amount == 0) {
                                                return '0';
                                            }
                                            
                                            $amount = (float) $amount;
                                            
                                            if ($amount >= 1000000000) {
                                                return number_format($amount / 1000000000, 1, ',', '.') . 'M';
                                            } elseif ($amount >= 1000000) {
                                                return number_format($amount / 1000000, 1, ',', '.') . 'Jt';
                                            } elseif ($amount >= 1000) {
                                                return number_format($amount / 1000, 0, ',', '.') . 'K';
                                            }
                                            
                                            return number_format($amount, 0, ',', '.');
                                        }
                                        
                                        $revenue = $stats['total_revenue'] ?? 0;
                                        ?>
                                        <h4 class="stats-number text-success">Rp <?= formatRevenue($revenue) ?></h4>
                                        <small class="stats-label">Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Statistics Detail -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="user-stats-card">
                                <div class="card-header">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-users me-2"></i>Statistik Pengguna
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="user-stat-item">
                                                <i class="fas fa-crown user-stat-icon text-warning"></i>
                                                <div class="user-stat-number text-warning"><?= number_format($stats['admin_users'] ?? 0) ?></div>
                                                <small class="text-muted">Admin</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="user-stat-item">
                                                <i class="fas fa-users user-stat-icon text-info"></i>
                                                <div class="user-stat-number text-info"><?= number_format($stats['regular_users'] ?? 0) ?></div>
                                                <small class="text-muted">User Biasa</small>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="text-center">
                                        <a href="<?= base_url('admin/users') ?>" class="btn btn-primary">
                                            <i class="fas fa-cogs me-2"></i>Kelola Pengguna
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="recent-activity-card">
                                <div class="card-header">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-chart-line me-2"></i>Ringkasan Sistem
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="activity-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-book-open text-primary me-3 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold">Manajemen Buku</div>
                                                <small class="text-muted">Kelola katalog buku dan inventori</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="activity-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-invoice text-info me-3 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold">Laporan Invoice</div>
                                                <small class="text-muted">Lihat dan export data invoice</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="<?= base_url('admin/invoices') ?>" class="btn btn-success">
                                            <i class="fas fa-chart-bar me-2"></i>Lihat Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navigation highlighting
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // Add subtle animation to stats cards
        document.querySelectorAll('.stats-card, .user-stats-card, .recent-activity-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Animate numbers on page load - Fixed untuk mencegah NaN
        window.addEventListener('load', function() {
            const numbers = document.querySelectorAll('.stats-number, .user-stat-number');
            numbers.forEach(number => {
                const text = number.textContent;
                const numericValue = parseInt(text.replace(/[^\d]/g, ''));
                
                // Skip animasi jika value bukan number atau 0
                if (isNaN(numericValue) || numericValue <= 0) {
                    return;
                }
                
                let currentValue = 0;
                const increment = numericValue / 50;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= numericValue) {
                        number.textContent = text; // Kembalikan ke format asli
                        clearInterval(timer);
                    } else {
                        // Update dengan format yang sesuai
                        if (text.includes('Rp')) {
                            // Untuk revenue, gunakan format yang sama
                            const currentFormatted = Math.floor(currentValue);
                            if (text.includes('M')) {
                                number.textContent = 'Rp ' + (currentFormatted / 1000000000).toFixed(1) + 'M';
                            } else if (text.includes('Jt')) {
                                number.textContent = 'Rp ' + (currentFormatted / 1000000).toFixed(1) + 'Jt';
                            } else if (text.includes('K')) {
                                number.textContent = 'Rp ' + Math.floor(currentFormatted / 1000) + 'K';
                            } else {
                                number.textContent = 'Rp ' + Math.floor(currentFormatted).toLocaleString();
                            }
                        } else {
                            number.textContent = Math.floor(currentValue).toLocaleString();
                        }
                    }
                }, 30);
            });
        });

        // Activity items hover effect
        document.querySelectorAll('.activity-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>