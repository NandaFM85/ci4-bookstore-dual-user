<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Toko Buku</title>
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
        .profile-img-small {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f8f9fa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .user-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }
        .user-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 20px 25px;
        }
        .role-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.4rem 0.8rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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
        .table {
            border-radius: 15px;
            overflow: hidden;
        }
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f8f9fa;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
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
                                <h3 class="mb-1">
                                    <i class="fas fa-users me-3"></i>Manajemen Pengguna
                                </h3>
                                <p class="mb-0 opacity-75">
                                    Kelola semua pengguna sistem dengan mudah dan aman
                                </p>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Pengguna
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Users Table -->
                    <div class="card user-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>Daftar Pengguna
                                <span class="badge bg-light text-dark ms-2"><?= is_array($users) ? count($users) : 0 ?></span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Username</th>
                                            <th>Nama Lengkap</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (is_array($users) && !empty($users)): ?>
                                            <?php foreach ($users as $userData): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= base_url('assets/images/profiles/' . ($userData['profile_image'] ?: 'default-profile.jpg')) ?>" 
                                                     alt="Profile" class="profile-img-small"
                                                     onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                                            </td>
                                            <td>
                                                <strong class="text-primary"><?= esc($userData['username']) ?></strong>
                                            </td>
                                            <td><?= esc($userData['fullname'] ?: '-') ?></td>
                                            <td><?= esc($userData['email']) ?></td>
                                            <td>
                                                <?php if ($userData['role'] === 'admin'): ?>
                                                    <span class="badge bg-success role-badge">
                                                        <i class="fas fa-crown me-1"></i>Admin
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary role-badge">
                                                        <i class="fas fa-user me-1"></i>User
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= esc($userData['address'] ? (strlen($userData['address']) > 30 ? substr($userData['address'], 0, 30) . '...' : $userData['address']) : '-') ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Profile Upload Button -->
                                                    <button type="button" class="btn btn-info btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#uploadModal<?= $userData['id'] ?>"
                                                            title="Upload Foto">
                                                        <i class="fas fa-camera"></i>
                                                    </button>
                                                    
                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-warning btn-sm edit-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editUserModal<?= $userData['id'] ?>"
                                                            title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <!-- Delete Button -->
                                                    <?php if ($userData['id'] != session()->get('user_id')): ?>
                                                        <form action="<?= base_url('admin/users/delete/' . $userData['id']) ?>" 
                                                              method="post" class="d-inline delete-form">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">Belum ada pengguna terdaftar</p>
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('admin/users/store') ?>" method="post" id="createUserForm">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_username" class="form-label">
                                <i class="fas fa-user me-1"></i>Username <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="create_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="create_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_fullname" class="form-label">
                                <i class="fas fa-id-card me-1"></i>Nama Lengkap
                            </label>
                            <input type="text" class="form-control" id="create_fullname" name="fullname">
                        </div>
                        <div class="mb-3">
                            <label for="create_address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Alamat
                            </label>
                            <textarea class="form-control" id="create_address" name="address" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="create_role" class="form-label">
                                <i class="fas fa-users-cog me-1"></i>Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="create_role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="create_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="create_password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dynamic Modals for Each User -->
    <?php if (is_array($users) && !empty($users)): ?>
        <?php foreach ($users as $userData): ?>
            <!-- Upload Modal -->
            <div class="modal fade" id="uploadModal<?= $userData['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-camera me-2"></i>Upload Foto Profil - <?= esc($userData['username']) ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/users/upload-profile/' . $userData['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-body text-center">
                                <div class="mb-4">
                                    <img src="<?= base_url('assets/images/profiles/' . ($userData['profile_image'] ?: 'default-profile.jpg')) ?>" 
                                         alt="Current Profile" class="img-thumbnail rounded-circle"
                                         style="width: 150px; height: 150px; object-fit: cover;"
                                         onerror="this.src='<?= base_url('assets/images/default-profile.jpg') ?>'">
                                </div>
                                <div class="mb-3">
                                    <label for="profile_image<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-upload me-1"></i>Pilih Foto Baru
                                    </label>
                                    <input type="file" class="form-control" id="profile_image<?= $userData['id'] ?>" 
                                           name="profile_image" accept="image/*" required>
                                    <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB</div>
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

            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal<?= $userData['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>Edit Pengguna - <?= esc($userData['username']) ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/users/update/' . $userData['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_username<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-user me-1"></i>Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_username<?= $userData['id'] ?>" 
                                           name="username" value="<?= esc($userData['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="edit_email<?= $userData['id'] ?>" 
                                           name="email" value="<?= esc($userData['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_fullname<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="edit_fullname<?= $userData['id'] ?>" 
                                           name="fullname" value="<?= esc($userData['fullname']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_address<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Alamat
                                    </label>
                                    <textarea class="form-control" id="edit_address<?= $userData['id'] ?>" 
                                              name="address" rows="3"><?= esc($userData['address']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_role<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-users-cog me-1"></i>Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="edit_role<?= $userData['id'] ?>" name="role" required>
                                        <option value="user" <?= $userData['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= $userData['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_password<?= $userData['id'] ?>" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Password Baru
                                    </label>
                                    <input type="password" class="form-control" id="edit_password<?= $userData['id'] ?>" 
                                           name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                },
                "pageLength": 10,
                "order": [[1, "asc"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ]
            });

            // Confirm delete
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const username = $(this).closest('tr').find('td:nth-child(2) strong').text();
                
                if (confirm(`Apakah Anda yakin ingin menghapus pengguna ${username}?`)) {
                    const submitBtn = $(form).find('button[type="submit"]');
                    const originalHtml = submitBtn.html();
                    
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<i class="fas fa-spinner fa-spin"></i>');
                    
                    form.submit();
                }
            });

            // Show success/error messages with auto-hide
            $('.alert').each(function() {
                const alert = $(this);
                setTimeout(function() {
                    alert.fadeOut('slow');
                }, 5000);
            });

            // Reset forms when modals are closed
            $('.modal').on('hidden.bs.modal', function() {
                const forms = $(this).find('form');
                forms.each(function() {
                    this.reset();
                });
            });

            // Form submission with loading state
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalHtml = submitBtn.html();
                
                submitBtn.prop('disabled', true);
                if (submitBtn.hasClass('btn-primary')) {
                    submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
                } else if (submitBtn.hasClass('btn-danger')) {
                    submitBtn.html('<i class="fas fa-spinner fa-spin"></i>');
                }
                
                // Re-enable after 10 seconds (fallback)
                setTimeout(function() {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalHtml);
                }, 10000);
            });
        });
    </script>
</body>
</html>