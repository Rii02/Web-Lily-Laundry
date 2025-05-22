<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../config/config.php';

// Periksa apakah ID type sudah diterima
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: type.php?error=Invalid ID");
    exit;
}

$id = intval($_GET['id']);

// Ambil data type berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM type WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$type = $result->fetch_assoc();
$stmt->close();

if (!$type) {
    header("Location: type.php?error=Data not found");
    exit;
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_laundry = trim($_POST['type_laundry']);
    $price = trim($_POST['price']);
    
    if (!empty($type_laundry) && !empty($price) && is_numeric($price)) {
        $stmt = $conn->prepare("UPDATE type SET type_laundry = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sii", $type_laundry, $price, $id);
        
        if ($stmt->execute()) {
            header("Location: type.php?success=Data updated successfully");
            exit;
        } else {
            $error = "Gagal memperbarui data!";
        }
        $stmt->close();
    } else {
        $error = "Harap isi semua kolom dengan benar!";
    }
}
?>

<html lang="id">
<head>
    <title>Edit Type Laundry - Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="flex h-screen bg-gray-100">
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

    <div class="w-5/6 flex flex-col">
        <header class="bg-gradient-to-r from-blue-700 to-blue-400 shadow-lg p-5 flex justify-between items-center rounded-b-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-tshirt text-white text-3xl"></i>
                <h1 class="text-2xl font-bold text-white">Edit Type Laundry</h1>
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

        <main class="p-4 flex-grow">
            <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Form Edit Type Laundry</h2>
                <?php if (isset($error)): ?>
                    <div class="mb-4 p-3 bg-red-500 text-white rounded">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2" for="type-laundry">Type</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded"
                               id="type-laundry" type="text" name="type_laundry" required value="<?php echo htmlspecialchars($type['type_laundry']); ?>"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2" for="price">Price/Kg</label>
                        <input class="w-full border-2 border-gray-300 focus:border-blue-600 outline-none py-2 px-4 rounded"
                               id="price" type="number" name="price" required value="<?php echo htmlspecialchars($type['price']); ?>"/>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">Update</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>