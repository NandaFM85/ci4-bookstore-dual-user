<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentMethodModel;
use CodeIgniter\Controller;

class User extends BaseController
{
    protected $userModel;
    protected $bookModel;
    protected $cartModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $paymentMethodModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookModel = new BookModel();
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->db = \Config\Database::connect();
    }

    // Halaman profil user
    public function profile()
    {
        // Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);
        $cartSummary = $this->cartModel->getCartSummary($userId);            

        $data = [
            'title' => 'Edit Profil',
            'user' => $user,
            'total_items' => $cartSummary['total_items'],
        ];

        return view('user/profile', $data);
    }

    public function updateProfile()
    {
        // Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');

        $rules = [
            'fullname' => 'permit_empty|max_length[100]',
            'address' => 'permit_empty|max_length[500]',
            'profile_image' => 'permit_empty|uploaded[profile_image]|is_image[profile_image]|max_size[profile_image,2048]'
        ];

        $messages = [
            'profile_image' => [
                'uploaded' => 'Silakan pilih foto profil',
                'is_image' => 'File harus berupa gambar',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ];

        // Validasi tanpa require image jika tidak diupload
        $inputRules = [
            'fullname' => 'permit_empty|max_length[100]',
            'address' => 'permit_empty|max_length[500]'
        ];

        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid()) {
            $inputRules['profile_image'] = 'uploaded[profile_image]|is_image[profile_image]|max_size[profile_image,2048]';
        }

        if (!$this->validate($inputRules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'address' => $this->request->getPost('address')
        ];

        // Handle file upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus foto lama jika bukan default
            $currentUser = $this->userModel->find($userId);
            if ($currentUser['profile_image'] && $currentUser['profile_image'] !== 'default-profile.jpg') {
                $oldImagePath = FCPATH . 'assets/images/profiles/' . $currentUser['profile_image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload foto baru
            $imageName = $file->getRandomName();
            $file->move(FCPATH . 'assets/images/profiles/', $imageName);
            $data['profile_image'] = $imageName;
        }

        if ($this->userModel->update($userId, $data)) {
            // Update session data
            session()->set([
                'fullname' => $data['fullname'],
                'profile_image' => $data['profile_image'] ?? session()->get('profile_image')
            ]);

            return redirect()->to('/user/profile')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil');
        }
    }

    public function changePassword()
    {
        // Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        $cartSummary = $this->cartModel->getCartSummary($userId); 

        $data = [
            'title' => 'Ubah Password',
            'user' => [
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'total_items' => $cartSummary['total_items'],
        ];

        return view('user/change_password', $data);
    }

    public function updatePassword()
    {
        // Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        $messages = [
            'current_password' => [
                'required' => 'Password saat ini harus diisi'
            ],
            'new_password' => [
                'required' => 'Password baru harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sama'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        $currentUser = $this->userModel->find($userId);

        // Verifikasi password lama
        if (!password_verify($this->request->getPost('current_password'), $currentUser['password'])) {
            return redirect()->back()->with('error', 'Password saat ini salah');
        }

        // Update password
        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        if ($this->userModel->update($userId, ['password' => $newPasswordHash])) {
            return redirect()->to('/user/profile')->with('success', 'Password berhasil diubah!');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah password');
        }
    }

    //catalog
    public function catalog()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');

        if ($search) {
            $books = $this->bookModel->searchBooks($search);
        } elseif ($category) {
            $books = $this->bookModel->getBooksByCategory($category);
        } else {
            $books = $this->bookModel->getActiveBooks();
        }

        $categories = $this->bookModel->getBookCategories();
        $cartSummary = $this->cartModel->getCartSummary($userId);  

        $data = [
            'title' => 'Katalog Buku',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'books' => $books,
            'categories' => $categories,
            'search' => $search,
            'selected_category' => $category,
            'total_items' => $cartSummary['total_items'],
        ];

        return view('user/catalog', $data);
    }

    // Cart
    public function cart()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        $cartSummary = $this->cartModel->getCartSummary($userId);

        $data = [
            'title' => 'Keranjang Belanja',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'cart_items' => $cartSummary['items'],
            'total_items' => $cartSummary['total_items'],
            'total_price' => $cartSummary['total_price']
        ];

        return view('user/cart', $data);
    }

    public function addToCart()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ]);
        }

        $bookId = $this->request->getGet('book_id') ?: $this->request->getPost('book_id');
        $quantity = $this->request->getGet('quantity') ?: $this->request->getPost('quantity') ?: 1;
        $userId = session()->get('id');

        if (!$bookId || !is_numeric($bookId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID buku tidak valid'
            ]);
        }

        $book = $this->bookModel->find($bookId);
        if (!$book) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
        }

        if (isset($book['stock']) && $book['stock'] < $quantity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ]);
        }

        $result = $this->cartModel->addToCart($userId, $bookId, $quantity);

        if ($result) {
            $cartCount = $this->cartModel->getCartItemCount($userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan ke keranjang'
            ]);
        }
    }

    public function updateCart()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ]);
        }

        $bookId = $this->request->getGet('book_id') ?: $this->request->getPost('book_id');
        $quantity = $this->request->getGet('quantity') ?: $this->request->getPost('quantity');
        $userId = session()->get('id');

        if (!$bookId || !is_numeric($bookId) || !is_numeric($quantity)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $book = $this->bookModel->find($bookId);
        if (!$book) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
        }

        if (isset($book['stock']) && $quantity > $book['stock']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $book['stock']
            ]);
        }

        $result = $this->cartModel->updateCartQuantity($userId, $bookId, $quantity);

        if ($result) {
            $cartSummary = $this->cartModel->getCartSummary($userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate',
                'cart_count' => $cartSummary['total_items'],
                'total_price' => number_format($cartSummary['total_price'], 0, ',', '.')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate keranjang'
            ]);
        }
    }

    public function removeFromCart()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ]);
        }

        $bookId = $this->request->getGet('book_id') ?: $this->request->getPost('book_id');
        $userId = session()->get('id');

        if (!$bookId || !is_numeric($bookId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID buku tidak valid'
            ]);
        }

        $result = $this->cartModel->removeFromCart($userId, $bookId);

        if ($result) {
            $cartSummary = $this->cartModel->getCartSummary($userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang',
                'cart_count' => $cartSummary['total_items'],
                'total_price' => number_format($cartSummary['total_price'], 0, ',', '.')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang'
            ]);
        }
    }

    public function clearCart()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ]);
        }

        $userId = session()->get('id');
        $result = $this->cartModel->clearCart($userId);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan',
                'cart_count' => 0,
                'total_price' => '0'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang'
            ]);
        }
    }

    // Checkout
    public function checkout()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        $cartSummary = $this->cartModel->getCartSummary($userId);

        if ($cartSummary['total_items'] == 0) {
            session()->setFlashdata('error', 'Keranjang Anda kosong');
            return redirect()->to('/user/cart');
        }

        $paymentMethods = $this->paymentMethodModel->getActivePaymentMethods();

        $data = [
            'title' => 'Checkout - Transaksi',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'cart_items' => $cartSummary['items'],
            'total_items' => $cartSummary['total_items'],
            'total_price' => $cartSummary['total_price'],
            'payment_methods' => $paymentMethods
        ];

        return view('user/checkout', $data);
    }

    // Process checkout - FIXED
    public function processCheckout()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) session()->get('id');
        $paymentMethod = $this->request->getGet('payment_method');
        $paymentMethod = is_numeric($paymentMethod) ? (int) $paymentMethod : null;

        log_message('info', 'ProcessCheckout called - User ID: ' . $userId . ', Payment Method: ' . $paymentMethod);

        if (!$paymentMethod || $paymentMethod <= 0) {
            session()->setFlashdata('error', 'Silakan pilih metode pembayaran');
            return redirect()->to('/user/checkout');
        }

        try {
            $cartSummary = $this->cartModel->getCartSummary($userId);

            if ($cartSummary['total_items'] == 0) {
                session()->setFlashdata('error', 'Keranjang Anda kosong');
                return redirect()->to('/user/cart');
            }

            // Validasi payment method dengan query langsung
            $paymentMethodQuery = $this->db->query("SELECT * FROM payment_methods WHERE id = ?", [$paymentMethod]);
            $paymentMethodData = $paymentMethodQuery->getRowArray();
            
            if (!$paymentMethodData) {
                session()->setFlashdata('error', 'Metode pembayaran tidak valid');
                return redirect()->to('/user/checkout');
            }

            log_message('info', 'Creating order - User: ' . $userId . ', Items: ' . $cartSummary['total_items'] . ', Total: ' . $cartSummary['total_price']);

            // Siapkan data order items
            $orderItems = [];
            foreach ($cartSummary['items'] as $item) {
                $orderItems[] = [
                    'book_id' => (int) $item['book_id'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price']
                ];
                
                log_message('info', 'Cart item processed - Book ID: ' . $item['book_id'] . ', Quantity: ' . $item['quantity'] . ', Price: ' . $item['price']);
            }

            // Buat pesanan dengan approach yang lebih langsung
            $orderId = $this->createOrderDirect($userId, $orderItems, (float) $cartSummary['total_price'], $paymentMethod);

            if ($orderId) {
                log_message('info', 'Order created successfully with ID: ' . $orderId);
                
                // Kosongkan cart
                $this->cartModel->clearCart($userId);
                
                // Redirect ke halaman pembayaran
                session()->setFlashdata('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
                return redirect()->to('/user/payment/' . $orderId);
            } else {
                log_message('error', 'Failed to create order for user: ' . $userId);
                session()->setFlashdata('error', 'Terjadi kesalahan saat memproses pesanan');
                return redirect()->to('/user/checkout');
            }
        } catch (\Exception $e) {
            log_message('error', 'Checkout Exception: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to('/user/checkout');
        }
    }

    // Method untuk membuat order secara langsung tanpa ORM - FIXED
    private function createOrderDirect($userId, $cartItems, $totalAmount, $paymentMethod)
    {
        $userId = (int) $userId;
        $totalAmount = (float) $totalAmount;
        $paymentMethod = (int) $paymentMethod;
        
        if ($userId <= 0 || $totalAmount <= 0 || $paymentMethod <= 0 || empty($cartItems)) {
            log_message('error', 'Invalid parameters for createOrderDirect');
            return false;
        }
        
        try {
            // Start transaction
            $this->db->transStart();
            
            // Generate order number
            do {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $checkQuery = $this->db->query("SELECT COUNT(*) as count FROM orders WHERE order_number = ?", [$orderNumber]);
                $checkResult = $checkQuery->getRow();
            } while ($checkResult->count > 0);
            
            $now = date('Y-m-d H:i:s');
            
            // Insert order dengan query langsung
            $orderSql = "INSERT INTO orders (user_id, order_number, total_amount, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'pending_payment', ?, ?)";
            $orderResult = $this->db->query($orderSql, [$userId, $orderNumber, $totalAmount, $paymentMethod, $now, $now]);
            
            if (!$orderResult) {
                throw new \Exception('Failed to create order');
            }
            
            $orderId = $this->db->insertID();
            log_message('info', 'Order created with ID: ' . $orderId);
            
            // Insert order items
            foreach ($cartItems as $item) {
                $itemSql = "INSERT INTO order_items (order_id, book_id, quantity, price, created_at) VALUES (?, ?, ?, ?, ?)";
                $itemResult = $this->db->query($itemSql, [
                    $orderId,
                    (int) $item['book_id'],
                    (int) $item['quantity'],
                    (float) $item['price'],
                    $now
                ]);
                
                if (!$itemResult) {
                    throw new \Exception('Failed to insert order item for book ID: ' . $item['book_id']);
                }
                
                log_message('info', 'Order item inserted - Book ID: ' . $item['book_id'] . ', Quantity: ' . $item['quantity'] . ', Price: ' . $item['price']);
            }
            
            // Complete transaction
            $this->db->transComplete();
            
            if ($this->db->transStatus() === FALSE) {
                log_message('error', 'Transaction failed in createOrderDirect');
                return false;
            }
            
            log_message('info', 'Order creation completed successfully with ID: ' . $orderId);
            return $orderId;
            
        } catch (\Exception $e) {
            log_message('error', 'createOrderDirect exception: ' . $e->getMessage());
            $this->db->transRollback();
            return false;
        }
    }

    // Halaman pembayaran - FIXED with Dummy Barcode
    public function payment($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        
        // Get order dengan query langsung
        $orderSql = "SELECT o.*, u.username, u.fullname, u.email 
                     FROM orders o 
                     JOIN users u ON u.id = o.user_id 
                     WHERE o.id = ? AND o.user_id = ?";
        $orderQuery = $this->db->query($orderSql, [$orderId, $userId]);
        $order = $orderQuery->getRowArray();

        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan');
            return redirect()->to('/user/orders');
        }

        // Get order items
        $itemsSql = "SELECT oi.*, b.title, b.author, b.isbn, b.cover_image 
                    FROM order_items oi 
                    JOIN books b ON b.id = oi.book_id 
                    WHERE oi.order_id = ?";
        $itemsQuery = $this->db->query($itemsSql, [$orderId]);
        $order['items'] = $itemsQuery->getResultArray();

        // Get payment method
        $paymentMethodSql = "SELECT * FROM payment_methods WHERE id = ?";
        $paymentMethodQuery = $this->db->query($paymentMethodSql, [$order['payment_method']]);
        $paymentMethod = $paymentMethodQuery->getRowArray();

        // Get cart summary for navbar
        $cartSummary = $this->cartModel->getCartSummary($userId);

        // Generate dummy barcode filename based on order
        $barcodeFile = 'barcode_' . $orderId . '.png';

        $data = [
            'title' => 'Pembayaran',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'order' => $order,
            'payment_method' => $paymentMethod,
            'total_items' => $cartSummary['total_items'],
            'barcode_file' => $barcodeFile
        ];

        return view('user/payment', $data);
    }

    // Complete payment directly - Using GET method to avoid CSRF
    public function completePayment()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Anda harus login terlebih dahulu');
            return redirect()->to('/auth/login');
        }

        $orderId = $this->request->getGet('order_id');
        $userId = session()->get('id');

        if (!$orderId || !is_numeric($orderId)) {
            session()->setFlashdata('error', 'ID pesanan tidak valid');
            return redirect()->to('/user/orders');
        }

        try {
            // Validasi order dengan query langsung
            $orderQuery = $this->db->query("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$orderId, $userId]);
            $order = $orderQuery->getRowArray();
            
            if (!$order) {
                session()->setFlashdata('error', 'Pesanan tidak ditemukan');
                return redirect()->to('/user/orders');
            }

            // Jika sudah completed, redirect ke orders
            if ($order['status'] == 'completed') {
                session()->setFlashdata('info', 'Pesanan sudah selesai');
                return redirect()->to('/user/orders');
            }

            // Update order status langsung ke completed dan set dummy receipt
            $now = date('Y-m-d H:i:s');
            $dummyReceipt = 'dummy_receipt_' . $orderId . '_' . time() . '.png';
            
            $updateSql = "UPDATE orders SET status = 'completed', receipt_image = ?, updated_at = ? WHERE id = ?";
            $updateResult = $this->db->query($updateSql, [$dummyReceipt, $now, $orderId]);

            if ($updateResult) {
                log_message('info', 'Order ' . $orderId . ' completed via payment confirmation by user ' . $userId);
                session()->setFlashdata('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda telah selesai.');
                return redirect()->to('/user/orders');
            } else {
                session()->setFlashdata('error', 'Gagal memproses pembayaran');
                return redirect()->to('/user/payment/' . $orderId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Complete payment exception: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem');
            return redirect()->to('/user/payment/' . $orderId);
        }
    }

    // Alternative method using simpler routing
    public function pay()
    {
        $action = $this->request->getGet('action');
        $orderId = $this->request->getGet('order_id');
        
        if ($action === 'complete' && $orderId) {
            return $this->completePayment();
        }
        
        return redirect()->to('/user/orders');
    }

    // Halaman pesanan saya - FIXED
    public function orders()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        
        // Get orders dengan query langsung
        $ordersSql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $ordersQuery = $this->db->query($ordersSql, [$userId]);
        $orders = $ordersQuery->getResultArray();
        
        // Ambil items untuk setiap order
        foreach ($orders as &$order) {
            // Get order items
            $itemsSql = "SELECT oi.*, b.title, b.author, b.isbn, b.cover_image 
                        FROM order_items oi 
                        JOIN books b ON b.id = oi.book_id 
                        WHERE oi.order_id = ?";
            $itemsQuery = $this->db->query($itemsSql, [$order['id']]);
            $order['items'] = $itemsQuery->getResultArray();
            
            // Get total items count
            $countSql = "SELECT SUM(quantity) as total FROM order_items WHERE order_id = ?";
            $countQuery = $this->db->query($countSql, [$order['id']]);
            $countResult = $countQuery->getRow();
            $order['total_items'] = (int) ($countResult->total ?? 0);
        }

        $cartSummary = $this->cartModel->getCartSummary($userId);

        $data = [
            'title' => 'Pesanan Saya',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image')
            ],
            'orders' => $orders,
            'total_items' => $cartSummary['total_items']
        ];

        return view('user/orders', $data);
    }

    // Konfirmasi pesanan selesai - FIXED
    public function confirmOrder()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ]);
        }

        $orderId = $this->request->getGet('order_id');
        $userId = session()->get('id');

        // Validasi order dengan query langsung
        $orderQuery = $this->db->query("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'on_shipping'", [$orderId, $userId]);
        $order = $orderQuery->getRowArray();

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak valid atau belum dalam status pengiriman'
            ]);
        }

        // Update order status dengan query langsung
        $now = date('Y-m-d H:i:s');
        $updateSql = "UPDATE orders SET status = 'completed', updated_at = ? WHERE id = ?";
        $result = $this->db->query($updateSql, [$now, $orderId]);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi sebagai selesai'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pesanan'
            ]);
        }
    }

    // Get invoice data for modal - NEW
    public function getInvoice()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $orderId = $this->request->getGet('order_id');
        $userId = session()->get('id');

        if (!$orderId || !is_numeric($orderId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pesanan tidak valid'
            ]);
        }

        try {
            // Get order dengan query langsung
            $orderSql = "SELECT o.*, u.username, u.fullname, u.email, u.address, pm.name as payment_method_name, pm.account_info
                         FROM orders o 
                         JOIN users u ON u.id = o.user_id 
                         LEFT JOIN payment_methods pm ON pm.id = o.payment_method
                         WHERE o.id = ? AND o.user_id = ?";
            $orderQuery = $this->db->query($orderSql, [$orderId, $userId]);
            $order = $orderQuery->getRowArray();

            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ]);
            }

            // Get order items
            $itemsSql = "SELECT oi.*, b.title, b.author, b.isbn, b.cover_image 
                        FROM order_items oi 
                        JOIN books b ON b.id = oi.book_id 
                        WHERE oi.order_id = ?
                        ORDER BY oi.id";
            $itemsQuery = $this->db->query($itemsSql, [$orderId]);
            $order['items'] = $itemsQuery->getResultArray();

            // Calculate totals
            $order['total_quantity'] = array_sum(array_column($order['items'], 'quantity'));
            $order['subtotal'] = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $order['items']));

            // Format dates
            $order['formatted_created_date'] = date('d F Y, H:i', strtotime($order['created_at']));
            $order['formatted_print_date'] = date('d F Y, H:i');

            // Get status text
            $statusMap = [
                'pending_payment' => 'Menunggu Pembayaran',
                'pending_verification' => 'Menunggu Verifikasi',
                'approved' => 'Disetujui',
                'on_shipping' => 'Sedang Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
            $order['status_text'] = $statusMap[$order['status']] ?? ucfirst($order['status']);

            // Company info
            $company = [
                'name' => 'Toko Buku Online',
                'address' => 'Jl. Raya Bookstore No. 123, Jakarta Selatan 12345',
                'phone' => '(021) 1234-5678',
                'email' => 'info@tokobuku.com',
                'website' => 'www.tokobuku.com'
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'order' => $order,
                    'company' => $company
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'getInvoice exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }

public function printInvoice($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');

        if (!$orderId || !is_numeric($orderId)) {
            session()->setFlashdata('error', 'ID pesanan tidak valid');
            return redirect()->to('/user/orders');
        }

        try {
            // Get order dengan query langsung
            $orderSql = "SELECT o.*, u.username, u.fullname, u.email, u.address, pm.name as payment_method_name, pm.account_info
                         FROM orders o 
                         JOIN users u ON u.id = o.user_id 
                         LEFT JOIN payment_methods pm ON pm.id = o.payment_method
                         WHERE o.id = ? AND o.user_id = ?";
            $orderQuery = $this->db->query($orderSql, [$orderId, $userId]);
            $order = $orderQuery->getRowArray();

            if (!$order) {
                session()->setFlashdata('error', 'Pesanan tidak ditemukan');
                return redirect()->to('/user/orders');
            }

            // Get order items
            $itemsSql = "SELECT oi.*, b.title, b.author, b.isbn, b.cover_image 
                        FROM order_items oi 
                        JOIN books b ON b.id = oi.book_id 
                        WHERE oi.order_id = ?
                        ORDER BY oi.id";
            $itemsQuery = $this->db->query($itemsSql, [$orderId]);
            $order['items'] = $itemsQuery->getResultArray();

            // Calculate totals
            $order['total_quantity'] = array_sum(array_column($order['items'], 'quantity'));
            $order['subtotal'] = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $order['items']));

            // Format dates
            $order['formatted_created_date'] = date('d F Y, H:i', strtotime($order['created_at']));
            $order['formatted_print_date'] = date('d F Y, H:i');

            // Get status text
            $statusMap = [
                'pending_payment' => 'Menunggu Pembayaran',
                'pending_verification' => 'Menunggu Verifikasi',
                'approved' => 'Disetujui',
                'on_shipping' => 'Sedang Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
            $order['status_text'] = $statusMap[$order['status']] ?? ucfirst($order['status']);

            // Company info
            $company = [
                'name' => 'Toko Buku Online',
                'address' => 'Jl. Raya Bookstore No. 123, Jakarta Selatan 12345',
                'phone' => '(021) 1234-5678',
                'email' => 'info@tokubuku.com',
                'website' => 'www.tokobuku.com'
            ];

            $data = [
                'title' => 'Invoice - ' . $order['order_number'],
                'order' => $order,
                'company' => $company
            ];

            // Return view khusus untuk print (tanpa layout navbar/footer)
            return view('user/print_invoice', $data);

        } catch (\Exception $e) {
            log_message('error', 'printInvoice exception: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem');
            return redirect()->to('/user/orders');
        }
    }

    // Method untuk download invoice sebagai file
    public function downloadInvoice($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');

        if (!$orderId || !is_numeric($orderId)) {
            session()->setFlashdata('error', 'ID pesanan tidak valid');
            return redirect()->to('/user/orders');
        }

        try {
            // Get order dengan query langsung
            $orderSql = "SELECT o.*, u.username, u.fullname, u.email, u.address, pm.name as payment_method_name, pm.account_info
                         FROM orders o 
                         JOIN users u ON u.id = o.user_id 
                         LEFT JOIN payment_methods pm ON pm.id = o.payment_method
                         WHERE o.id = ? AND o.user_id = ?";
            $orderQuery = $this->db->query($orderSql, [$orderId, $userId]);
            $order = $orderQuery->getRowArray();

            if (!$order) {
                session()->setFlashdata('error', 'Pesanan tidak ditemukan');
                return redirect()->to('/user/orders');
            }

            // Get order items
            $itemsSql = "SELECT oi.*, b.title, b.author, b.isbn, b.cover_image 
                        FROM order_items oi 
                        JOIN books b ON b.id = oi.book_id 
                        WHERE oi.order_id = ?
                        ORDER BY oi.id";
            $itemsQuery = $this->db->query($itemsSql, [$orderId]);
            $order['items'] = $itemsQuery->getResultArray();

            // Calculate totals
            $order['total_quantity'] = array_sum(array_column($order['items'], 'quantity'));
            $order['subtotal'] = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $order['items']));

            // Generate HTML content for download
            $html = $this->generateInvoiceHTML($order);

            // Set headers untuk download
            $filename = 'Invoice_' . $order['order_number'] . '_' . date('Y-m-d') . '.html';
            
            return $this->response
                ->setHeader('Content-Type', 'text/html')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($html);

        } catch (\Exception $e) {
            log_message('error', 'downloadInvoice exception: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem');
            return redirect()->to('/user/orders');
        }
    }

    // Helper method untuk generate HTML invoice
    private function generateInvoiceHTML($order)
    {
        // Company info
        $company = [
            'name' => 'Toko Buku Online',
            'address' => 'Jl. Raya Bookstore No. 123, Jakarta Selatan 12345',
            'phone' => '(021) 1234-5678',
            'email' => 'info@tokubuku.com',
            'website' => 'www.tokobuku.com'
        ];

        // Format dates
        $createdDate = date('d F Y, H:i', strtotime($order['created_at']));
        $printDate = date('d F Y, H:i');

        // Status text
        $statusMap = [
            'pending_payment' => 'Menunggu Pembayaran',
            'pending_verification' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'on_shipping' => 'Sedang Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
        $statusText = $statusMap[$order['status']] ?? ucfirst($order['status']);

        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - ' . htmlspecialchars($order['order_number']) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 2px solid #dee2e6;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.4;
        }
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #495057;
            margin: 20px 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
            font-weight: bold;
        }
        .info-section p {
            margin: 2px 0;
            font-size: 12px;
            line-height: 1.4;
        }
        .invoice-body {
            padding: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
        }
        .items-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .total-row.final {
            font-weight: bold;
            font-size: 16px;
            color: #28a745;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }
        .invoice-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-shipping { background: #cce5ff; color: #004085; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">' . htmlspecialchars($company['name']) . '</div>
                <div class="company-details">
                    ' . htmlspecialchars($company['address']) . '<br>
                    Tel: ' . htmlspecialchars($company['phone']) . ' | Email: ' . htmlspecialchars($company['email']) . '<br>
                    Website: ' . htmlspecialchars($company['website']) . '
                </div>
            </div>
            
            <div class="invoice-title">INVOICE</div>
            
            <div class="invoice-info">
                <div class="info-section">
                    <h4>Informasi Pesanan</h4>
                    <p><strong>No. Pesanan:</strong> ' . htmlspecialchars($order['order_number']) . '</p>
                    <p><strong>Tanggal Pesanan:</strong> ' . $createdDate . '</p>
                    <p><strong>Status:</strong> <span class="status-badge status-' . $order['status'] . '">' . $statusText . '</span></p>
                </div>
                <div class="info-section">
                    <h4>Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> ' . htmlspecialchars($order['fullname'] ?: $order['username']) . '</p>
                    <p><strong>Email:</strong> ' . htmlspecialchars($order['email']) . '</p>
                    <p><strong>Username:</strong> ' . htmlspecialchars($order['username']) . '</p>
                    ' . ($order['address'] ? '<p><strong>Alamat:</strong> ' . htmlspecialchars($order['address']) . '</p>' : '') . '
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Buku</th>
                        <th>Penulis</th>
                        <th>ISBN</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        foreach ($order['items'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $html .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . htmlspecialchars($item['title']) . '</td>
                        <td>' . htmlspecialchars($item['author']) . '</td>
                        <td>' . htmlspecialchars($item['isbn']) . '</td>
                        <td class="text-center">' . $item['quantity'] . '</td>
                        <td class="text-right">Rp ' . number_format($item['price'], 0, ',', '.') . '</td>
                        <td class="text-right">Rp ' . number_format($subtotal, 0, ',', '.') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Total Item:</span>
                    <span>' . count($order['items']) . ' item(s)</span>
                </div>
                <div class="total-row">
                    <span>Total Quantity:</span>
                    <span>' . array_sum(array_column($order['items'], 'quantity')) . '</span>
                </div>
                <div class="total-row final">
                    <span>TOTAL PEMBAYARAN:</span>
                    <span>Rp ' . number_format($order['total_amount'], 0, ',', '.') . '</span>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <p>Invoice ini digenerate secara otomatis pada ' . $printDate . '</p>
            <p>Terima kasih atas kepercayaan Anda berbelanja di ' . htmlspecialchars($company['name']) . '</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    // Method untuk generate PDF (menggunakan library seperti TCPDF atau DOMPDF)
    public function downloadInvoicePDF($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');
        
        // Validasi order
        $orderQuery = $this->db->query("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$orderId, $userId]);
        $order = $orderQuery->getRowArray();

        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan');
            return redirect()->to('/user/orders');
        }

        // TODO: Implementasi PDF generation
        // Untuk saat ini, redirect ke download HTML
        session()->setFlashdata('info', 'Fitur download PDF sedang dalam pengembangan. Silakan gunakan download HTML atau print untuk sementara.');
        return redirect()->to('/user/downloadInvoice/' . $orderId);
    }
}