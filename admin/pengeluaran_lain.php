<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../config/config.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pengeluaran']);
    $tanggal = $_POST['tanggal'];
    $jumlah = intval($_POST['jumlah']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    if ($nama && $tanggal && $jumlah > 0) {
        $sql = "INSERT INTO pengeluaran_lain (nama_pengeluaran, tanggal, jumlah, keterangan) 
                VALUES ('$nama', '$tanggal', $jumlah, '$keterangan')";

        if ($conn->query($sql) === TRUE) {
            $success = "Pengeluaran berhasil ditambahkan.";
        } else {
            $error = "Gagal menambahkan data: " . $conn->error;
        }
    } else {
        $error = "Harap lengkapi semua data dengan benar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengeluaran Lain - Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-1/6 bg-gradient-to-b from-blue-700 to-blue-500 text-white flex flex-col p-4 shadow-lg min-h-screen">
        <div class="flex items-center space-x-3 mb-6">
            <img src="../assets/Logo1.PNG" alt="Logo" class="w-12 h-12 rounded-full shadow-md">
            <h1 class="text-xl font-bold tracking-wide">Lily Laundry</h1>
        </div>
        <nav class="flex flex-col space-y-2 flex-grow">
            <a class="flex items-center space-x-3 bg-blue-600 hover:bg-blue-800 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="home.php">
                <i class="fas fa-tachometer-alt text-lg"></i>
                <span class="font-medium">Home</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-600 hover:bg-blue-800 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="type.php">
                <i class="fas fa-tshirt text-lg"></i>
                <span class="font-medium">Laundry Type</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-600 hover:bg-blue-800 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="officer.php">
                <i class="fas fa-users text-lg"></i>
                <span class="font-medium">Add Officer</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-600 hover:bg-blue-800 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="pengeluaran_lain.php">
                <i class="fas fa-wallet text-lg"></i>
                <span class="font-medium">Pengeluaran Lain</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-600 hover:bg-blue-800 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="report.php">
                <i class="fas fa-chart-line text-lg"></i>
                <span class="font-medium">Report</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 flex flex-col h-screen">
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-5 flex justify-between items-center rounded-b-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-money-bill-wave text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Tambah Pengeluaran Lain</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-white text-lg font-medium flex items-center">
                    <i class="fas fa-user-circle text-white text-xl mr-2"></i>
                    Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
                <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center shadow-lg transition duration-300 transform hover:scale-110">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </header>

        <main class="p-4 flex-grow overflow-auto">
            <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Form Pengeluaran Lain</h2>
                <?php if (!empty($success)): ?>
                    <div class="mb-4 p-3 bg-green-500 text-white rounded"> <?= $success; ?> </div>
                <?php elseif (!empty($error)): ?>
                    <div class="mb-4 p-3 bg-red-500 text-white rounded"> <?= $error; ?> </div>
                <?php endif; ?>

                <form method="POST" action="" onsubmit="return confirmExpense()" class="bg-white p-10 rounded-lg shadow-lg w-full max-w-lg">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nama Pengeluaran</label>
                        <input type="text" name="nama_pengeluaran" required class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" min="1" required class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3" class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">Tambah Pengeluaran</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function confirmExpense() {
            const nama = document.querySelector('input[name="nama_pengeluaran"]').value;
            const jumlah = document.querySelector('input[name="jumlah"]').value;
            const tanggal = document.querySelector('input[name="tanggal"]').value;
            const keterangan = document.querySelector('textarea[name="keterangan"]').value;

            const message = `Konfirmasi data pengeluaran:\n\n` +
                `📄 Nama: ${nama}\n💸 Jumlah: Rp ${jumlah}\n📅 Tanggal: ${tanggal}\n📝 Keterangan: ${keterangan || '(Kosong)'}\n\nApakah data sudah sesuai?`;

            return confirm(message);
        }
    </script>
</body>
</html>
