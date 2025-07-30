<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'book_id', 'quantity', 'price'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    // Validation rules
    protected $validationRules = [
        'order_id' => 'required|integer',
        'book_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'price' => 'required|decimal'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID harus diisi',
            'integer' => 'Order ID harus berupa angka'
        ],
        'book_id' => [
            'required' => 'Book ID harus diisi',
            'integer' => 'Book ID harus berupa angka'
        ],
        'quantity' => [
            'required' => 'Quantity harus diisi',
            'integer' => 'Quantity harus berupa angka',
            'greater_than' => 'Quantity harus lebih dari 0'
        ],
        'price' => [
            'required' => 'Price harus diisi',
            'decimal' => 'Price harus berupa angka desimal'
        ]
    ];

    // Ambil items pesanan dengan detail buku
    public function getOrderItems($orderId)
    {
        $orderId = (int) $orderId; // Type cast
        
        return $this->select('order_items.*, books.title, books.author, books.isbn, books.cover_image')
                   ->join('books', 'books.id = order_items.book_id')
                   ->where('order_items.order_id', $orderId)
                   ->findAll();
    }

    // Hitung total item dalam pesanan
    public function getOrderItemCount($orderId)
    {
        $orderId = (int) $orderId; // Type cast
        
        $result = $this->selectSum('quantity')
                       ->where('order_id', $orderId)
                       ->first();
        
        return $result['quantity'] ?? 0;
    }

    // Insert single order item
    public function insertOrderItem($data)
    {
        // Pastikan data dalam format yang benar
        $insertData = [
            'order_id' => (int) $data['order_id'],
            'book_id' => (int) $data['book_id'],
            'quantity' => (int) $data['quantity'],
            'price' => (float) $data['price']
        ];
        
        log_message('info', 'OrderItemModel insertOrderItem data: ' . json_encode($insertData));
        
        return $this->insert($insertData);
    }

    // ADDED: Insert multiple order items using batch insert
    public function insertMultipleOrderItems($orderItemsData)
    {
        if (empty($orderItemsData) || !is_array($orderItemsData)) {
            log_message('error', 'OrderItemModel insertMultipleOrderItems: Invalid data provided');
            return false;
        }

        try {
            // Prepare data with type casting
            $preparedData = [];
            foreach ($orderItemsData as $item) {
                $preparedData[] = [
                    'order_id' => (int) $item['order_id'],
                    'book_id' => (int) $item['book_id'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            log_message('info', 'OrderItemModel insertMultipleOrderItems prepared data: ' . json_encode($preparedData));
            
            // Use insertBatch for better performance
            $result = $this->insertBatch($preparedData);
            
            if ($result) {
                log_message('info', 'OrderItemModel insertMultipleOrderItems: Successfully inserted ' . count($preparedData) . ' items');
                return true;
            } else {
                log_message('error', 'OrderItemModel insertMultipleOrderItems: Failed to insert items');
                return false;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'OrderItemModel insertMultipleOrderItems exception: ' . $e->getMessage());
            return false;
        }
    }

    // Insert order item dengan type casting
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            // Type cast data untuk memastikan format yang benar
            if (isset($data['order_id'])) {
                $data['order_id'] = (int) $data['order_id'];
            }
            if (isset($data['book_id'])) {
                $data['book_id'] = (int) $data['book_id'];
            }
            if (isset($data['quantity'])) {
                $data['quantity'] = (int) $data['quantity'];
            }
            if (isset($data['price'])) {
                $data['price'] = (float) $data['price'];
            }
            
            log_message('info', 'OrderItemModel insert data: ' . json_encode($data));
        }
        
        return parent::insert($data, $returnID);
    }

    // Get total amount for an order
    public function getOrderTotal($orderId)
    {
        $orderId = (int) $orderId; // Type cast
        
        $result = $this->selectSum('quantity * price', 'total')
                       ->where('order_id', $orderId)
                       ->first();
        
        return (float) ($result['total'] ?? 0);
    }
}