<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $userModel;
    protected $bookModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookModel = new BookModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    // Halaman pesanan admin
public function invoices()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
        return redirect()->to('/auth/login');
    }

    // Ambil semua orders dengan informasi user
    $orders = $this->orderModel->select('orders.id, orders.order_number, orders.total_amount, orders.created_at, orders.status, users.fullname, users.username')
                              ->join('users', 'users.id = orders.user_id')
                              ->orderBy('orders.created_at', 'DESC')
                              ->findAll();

    // Statistik invoice
    $stats = [
        'total_invoices' => count($orders),
        'total_revenue' => array_sum(array_column($orders, 'total_amount')),
        'completed_orders' => count(array_filter($orders, function($order) {
            return $order['status'] === 'completed';
        })),
        'pending_orders' => count(array_filter($orders, function($order) {
            return in_array($order['status'], ['pending_payment', 'pending_verification']);
        }))
    ];

    $data = [
        'title' => 'Kelola Invoice',
        'user' => [
            'username' => session()->get('username'),
            'fullname' => session()->get('fullname'),
            'email' => session()->get('email'),
            'profile_image' => session()->get('profile_image')
        ],
        'orders' => $orders,
        'stats' => $stats
    ];

    return view('admin/invoices', $data);
}

// Export invoice ke Excel
public function exportInvoices()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
        return redirect()->to('/auth/login');
    }

    // Ambil data orders dengan informasi user
    $orders = $this->orderModel->select('orders.id, orders.order_number, orders.total_amount, orders.created_at, orders.status, users.fullname, users.username')
                              ->join('users', 'users.id = orders.user_id')
                              ->orderBy('orders.created_at', 'DESC')
                              ->findAll();

    // Set filename dengan timestamp
    $filename = 'invoice_report_' . date('Y-m-d_H-i-s') . '.csv';
    
    // Set headers untuk download CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Buka output stream
    $output = fopen('php://output', 'w');
    
    // Tulis BOM untuk support UTF-8 di Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header kolom
    fputcsv($output, [
        'ID',
        'Nama User',
        'Order Number',
        'Total Amount',
        'Status',
        'Created At'
    ]);
    
    // Tulis data
    foreach ($orders as $order) {
        fputcsv($output, [
            $order['id'],
            $order['fullname'] ?: $order['username'], // Gunakan fullname, fallback ke username
            $order['order_number'],
            'Rp ' . number_format($order['total_amount'], 0, ',', '.'),
            ucfirst(str_replace('_', ' ', $order['status'])),
            date('d/m/Y H:i:s', strtotime($order['created_at']))
        ]);
    }
    
    fclose($output);
    exit;
}
}