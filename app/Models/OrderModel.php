<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'order_number', 'total_amount', 'payment_method', 
        'payment_account', 'tracking_number', 'status', 'notes'
    ];
    protected $useTimestamps = false; // Disable automatic timestamps
    protected $dateFormat = 'datetime';

    // Generate nomor pesanan unik
    public function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while ($this->where('order_number', $orderNumber)->first());
        
        return $orderNumber;
    }

    // Insert order menggunakan query builder manual
    public function insertOrder($data)
    {
        $userId = (int) $data['user_id'];
        $orderNumber = $data['order_number'];
        $totalAmount = (float) $data['total_amount'];
        $paymentMethod = (int) $data['payment_method'];
        $status = $data['status'];
        
        log_message('info', 'OrderModel insertOrder - User ID: ' . $userId . ', Order Number: ' . $orderNumber . ', Total: ' . $totalAmount . ', Payment Method: ' . $paymentMethod . ', Status: ' . $status);
        
        $sql = "INSERT INTO orders (user_id, order_number, total_amount, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        
        $result = $this->db->query($sql, [$userId, $orderNumber, $totalAmount, $paymentMethod, $status]);
        
        if ($result) {
            $insertedId = $this->db->insertID();
            log_message('info', 'OrderModel - Order inserted with ID: ' . $insertedId);
            return $insertedId;
        } else {
            log_message('error', 'OrderModel - Failed to insert order');
            return false;
        }
    }

    // Buat pesanan baru dengan penanganan yang lebih robust
    public function createOrder($userId, $cartItems, $totalAmount, $paymentMethod)
    {
        // Type cast dan validasi semua parameter
        $userId = (int) $userId;
        $totalAmount = (float) $totalAmount;
        $paymentMethod = (int) $paymentMethod;
        
        if ($userId <= 0 || $totalAmount <= 0 || $paymentMethod <= 0) {
            log_message('error', 'Invalid parameters for createOrder');
            return false;
        }
        
        if (empty($cartItems) || !is_array($cartItems)) {
            log_message('error', 'Invalid cart items for createOrder');
            return false;
        }
        
        try {
            // Start transaction
            $this->db->transBegin();
            
            $orderNumber = $this->generateOrderNumber();
            
            $orderData = [
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'pending_payment'
            ];

            log_message('info', 'Creating order with data: ' . json_encode($orderData));
            
            // Insert order menggunakan method manual
            $orderId = $this->insertOrder($orderData);
            
            if (!$orderId) {
                log_message('error', 'Failed to insert order');
                throw new \Exception('Failed to create order');
            }
            
            log_message('info', 'Order created with ID: ' . $orderId);
            
            // Insert order items menggunakan batch insert
            $orderItemModel = new OrderItemModel();
            $orderItemsData = [];
            
            foreach ($cartItems as $item) {
                $orderItemsData[] = [
                    'order_id' => $orderId,
                    'book_id' => (int) $item['book_id'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price']
                ];
            }
            
            log_message('info', 'Inserting order items: ' . json_encode($orderItemsData));
            
            $itemResult = $orderItemModel->insertMultipleOrderItems($orderItemsData);
            if (!$itemResult) {
                log_message('error', 'Failed to insert order items');
                throw new \Exception('Failed to insert order items');
            }
            
            // Commit transaction
            $this->db->transCommit();
            
            log_message('info', 'Order creation completed successfully with ID: ' . $orderId);
            return $orderId;
            
        } catch (\Exception $e) {
            log_message('error', 'Order creation exception: ' . $e->getMessage());
            $this->db->transRollback();
            return false;
        }
    }

    // Ambil pesanan dengan detail items
    public function getOrderWithItems($orderId)
    {
        $orderId = (int) $orderId;
        
        $order = $this->select('orders.*, users.username, users.fullname, users.email')
                      ->join('users', 'users.id = orders.user_id')
                      ->find($orderId);

        if ($order) {
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->getOrderItems($orderId);
        }

        return $order;
    }

    // Ambil pesanan berdasarkan user
    public function getUserOrders($userId, $limit = null)
    {
        $userId = (int) $userId;
        
        $builder = $this->where('user_id', $userId)
                       ->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    // Ambil semua pesanan untuk admin
    public function getAllOrdersForAdmin($status = null)
    {
        $builder = $this->select('orders.*, users.username, users.fullname, users.email')
                       ->join('users', 'users.id = orders.user_id')
                       ->orderBy('orders.created_at', 'DESC');

        if ($status) {
            $builder->where('orders.status', $status);
        }

        return $builder->findAll();
    }

    // Update status pesanan menggunakan query manual
    public function updateOrderStatus($orderId, $status, $additionalData = [])
    {
        $orderId = (int) $orderId;
        
        try {
            $setParts = ['status = ?', 'updated_at = NOW()'];
            $params = [$status];
            
            foreach ($additionalData as $key => $value) {
                $setParts[] = $key . ' = ?';
                $params[] = $value;
            }
            
            $params[] = $orderId; // untuk WHERE clause
            
            $sql = "UPDATE orders SET " . implode(', ', $setParts) . " WHERE id = ?";
            
            log_message('info', 'OrderModel updateOrderStatus - SQL: ' . $sql);
            log_message('info', 'OrderModel updateOrderStatus - Params: ' . json_encode($params));
            
            $result = $this->db->query($sql, $params);
            
            if ($result) {
                log_message('info', 'OrderModel - Order status updated successfully');
                return true;
            } else {
                log_message('error', 'OrderModel - Failed to update order status');
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'OrderModel updateOrderStatus exception: ' . $e->getMessage());
            return false;
        }
    }

    // Statistik pesanan
    public function getOrderStats()
    {
        return [
            'total_orders' => $this->countAll(),
            'pending_verification' => $this->where('status', 'pending_verification')->countAllResults(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'on_shipping' => $this->where('status', 'on_shipping')->countAllResults(),
            'completed' => $this->where('status', 'completed')->countAllResults(),
            'total_revenue' => $this->selectSum('total_amount')->where('status', 'completed')->first()['total_amount'] ?? 0
        ];
    }
}