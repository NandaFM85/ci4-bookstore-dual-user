<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .invoice-container { box-shadow: none; border: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background: #f5f5f5;
        }
        
        .print-header {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .print-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }
        
        .print-btn:hover {
            background: #0056b3;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .back-btn:hover {
            background: #545b62;
            color: white;
            text-decoration: none;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .invoice-header {
            background: #f8f9fa;
            padding: 30px;
            border-bottom: 3px solid #007bff;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .company-details {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.6;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin: 30px 0;
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .detail-section {
            background: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .detail-section h4 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        
        .detail-section p {
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .detail-section strong {
            color: #2c3e50;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .items-table th,
        .items-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 14px;
        }
        
        .items-table th {
            background: #007bff;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .items-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 16px;
            padding: 5px 0;
        }
        
        .total-row.final {
            font-weight: bold;
            font-size: 20px;
            color: #28a745;
            padding: 15px 0 5px 0;
            border-top: 2px solid #28a745;
            margin-top: 15px;
        }
        
        .invoice-footer {
            background: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 14px;
            line-height: 1.6;
            margin: 5px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending_payment { 
            background: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeaa7;
        }
        .status-pending_verification { 
            background: #e2e3e5; 
            color: #383d41;
            border: 1px solid #c6c8ca;
        }
        .status-approved { 
            background: #d4edda; 
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-on_shipping { 
            background: #cce5ff; 
            color: #004085;
            border: 1px solid #99d6ff;
        }
        .status-completed { 
            background: #d1ecf1; 
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .status-cancelled { 
            background: #f8d7da; 
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .book-info {
            font-weight: 500;
        }
        
        .price-cell {
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="print-header no-print">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <a href="<?= base_url('user/orders') ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name"><?= esc($company['name']) ?></div>
                <div class="company-details">
                    <?= esc($company['address']) ?><br>
                    Tel: <?= esc($company['phone']) ?> | Email: <?= esc($company['email']) ?><br>
                    Website: <?= esc($company['website']) ?>
                </div>
            </div>
            
            <div class="invoice-title">INVOICE</div>
            
            <div class="invoice-details">
                <div class="detail-section">
                    <h4><i class="fas fa-receipt"></i> Informasi Pesanan</h4>
                    <p><strong>No. Pesanan:</strong> <?= esc($order['order_number']) ?></p>
                    <p><strong>Tanggal Pesanan:</strong> <?= date('d F Y, H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Status:</strong> 
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
                    </p>
                    <?php if (!empty($order['payment_method_name'])): ?>
                    <p><strong>Metode Pembayaran:</strong> <?= esc($order['payment_method_name']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="detail-section">
                    <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> <?= esc($order['fullname'] ?: $order['username']) ?></p>
                    <p><strong>Email:</strong> <?= esc($order['email']) ?></p>
                    <p><strong>Username:</strong> <?= esc($order['username']) ?></p>
                    <?php if (!empty($order['address'])): ?>
                    <p><strong>Alamat:</strong><br><?= nl2br(esc($order['address'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Buku</th>
                        <th>Penulis</th>
                        <th style="width: 120px;">ISBN</th>
                        <th style="width: 60px;" class="text-center">Qty</th>
                        <th style="width: 100px;" class="text-right">Harga</th>
                        <th style="width: 120px;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($order['items'] as $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <div class="book-info">
                                <?= esc($item['title']) ?>
                            </div>
                        </td>
                        <td><?= esc($item['author']) ?></td>
                        <td><?= esc($item['isbn']) ?></td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-right price-cell">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="text-right price-cell">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span><strong>Total Item:</strong></span>
                    <span><?= count($order['items']) ?> item(s)</span>
                </div>
                <div class="total-row">
                    <span><strong>Total Quantity:</strong></span>
                    <span><?= array_sum(array_column($order['items'], 'quantity')) ?></span>
                </div>
                <div class="total-row final">
                    <span>TOTAL PEMBAYARAN:</span>
                    <span>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="footer-text">
                Invoice ini digenerate secara otomatis pada <?= date('d F Y, H:i') ?>
            </div>
            <div class="footer-text">
                Terima kasih atas kepercayaan Anda berbelanja di <?= esc($company['name']) ?>
            </div>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>