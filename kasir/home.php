<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

include '../config/config.php'; // Koneksi ke database
?>
<html>
<head>
    <title>Lily Laundry Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>

    <script>
        function confirmDelete() {
            return confirm("Hapus order yang dipilih?");
        }
    </script>
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
        <nav class="flex flex-col space-y-2">
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
    <div class="w-5/6 flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-5 flex justify-between items-center rounded-b-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-tachometer-alt text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-white text-lg font-medium flex items-center">
                    <i class="fas fa-user-circle text-white text-xl mr-2"></i>
                    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
                <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center shadow-lg transition duration-300 transform hover:scale-110">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 flex-grow">
            <!-- Action Buttons -->
            <form method="POST" action="confirmation.php">
            <div class="flex space-x-4 mb-4">
                <a href="add_order.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Add Order
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-300">
                    <i class="fas fa-check mr-2"></i> Confirmation of Complete
                </button>
            </div>

                <!-- Recent Orders -->
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 border">Select</th>
                                <th class="px-4 py-2 border">Customer Name</th>
                                <th class="px-4 py-2 border">Phone Number</th> <!-- Tambahan kolom No Telepon -->
                                <th class="px-4 py-2 border">Priority</th>
                                <th class="px-4 py-2 border">Weight (Kg)</th>
                                <th class="px-4 py-2 border">Type</th>
                                <th class="px-4 py-2 border">Date Received</th>
                                <th class="px-4 py-2 border">Amount (Rp)</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT orders.*, type.type_laundry FROM orders JOIN type ON orders.type = type.id ORDER BY orders.id DESC";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='border'>";
                                    echo "<td class='px-4 py-2 border text-center'><input type='checkbox' name='selected_orders[]' value='" . htmlspecialchars($row['id']) . "'></td>";
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['no_tlp']) . "</td>"; // Menampilkan No Telepon
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['prioritas']) . "</td>";
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['weight']) . "</td>";
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['type_laundry']) . "</td>";
                                    echo "<td class='px-4 py-2 border'>" . htmlspecialchars($row['date_received']) . "</td>";
                                    echo "<td class='px-4 py-2 border'>" . number_format($row['amount'], 0, ',', '.') . "</td>";
                                    echo "<td class='px-4 py-2 border flex justify-center space-x-2'>
                                            <a href='edit_order.php?id=" . htmlspecialchars($row['id']) . "' class='bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg flex items-center shadow-md transition duration-300'>
                                                <i class='fas fa-edit mr-1'></i> Edit
                                            </a>
                                            <a href='delete_order.php?id=" . htmlspecialchars($row['id']) . "' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg flex items-center shadow-md transition duration-300'
                                                onclick='return confirm(\"Hapus order ini?\")'>
                                                <i class='fas fa-trash-alt mr-1'></i> Delete
                                            </a>
                                            <a href='print_receipt.php?id=" . htmlspecialchars($row['id']) . "' target='_blank' class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg flex items-center shadow-md transition duration-300'>
                                                <i class='fas fa-print mr-1'></i> Cetak Struk
                                            </a>
                                        </td>";
                                    echo "</tr>";
                                }  
                            } else {
                                echo "<tr><td colspan='9' class='text-center py-4'>Belum ada order</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
