<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

include '../config/config.php'; // Koneksi ke database

$orders = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_orders'])) {
    $selected_orders = $_POST['selected_orders'];
    $ids = implode(",", array_map('intval', $selected_orders));
    $query = "SELECT orders.*, type.type_laundry FROM orders JOIN type ON orders.type = type.id WHERE orders.id IN ($ids)";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
} else {
    header("Location: home.php");
    exit;
}
?>

<html lang="id">
<head>
    <title>Confirmation of Complete - Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
                <i class="fas fa-check-circle text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Confirmation of Complete</h1>
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
                <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Form Confirmation of Complete</h2>
                <?php foreach ($orders as $order): ?>
                    <form method="POST" action="confirm_complete.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Customer Name</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="text" name="customer_name" value="<?php echo htmlspecialchars($order['nama']); ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Priority</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="number" name="prioritas" value="<?php echo $order['prioritas']; ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Weight (Kg)</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="number" name="weight" value="<?php echo $order['weight']; ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Type</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="text" name="type" value="<?php echo htmlspecialchars($order['type_laundry']); ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Date Received</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="date" name="date_received" value="<?php echo $order['date_received']; ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Amount (Rp)</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="number" name="amount" value="<?php echo $order['amount']; ?>" readonly />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Date Finished</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="date" name="date_finished" required />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Pay Money (Rp)</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded" type="number" id="pay_money" name="pay_money" required oninput="calculateRefund()" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Refund (Rp)</label>
                            <input class="w-full border-2 border-gray-300 py-2 px-4 rounded bg-gray-100" type="number" id="refund" name="refund" readonly />
                        </div>

                        <script>
                            function calculateRefund() {
                                let payMoney = document.getElementById('pay_money').value;
                                let amount = <?php echo $order['amount']; ?>;
                                let refund = payMoney - amount;
                                document.getElementById('refund').value = refund >= 0 ? refund : 0;
                            }
                        </script>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Confirm Complete</button>
                    </form>

                <hr class="my-6">
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
