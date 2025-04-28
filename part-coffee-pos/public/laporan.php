<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../config/database.php';
check_access([1]); // Owner only

// Fetch total pendapatan
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_pendapatan FROM financial_transactions WHERE type = 'pendapatan'");
$stmt->execute();
$total_pendapatan = $stmt->fetchColumn() ?? 0;

// Fetch total pengeluaran
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_pengeluaran FROM financial_transactions WHERE type = 'pengeluaran'");
$stmt->execute();
$total_pengeluaran = $stmt->fetchColumn() ?? 0;

// Calculate profit
$profit = $total_pendapatan - $total_pengeluaran;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Keuangan - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Laporan Keuangan - Part Coffee</h1>
        <a href="dashboard.php" class="hover:underline">Kembali ke Dashboard</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-semibold mb-2">Total Pendapatan</h2>
                <p class="text-3xl font-bold text-green-600">Rp <?= number_format($total_pendapatan, 2, ',', '.') ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-semibold mb-2">Total Pengeluaran</h2>
                <p class="text-3xl font-bold text-red-600">Rp <?= number_format($total_pengeluaran, 2, ',', '.') ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-semibold mb-2">Profit</h2>
                <p class="text-3xl font-bold text-yellow-600">Rp <?= number_format($profit, 2, ',', '.') ?></p>
            </div>
        </section>
    </main>

    <footer class="bg-yellow-600 text-white p-4 text-center">
        &copy; 2024 Part Coffee. Semua hak cipta dilindungi.
    </footer>
</body>
</html>
