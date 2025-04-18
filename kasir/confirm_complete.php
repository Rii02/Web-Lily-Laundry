<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

include '../config/config.php'; // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    // Ambil order_id dari POST
    $order_id = $_POST['order_id'];
    $date_finished = isset($_POST['date_finished']) ? $_POST['date_finished'] : '';

    // Validasi date_finished tidak boleh kosong
    if (empty($date_finished)) {
        $_SESSION['error_message'] = "Tanggal selesai harus diisi.";
        header("Location: confirmation.php");
        exit;
    }

    // Pastikan koneksi ke database aktif
    if (!$conn) {
        $_SESSION['error_message'] = "Koneksi database gagal.";
        header("Location: confirmation.php");
        exit;
    }

    // Ambil data order termasuk type_id dari tabel orders dan type dari tabel type
    $query = "SELECT o.id, o.nama, o.type AS type_id, t.type_laundry AS type, o.weight, o.date_received, o.amount
              FROM orders o
              JOIN type t ON o.type = t.id
              WHERE o.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error_message'] = "Data pesanan tidak ditemukan atau tidak memiliki tipe laundry yang valid.";
        header("Location: confirmation.php");
        exit;
    }

    // Ambil hasil query
    $row = $result->fetch_assoc();
    $customer_name = $row['nama'];
    $type_id = $row['type_id']; // Ambil ID dari tabel type
    $weight = $row['weight'];
    $date_received = $row['date_received'];
    $amount = $row['amount'];

    // Ambil nilai dari form
    $pay_money = isset($_POST['pay_money']) ? $_POST['pay_money'] : 0; // Default 0 jika tidak diisi
    $refund = $pay_money - $amount; // Hitung refund (bisa jadi 0 jika pembayaran pas)

    // Simpan ke tabel report dengan ID type, bukan hanya nama type
    $stmt = $conn->prepare("INSERT INTO report (id, nama, berat, type, date_received, harga, date_finished, pay_money, refund) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        $_SESSION['error_message'] = "Terjadi kesalahan dalam persiapan query.";
        header("Location: confirmation.php");
        exit;
    }

    $stmt->bind_param("isidsssdd", $order_id, $customer_name, $weight, $type_id, $date_received, $amount, $date_finished, $pay_money, $refund);

    if ($stmt->execute()) {
        // Hapus data dari tabel orders setelah berhasil tersimpan
        $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $delete_stmt->bind_param("i", $order_id);
        $delete_stmt->execute();

        // Redirect ke halaman report dengan pesan sukses
        $_SESSION['success_message'] = "Pesanan berhasil dikonfirmasi dan dipindahkan ke laporan.";
        header("Location: report.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Gagal menyimpan data ke laporan.";
        header("Location: confirmation.php");
        exit;
    }
}
?>
