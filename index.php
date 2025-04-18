<?php
session_start();
include 'config/config.php';  // Jika file pemanggil ada di subfolder

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username='$username' OR email='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/home.php");
            } elseif ($user['role'] == 'kasir') {
                header("Location: kasir/home.php");
            }
            exit;
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username atau email tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lily Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,rgb(67, 107, 183),rgb(60, 114, 208));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-field:focus {
            box-shadow: 0 0 10px #4F46E5;
        }
        .btn-hover {
            transition: all 0.3s ease-in-out;
        }
        .btn-hover:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 15px rgba(79, 70, 229, 0.7);
        }
    </style>
</head>
<body>
    <div class="w-full max-w-5xl bg-white shadow-2xl rounded-3xl overflow-hidden flex scale-95">
        <!-- Left Section -->
        <div class="w-1/2 bg-blue-700 flex flex-col justify-center items-center text-white p-12">
            <h1 class="text-5xl font-bold mb-6">LILY LAUNDRY</h1> 
            <img id="washing-machine" src="assets/Logo.PNG" alt="Logo Laundry" class="w-40 h-40 mb-6"/>
            <p class="text-center text-lg">Spesialis Sikat Pelakor</p>
            <p class="text-center text-lg">(Pembersih Laundryan Kotor)</p>
        </div>

        <!-- Right Section -->
        <div class="w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-4xl font-semibold text-gray-800 mb-6">Welcome Back</h2>
            <p class="text-gray-600 mb-8 text-lg">Login to access your dashboard</p>
            <form method="POST" onsubmit="showLoading(event)">
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2 text-lg">Username or Email</label>
                    <input name="username" class="w-full px-5 py-4 border rounded-xl input-field focus:ring-2 focus:ring-blue-400 outline-none transition" type="text" required/>
                </div>
                <div class="mb-6 relative">
                    <label class="block text-gray-700 font-medium mb-2 text-lg">Password</label>
                    <input id="password" name="password" class="w-full px-5 py-4 border rounded-xl input-field focus:ring-2 focus:ring-blue-400 outline-none transition" type="password" required/>
                    <button type="button" onclick="togglePassword()" class="absolute top-12 right-4 text-gray-500 hover:text-gray-700 text-lg">👁</button>
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white py-4 rounded-xl font-semibold shadow-lg transition duration-300 btn-hover text-lg">
                    Login
                </button>
                <div id="loading" class="hidden text-center text-blue-700 mt-4 text-lg">Loading...</div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passField = document.getElementById("password");
            passField.type = (passField.type === "password") ? "text" : "password";
        }

        function showLoading(event) {
            event.preventDefault();
            document.getElementById("loading").classList.remove("hidden");
            setTimeout(() => event.target.submit(), 2000);
        }
        // Animasi gambar mesin cuci
        gsap.to("#washing-machine", {
            y: 10, // Gerakan naik turun
            repeat: -1, // Animasi berjalan terus-menerus
            yoyo: true, // Balik ke posisi awal setelah turun
            duration: 0.8, // Kecepatan animasi
            ease: "power1.inOut"
        });

        gsap.to("#washing-machine", {
            rotation: 5, // Rotasi sedikit
            repeat: -1, // Berulang terus
            yoyo: true, // Balik ke posisi awal
            duration: 0.5, // Kecepatan animasi
            ease: "power1.inOut"
        });

        // Animasi GSAP
        gsap.from(".scale-95", { duration: 1, scale: 0.8, opacity: 0, ease: "elastic.out(1, 0.5)" });
        gsap.from(".w-1/2", { duration: 1, x: 100, opacity: 0, ease: "power2.out", stagger: 0.2 });
    </script>
</body>
</html>
