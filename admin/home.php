<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../config/config.php';  // Jika file pemanggil ada di subfolder

?>
<html lang="id">
<head>
    <title>Admin Dashboard - Lily Laundry</title>
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
        <nav class="flex flex-col space-y-2">
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
    <div class="w-5/6 flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-5 flex justify-between items-center rounded-b-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-tachometer-alt text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
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

        <!-- Content -->
        <main class="p-4 flex-grow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Laundry Type Card -->
                <div class="bg-white p-6 rounded shadow-lg flex flex-col items-center">
                    <i class="fas fa-tshirt text-blue-600 text-4xl mb-4"></i>
                    <h2 class="text-xl font-bold mb-2">Laundry Type</h2>
                    <p class="text-gray-600 mb-4 text-center">Manage different types of laundry services offered.</p>
                    <a href="type.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Go to Laundry Type</a>
                </div>

                <!-- Add Officer Card -->
                <div class="bg-white p-6 rounded shadow-lg flex flex-col items-center">
                    <i class="fas fa-users text-blue-600 text-4xl mb-4"></i>
                    <h2 class="text-xl font-bold mb-2">Add Officer</h2>
                    <p class="text-gray-600 mb-4 text-center">Add new officers to manage the laundry services.</p>
                    <a href="officer.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Go to Add Officer</a>
                </div>

                <!-- Report Card -->
                <div class="bg-white p-6 rounded shadow-lg flex flex-col items-center">
                    <i class="fas fa-chart-line text-blue-600 text-4xl mb-4"></i>
                    <h2 class="text-xl font-bold mb-2">Report</h2>
                    <p class="text-gray-600 mb-4 text-center">View and generate reports of the laundry services.</p>
                    <a href="report.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Go to Report</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>