<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../config/config.php';

// Ambil data dari database
$sql = "SELECT id, username, email, password, role FROM user";
$result = $conn->query($sql);
?>
<html lang="id">
<head>
    <title>Officer Lily Laundry</title>
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
                <i class="fas fa-user-shield text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Officer Panel</h1>
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
            <!-- Action Buttons -->
            <div class="flex space-x-4 mb-4">
                <a href="add_officer.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Add Officer
                </a>
            </div>

            <!-- Officers Table -->
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">List of Officers</h2>
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Username</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Password</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="px-2 py-2 border w-1/6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($row['password']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td class="px-4 py-2 border text-center flex justify-center space-x-1">
                                        <a href="edit_officer.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <a href="delete_officer.php?id=<?php echo $row['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg flex items-center"
                                        onclick="return confirm('Are you sure?');">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </a>
                                </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
<?php
$conn->close();
?>
