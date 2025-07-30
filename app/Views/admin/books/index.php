<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Admin Panel' ?> - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .cover-img {
            width: 50px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #f8f9fa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .book-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: none;
            background: white;
            overflow: hidden;
        }
        .book-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 20px 25px;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.4rem 0.8rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .action-buttons .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 20px 25px;
        }
        .modal-footer {
            border: none;
            padding: 20px 25px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        
        .table-container {
            position: relative;
            overflow: visible;
        }
        .table-responsive {
            overflow: visible;
            margin: 0;
        }
        .table {
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100% !important;
        }
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
            position: sticky;
            top: 0;
            z-index: 10;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f8f9fa;
            background-color: white;
            white-space: nowrap;
        }
        .table tbody tr {
            transition: background-color 0.15s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }
        
        .dataTables_wrapper {
            overflow: visible;
            position: relative;
        }
        .dataTables_scrollHead,
        .dataTables_scrollBody,
        .dataTables_scrollFoot {
            overflow: visible !important;
        }
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            margin: 15px 0;
        }
        .dataTables_length select,
        .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .dataTables_paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        .dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            margin: 0 2px;
            cursor: pointer;
        }
        .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }
        .dataTables_paginate .paginate_button.current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .dataTables_paginate .paginate_button.previous,
        .dataTables_paginate .paginate_button.next {
            font-size: 0;
        }
        .dataTables_paginate .paginate_button.previous::before {
            content: '\f104';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
        }
        .dataTables_paginate .paginate_button.next::before {
            content: '\f105';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
        }
        .dataTables_paginate .paginate_button.first::before {
            content: '\f100';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
        }
        .dataTables_paginate .paginate_button.last::before {
            content: '\f101';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
        }
        .price-text {
            font-weight: 600;
            color: #28a745;
        }
        .stock-low {
            color: #dc3545;
            font-weight: 600;
        }
        .stock-normal {
            color: #28a745;
            font-weight: 600;
        }
        
        .modal.fade .modal-dialog {
            transition: transform 0.2s ease-out;
        }
        .modal.show .modal-dialog {
            transform: none;
        }
        
        .action-buttons {
            white-space: nowrap;
            min-width: 120px;
            display: flex;
            justify-content: center;
            gap: 4px;
        }
        
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .dataTables_info {
            font-size: 14px;
            color: #6c757d;
        }
        .dataTables_length label,
        .dataTables_filter label {
            font-size: 14px;
            color: #495057;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        #booksTable {
            table-layout: fixed;
        }
        #booksTable th:nth-child(1) { width: 80px; }
        #booksTable th:nth-child(2) { width: 25%; }
        #booksTable th:nth-child(3) { width: 15%; }
        #booksTable th:nth-child(4) { width: 12%; }
        #booksTable th:nth-child(5) { width: 12%; }
        #booksTable th:nth-child(6) { width: 8%; }
        #booksTable th:nth-child(7) { width: 10%; }
        #booksTable th:nth-child(8) { width: 150px; }
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
                                <img src="<?= base_url('assets/images/profiles/' . (isset($user['profile_image']) ? $user['profile_image'] : 'default-profile.jpg')) ?>" 
                                     alt="Profile" class="profile-img" 
                                     onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                            </div>
                            <div class="col">
                                <h3 class="mb-1">
                                    <i class="fas fa-book me-3"></i>Kelola Buku
                                </h3>
                                <p class="mb-0 opacity-75">
                                    Kelola koleksi buku toko dengan mudah dan efisien
                                </p>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#createBookModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Buku
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h4><?= isset($stats['total_books']) ? $stats['total_books'] : 0 ?></h4>
                                    <small>Total Buku</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h4><?= isset($stats['active_books']) ? $stats['active_books'] : 0 ?></h4>
                                    <small>Buku Aktif</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <h4><?= isset($stats['low_stock_count']) ? $stats['low_stock_count'] : 0 ?></h4>
                                    <small>Stok Rendah</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-tags fa-2x mb-2"></i>
                                    <h4><?= isset($stats['categories_count']) ? $stats['categories_count'] : 0 ?></h4>
                                    <small>Kategori</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Books Table -->
                    <div class="card book-card">
                        <div class="card-header">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-list me-2"></i>Daftar Buku
                                <span class="badge bg-light text-dark ms-2"><?= (isset($books) && is_array($books)) ? count($books) : 0 ?></span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table id="booksTable" class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Cover</th>
                                                <th>Judul</th>
                                                <th>Penulis</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($books) && is_array($books) && !empty($books)): ?>
                                                <?php foreach ($books as $book): ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= base_url('assets/images/covers/' . (isset($book['cover_image']) && $book['cover_image'] ? $book['cover_image'] : 'default-book.jpg')) ?>" 
                                                         alt="Cover" class="cover-img"
                                                         onerror="this.src='<?= base_url('assets/images/default-book.jpg') ?>'">
                                                </td>
                                                <td>
                                                    <div style="min-width: 200px;">
                                                        <div class="text-primary fw-bold"><?= esc($book['title']) ?></div>
                                                        <?php if (isset($book['isbn']) && $book['isbn']): ?>
                                                            <small class="text-muted">ISBN: <?= esc($book['isbn']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td><div style="min-width: 120px;"><?= esc($book['author']) ?></div></td>
                                                <td>
                                                    <?php if (isset($book['category']) && $book['category']): ?>
                                                        <span class="badge bg-secondary"><?= esc($book['category']) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="price-text">Rp <?= number_format($book['price'], 0, ',', '.') ?></span></td>
                                                <td>
                                                    <span class="<?= $book['stock'] <= 5 ? 'stock-low' : 'stock-normal' ?>">
                                                        <?= $book['stock'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($book['status'] === 'active'): ?>
                                                        <span class="badge bg-success status-badge">
                                                            <i class="fas fa-check me-1"></i>Aktif
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger status-badge">
                                                            <i class="fas fa-times me-1"></i>Nonaktif
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <!-- Cover Upload Button -->
                                                        <button type="button" class="btn btn-info btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#uploadModal<?= $book['id'] ?>"
                                                                title="Upload Cover">
                                                            <i class="fas fa-camera"></i>
                                                        </button>
                                                        
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-warning btn-sm edit-btn" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editBookModal<?= $book['id'] ?>"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                                data-book-id="<?= $book['id'] ?>"
                                                                data-book-title="<?= esc($book['title']) ?>"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">Belum ada buku tersimpan</p>
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
    </div>

    <!-- Create Book Modal -->
    <div class="modal fade" id="createBookModal" tabindex="-1" aria-labelledby="createBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBookModalLabel">
                        <i class="fas fa-plus me-2"></i>Tambah Buku Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('admin/books/store') ?>" method="post" id="createBookForm" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_title" class="form-label">
                                        <i class="fas fa-book me-1"></i>Judul Buku <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="create_author" class="form-label">
                                        <i class="fas fa-user me-1"></i>Penulis <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="create_author" name="author" required>
                                </div>
                                <div class="mb-3">
                                    <label for="create_publisher" class="form-label">
                                        <i class="fas fa-building me-1"></i>Penerbit
                                    </label>
                                    <input type="text" class="form-control" id="create_publisher" name="publisher">
                                </div>
                                <div class="mb-3">
                                    <label for="create_publication_year" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Tahun Terbit
                                    </label>
                                    <input type="number" class="form-control" id="create_publication_year" name="publication_year" min="1900" max="<?= date('Y') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="create_isbn" class="form-label">
                                        <i class="fas fa-barcode me-1"></i>ISBN
                                    </label>
                                    <input type="text" class="form-control" id="create_isbn" name="isbn">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_category" class="form-label">
                                        <i class="fas fa-tags me-1"></i>Kategori
                                    </label>
                                    <input type="text" class="form-control" id="create_category" name="category" placeholder="Contoh: Novel, Sejarah, dll">
                                </div>
                                <div class="mb-3">
                                    <label for="create_price" class="form-label">
                                        <i class="fas fa-money-bill me-1"></i>Harga <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="create_price" name="price" step="0.01" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label for="create_stock" class="form-label">
                                        <i class="fas fa-boxes me-1"></i>Stok <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="create_stock" name="stock" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label for="create_pages" class="form-label">
                                        <i class="fas fa-file-alt me-1"></i>Jumlah Halaman
                                    </label>
                                    <input type="number" class="form-control" id="create_pages" name="pages" min="1">
                                </div>
                                <div class="mb-3">
                                    <label for="create_language" class="form-label">
                                        <i class="fas fa-globe me-1"></i>Bahasa
                                    </label>
                                    <select class="form-select" id="create_language" name="language">
                                        <option value="Indonesian">Bahasa Indonesia</option>
                                        <option value="English">English</option>
                                        <option value="Other">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="create_description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi
                            </label>
                            <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_cover_image" class="form-label">
                                        <i class="fas fa-image me-1"></i>Cover Buku <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="create_cover_image" name="cover_image" accept="image/*" required>
                                    <div class="form-text">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="create_status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="active" selected>Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Tambah Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dynamic Modals for Each Book -->
    <?php if (isset($books) && is_array($books) && !empty($books)): ?>
        <?php foreach ($books as $book): ?>
            <!-- Upload Cover Modal -->
            <div class="modal fade" id="uploadModal<?= $book['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-camera me-2"></i>Upload Cover - <?= esc($book['title']) ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('admin/books/upload-cover/' . $book['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-body text-center">
                                <div class="mb-4">
                                    <img src="<?= base_url('assets/images/covers/' . (isset($book['cover_image']) && $book['cover_image'] ? $book['cover_image'] : 'default-book.jpg')) ?>" 
                                         alt="Current Cover" class="img-thumbnail"
                                         style="width: 150px; height: 200px; object-fit: cover;"
                                         onerror="this.src='<?= base_url('assets/images/default-book.jpg') ?>'">
                                </div>
                                <div class="mb-3">
                                    <label for="cover_image<?= $book['id'] ?>" class="form-label">
                                        <i class="fas fa-upload me-1"></i>Pilih Cover Baru
                                    </label>
                                    <input type="file" class="form-control" id="cover_image<?= $book['id'] ?>" 
                                           name="cover_image" accept="image/*" required>
                                    <div class="form-text">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i>Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Book Modal -->
            <div class="modal fade" id="editBookModal<?= $book['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>Edit Buku - <?= esc($book['title']) ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('admin/books/update/' . $book['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_title<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-book me-1"></i>Judul Buku <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="edit_title<?= $book['id'] ?>" 
                                                   name="title" value="<?= esc($book['title']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_author<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-user me-1"></i>Penulis <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="edit_author<?= $book['id'] ?>" 
                                                   name="author" value="<?= esc($book['author']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_publisher<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-building me-1"></i>Penerbit
                                            </label>
                                            <input type="text" class="form-control" id="edit_publisher<?= $book['id'] ?>" 
                                                   name="publisher" value="<?= esc(isset($book['publisher']) ? $book['publisher'] : '') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_publication_year<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-calendar me-1"></i>Tahun Terbit
                                            </label>
                                            <input type="number" class="form-control" id="edit_publication_year<?= $book['id'] ?>" 
                                                   name="publication_year" value="<?= esc(isset($book['publication_year']) ? $book['publication_year'] : '') ?>" min="1900" max="<?= date('Y') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_isbn<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-barcode me-1"></i>ISBN
                                            </label>
                                            <input type="text" class="form-control" id="edit_isbn<?= $book['id'] ?>" 
                                                   name="isbn" value="<?= esc(isset($book['isbn']) ? $book['isbn'] : '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_category<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-tags me-1"></i>Kategori
                                            </label>
                                            <input type="text" class="form-control" id="edit_category<?= $book['id'] ?>" 
                                                   name="category" value="<?= esc(isset($book['category']) ? $book['category'] : '') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_price<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-money-bill me-1"></i>Harga <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="edit_price<?= $book['id'] ?>" 
                                                   name="price" value="<?= $book['price'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_stock<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-boxes me-1"></i>Stok <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="edit_stock<?= $book['id'] ?>" 
                                                   name="stock" value="<?= $book['stock'] ?>" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_pages<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-file-alt me-1"></i>Jumlah Halaman
                                            </label>
                                            <input type="number" class="form-control" id="edit_pages<?= $book['id'] ?>" 
                                                   name="pages" value="<?= esc(isset($book['pages']) ? $book['pages'] : '') ?>" min="1">
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_language<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-globe me-1"></i>Bahasa
                                            </label>
                                            <select class="form-select" id="edit_language<?= $book['id'] ?>" name="language">
                                                <option value="Indonesian" <?= (isset($book['language']) && $book['language'] === 'Indonesian') ? 'selected' : '' ?>>Bahasa Indonesia</option>
                                                <option value="English" <?= (isset($book['language']) && $book['language'] === 'English') ? 'selected' : '' ?>>English</option>
                                                <option value="Other" <?= (isset($book['language']) && $book['language'] === 'Other') ? 'selected' : '' ?>>Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_description<?= $book['id'] ?>" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control" id="edit_description<?= $book['id'] ?>" 
                                              name="description" rows="3"><?= esc(isset($book['description']) ? $book['description'] : '') ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_cover_image<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-image me-1"></i>Cover Buku Baru
                                            </label>
                                            <input type="file" class="form-control" id="edit_cover_image<?= $book['id'] ?>" 
                                                   name="cover_image" accept="image/*">
                                            <div class="form-text">Kosongkan jika tidak ingin mengubah cover. Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_status<?= $book['id'] ?>" class="form-label">
                                                <i class="fas fa-toggle-on me-1"></i>Status <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="edit_status<?= $book['id'] ?>" name="status" required>
                                                <option value="active" <?= $book['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                                <option value="inactive" <?= $book['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Buku
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                    <p class="mb-0">Apakah Anda yakin ingin menghapus buku <strong id="deleteBookTitle"></strong>?</p>
                    <p class="text-muted small mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <form id="deleteForm" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Simple DataTable initialization without problematic features
            const table = $('#booksTable').DataTable({
                "pageLength": 10,
                "order": [[1, "asc"]],
                "responsive": false,
                "autoWidth": false,
                "processing": false,
                "serverSide": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 7] },
                    { "searchable": false, "targets": [0] }
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir", 
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Delete confirmation function - simple version
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                
                const bookId = $(this).data('book-id');
                const bookTitle = $(this).data('book-title');
                
                $('#deleteBookTitle').text(bookTitle);
                $('#deleteForm').attr('action', '<?= base_url('admin/books/delete/') ?>' + bookId);
                
                $('#deleteConfirmModal').modal('show');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Simple form validation
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
            });

            // Reset forms when modals are closed
            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('button[type="submit"]').prop('disabled', false);
            });

            // Simple file validation
            $('input[type="file"]').on('change', function() {
                const file = this.files[0];
                if (file && file.size > 2 * 1024 * 1024) {
                    alert('File terlalu besar. Maksimal 2MB.');
                    $(this).val('');
                }
            });
        });
    </script>
</body>
</html>