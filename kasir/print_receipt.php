<?php
include '../config/config.php'; // Koneksi ke database

if (!isset($_GET['id'])) {
    die("ID order tidak ditemukan.");
}

$id = intval($_GET['id']);
$query = "SELECT orders.*, type.type_laundry FROM orders JOIN type ON orders.type = type.id WHERE orders.id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Order tidak ditemukan.");
}

$order = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - Lily Laundry</title>
    <script>
        function printPage() {
            window.print();
        }
    </script>
    <style>
        body { font-family: Arial, sans-serif; }
        .receipt-container { width: 300px; padding: 20px; border: 1px solid #000; }
        .receipt-title { text-align: center; font-size: 18px; font-weight: bold; }
        .receipt-info { margin-bottom: 12px; }
        .receipt-footer { text-align: center; margin-top: 20px; font-size: 13px; font-weight: bold;}
        .note { text-align: left; font-size: 9px; font-weight: normal; } 
        
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body onload="printPage()">
    <div class="receipt-container">
        <div class="receipt-title">Lily Laundry</div>
        <div class="receipt-info">
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['nama']); ?></p>
            <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($order['date_received']); ?></p>
            <p><strong>Jenis Laundry:</strong> <?php echo htmlspecialchars($order['type_laundry']); ?></p>
            <p><strong>Berat:</strong> <?php echo htmlspecialchars($order['weight']); ?> Kg</p>
            <p><strong>Prioritas:</strong> <?php echo htmlspecialchars($order['prioritas']); ?></p>
            <p><strong>Total:</strong> Rp <?php echo number_format($order['amount'], 0, ',', '.'); ?></p>
        </div>
        <div class="receipt-footer">Terima kasih telah menggunakan layanan kami!</div>
        <div class="receipt-footer note">NB: Bawa struk ini jika ingin melakukan pengambilan</div>
    </div>
    <button class="btn-print" onclick="printPage()">Cetak Struk</button>
</body>
</html>