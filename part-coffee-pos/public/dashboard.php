<?php
require_once __DIR__ . '/../src/auth.php';
check_access([1]); // Role ID 1 = Owner

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Owner - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Dashboard Owner - Part Coffee</h1>
        <a href="logout.php" class="hover:underline">Logout</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Statistik Penjualan</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-semibold mb-2">Penjualan Harian</h3>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-semibold mb-2">Penjualan Mingguan</h3>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-semibold mb-2">Penjualan Bulanan</h3>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-semibold mb-2">Performa Karyawan</h3>
                    <p class="text-2xl font-bold">0</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-4">Menu Navigasi</h2>
            <ul class="space-y-2">
                <li><a href="menu.php" class="text-yellow-600 hover:underline font-semibold">Manajemen Menu</a></li>
                <li><a href="karyawan.php" class="text-yellow-600 hover:underline font-semibold">Manajemen Karyawan</a></li>
                <li><a href="laporan.php" class="text-yellow-600 hover:underline font-semibold">Laporan Keuangan</a></li>
                <li><a href="promosi.php" class="text-yellow-600 hover:underline font-semibold">Pengaturan Promosi</a></li>
                <li><a href="logs.php" class="text-yellow-600 hover:underline font-semibold">Activity Logs</a></li>
                <li><a href="role_management.php" class="text-yellow-600 hover:underline font-semibold">Role Management</a></li>
                <li><a href="analitik_promosi.php" class="text-yellow-600 hover:underline font-semibold">Analitik Promosi</a></li>
            </ul>
        </section>
    </main>

    <footer class="bg-yellow-600 text-white p-4 text-center">
        &copy; 2024 Part Coffee. Semua hak cipta dilindungi.
    </footer>
</body>
</html>
