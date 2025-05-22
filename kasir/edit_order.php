<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}
include '../config/config.php';

$error = "";
$order = null;
$type_options = [];

// Ambil daftar tipe laundry dan harga per kg
$result = $conn->query("SELECT id, type_laundry, price FROM type");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $type_options[] = $row;
    }
}

// Ambil data order berdasarkan ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
}

if (!$order) {
    die("Order tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $no_tlp = trim($_POST['no_tlp']);
    $prioritas = trim($_POST['prioritas']);
    $weight = trim($_POST['weight']);
    $type = trim($_POST['type']);
    $date_received = trim($_POST['date_received']);
    $amount = trim($_POST['amount']);

    if (empty($nama) || empty($no_tlp) || empty($prioritas) || empty($weight) || empty($type) || empty($date_received) || empty($amount)) {
        $error = "Semua field harus diisi!";
    } elseif (!is_numeric($weight) || $weight <= 0) {
        $error = "Berat harus berupa angka positif!";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = "Jumlah harus berupa angka positif!";
    } else {
        $stmt = $conn->prepare("UPDATE orders SET nama=?, no_tlp=?, prioritas=?, weight=?, type=?, date_received=?, amount=? WHERE id=?");
        $stmt->bind_param("ssissssi", $nama, $no_tlp, $prioritas, $weight, $type, $date_received, $amount, $order_id);
        
        if ($stmt->execute()) {
            header("Location: home.php?success=Order berhasil diperbarui");
            exit;
        } else {
            $error = "Gagal memperbarui order. Silakan coba lagi.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<html lang="id">
<head>
    <title>Edit Order - Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-1/6 bg-gradient-to-b from-blue-700 to-blue-500 text-white flex flex-col p-4 shadow-lg min-h-screen">
        <!-- Logo Section -->
        <div class="flex items-center space-x-3 mb-6">
            <img src="../assets/Logo1.PNG" alt="Logo" class="w-12 h-12 rounded-full shadow-md">
            <h1 class="text-xl font-bold tracking-wide">Lily Laundry</h1>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex flex-col space-y-2 flex-grow">
            <a class="flex items-center space-x-3 bg-blue-700 hover:bg-blue-900 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="home.php">
                <i class="fas fa-home text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-700 hover:bg-blue-900 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="pengeluaran_lain.php">
                <i class="fas fa-wallet text-lg"></i>
                <span class="font-medium">Pengeluaran Lain</span>
            </a>
            <a class="flex items-center space-x-3 bg-blue-700 hover:bg-blue-900 p-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-md" href="report.php">
                <i class="fas fa-chart-line text-lg"></i>
                <span class="font-medium">Report</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 flex flex-col h-screen">
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-5 flex justify-between items-center rounded-b-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-edit text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Edit Order</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-white text-lg font-medium flex items-center">
                    <i class="fas fa-user-circle text-white text-xl mr-2"></i>
                    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
                <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center shadow-lg transition duration-300 transform hover:scale-110">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </header>

        <main class="p-4 flex-grow overflow-auto">
            <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Form Edit Order</h2>
                <?php if (!empty($error)): ?>
                    <div class="mb-4 p-3 bg-red-500 text-white rounded"> <?php echo $error; ?> </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Customer Name</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="text" name="nama" value="<?php echo htmlspecialchars($order['nama']); ?>" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="text" name="no_tlp" value="<?php echo htmlspecialchars($order['no_tlp']); ?>" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Priority</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="number" name="prioritas" value="<?php echo $order['prioritas']; ?>" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Weight (Kg)</label>
                        <input id="weight" class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="number" name="weight" value="<?php echo $order['weight']; ?>" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Type</label>
                        <select id="type" class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" name="type" required>
                            <?php foreach ($type_options as $type) : ?>
                                <option value="<?php echo $type['id']; ?>" data-price="<?php echo $type['price']; ?>" <?php echo ($type['id'] == $order['type']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type['type_laundry']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Amount (Rp)</label>
                        <input id="amount" class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="number" name="amount" value="<?php echo $order['amount']; ?>" required readonly />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Date Received</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded" type="date" name="date_received" value="<?php echo isset($order['date_received']) ? $order['date_received'] : ''; ?>" required />
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">Update Order</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function calculateAmount() {
            var weight = parseFloat($("#weight").val());
            var price = parseFloat($("#type option:selected").data("price"));
            if (!isNaN(weight) && !isNaN(price)) {
                $("#amount").val(weight * price);
            }
        }
        $("#weight, #type").on("input change", calculateAmount);
    </script>
</body>
</html>
