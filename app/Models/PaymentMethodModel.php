<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'type',
        'account_number',
        'account_name',
        'description',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'type' => 'required|in_list[bank,ewallet,other]',
        'account_number' => 'required|max_length[50]',
        'account_name' => 'required|max_length[100]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama metode pembayaran harus diisi',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'type' => [
            'required' => 'Tipe pembayaran harus diisi',
            'in_list' => 'Tipe pembayaran harus bank, ewallet, atau other'
        ],
        'account_number' => [
            'required' => 'Nomor rekening harus diisi',
            'max_length' => 'Nomor rekening maksimal 50 karakter'
        ],
        'account_name' => [
            'required' => 'Nama pemilik rekening harus diisi',
            'max_length' => 'Nama pemilik rekening maksimal 100 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Get active payment methods
    public function getActivePaymentMethods()
    {
        return $this->where('is_active', 1)
                   ->orderBy('type', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    // Get payment methods by type
    public function getPaymentMethodsByType($type)
    {
        return $this->where('type', $type)
                   ->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    // Get payment method details
    public function getPaymentMethodDetails($id)
    {
        $id = (int) $id; // Type cast
        return $this->find($id);
    }

    // Check if payment method is active
    public function isPaymentMethodActive($id)
    {
        $id = (int) $id; // Type cast
        $method = $this->find($id);
        return $method && $method['is_active'] == 1;
    }

    // Toggle payment method status
    public function toggleStatus($id)
    {
        $id = (int) $id; // Type cast
        $method = $this->find($id);
        
        if ($method) {
            $newStatus = $method['is_active'] == 1 ? 0 : 1;
            return $this->update($id, ['is_active' => $newStatus]);
        }
        
        return false;
    }

    // Get payment method statistics
    public function getPaymentMethodStats()
    {
        return [
            'total_methods' => $this->countAll(),
            'active_methods' => $this->where('is_active', 1)->countAllResults(),
            'bank_methods' => $this->where('type', 'bank')->where('is_active', 1)->countAllResults(),
            'ewallet_methods' => $this->where('type', 'ewallet')->where('is_active', 1)->countAllResults()
        ];
    }
}