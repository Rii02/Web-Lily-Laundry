<?php
session_start();
include '../config/config.php'; // Koneksi database

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$query = "SELECT report.*, type.type_laundry FROM report 
          JOIN type ON report.type = type.id";
if (!empty($start_date) && !empty($end_date)) {
    $query .= " WHERE date_received BETWEEN '$start_date' AND '$end_date'";
}
$query .= " ORDER BY date_finished DESC";
$result = $conn->query($query);

$data = [];
$queryChart = "SELECT DATE(date_received) as tanggal, SUM(pay_money) as pendapatan, SUM(refund) as pengeluaran 
               FROM report 
               WHERE date_received BETWEEN '$start_date' AND '$end_date'
               GROUP BY tanggal
               ORDER BY tanggal ASC";
$resultChart = $conn->query($queryChart);

while ($row = $resultChart->fetch_assoc()) {
    $data[] = $row;
}
$jsonData = json_encode($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
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
    <div class="w-5/6 flex flex-col h-screen">
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-4 flex justify-between items-center rounded-b-lg no-print">
            <div class="flex items-center space-x-3">
                <i class="fas fa-chart-line text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Report Dashboard</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-white text-lg font-medium">
                    <i class="fas fa-user-circle mr-2"></i>
                    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
                <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center shadow-lg transition duration-300 transform hover:scale-110">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 flex-grow overflow-auto">
            <div class="flex justify-between items-center mb-4 no-print">
                <form class="flex space-x-4" method="GET">
                    <div>
                        <label class="block text-gray-700" for="start-date">Start Date</label>
                        <input class="w-full border-b-2 border-gray-300 focus:border-blue-600 outline-none py-2"
                            id="start-date" type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="end-date">End Date</label>
                        <input class="w-full border-b-2 border-gray-300 focus:border-blue-600 outline-none py-2"
                            id="end-date" type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>"/>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-lg flex items-center gap-2 transition duration-300">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>
                <div>
                    <button onclick="printReport()" class="bg-green-500 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg shadow-lg flex items-center gap-2 transition duration-300">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <!-- Area yang akan dicetak -->
            <div id="print-area">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold uppercase text-gray-800">Laporan Keuangan Lily Laundry</h1>
                    <?php if (!empty($start_date) && !empty($end_date)): ?>
                        <p class="text-gray-600 mt-2">Periode: <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></p>
                    <?php endif; ?>
                </div>

                <div class="bg-white p-4 rounded shadow mb-4">
                    <h2 class="text-xl font-bold mb-4">Detail Transaksi</h2>
                    <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-3 py-2 border">Customer Name</th>
                                <th class="px-3 py-2 border">Weight (Kg)</th>
                                <th class="px-3 py-2 border">Type</th>
                                <th class="px-3 py-2 border">Date Received</th>
                                <th class="px-3 py-2 border">Date Finished</th>
                                <th class="px-3 py-2 border">Amount</th>
                                <th class="px-3 py-2 border">Pay Money</th>
                                <th class="px-3 py-2 border">Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='border'>";
                                    echo "<td class='px-3 py-2 border'>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td class='px-3 py-2 border'>" . htmlspecialchars($row['berat']) . " Kg</td>";
                                    echo "<td class='px-3 py-2 border'>" . htmlspecialchars($row['type_laundry']) . "</td>";
                                    echo "<td class='px-3 py-2 border'>" . htmlspecialchars($row['date_received']) . "</td>";
                                    echo "<td class='px-3 py-2 border'>" . htmlspecialchars($row['date_finished']) . "</td>";
                                    echo "<td class='px-3 py-2 border'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                    echo "<td class='px-3 py-2 border'>Rp " . number_format($row['pay_money'], 0, ',', '.') . "</td>";
                                    echo "<td class='px-3 py-2 border'>Rp " . number_format($row['refund'], 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center py-4'>Tidak ada data tersedia.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-4">Grafik Pendapatan & Pengeluaran</h2>
                    <canvas id="chartPendapatan" style="max-height: 250px; max-width: 400px;"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        function printReport() {
            window.print();
        }

        const data = <?php echo $jsonData; ?>;
        const labels = data.map(row => row.tanggal);
        const pendapatan = data.map(row => row.pendapatan);
        const pengeluaran = data.map(row => row.pengeluaran);

        new Chart(document.getElementById('chartPendapatan'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: pendapatan,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        barThickness: 20
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaran,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        barThickness: 20
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
