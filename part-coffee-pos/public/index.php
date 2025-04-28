<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Part Coffee - Sistem Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center">
        <img src="../assets/images/part-coffee-logo.png" alt="Logo Part Coffee" class="h-12 w-auto mr-4" />
        <h1 class="text-2xl font-bold">Part Coffee - Sistem Pemesanan</h1>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <h2 class="text-xl font-semibold mb-4">Selamat datang di Part Coffee</h2>
        <p>Silakan <a href="login.php" class="text-yellow-600 font-semibold hover:underline">login</a> untuk mengakses sistem.</p>
    </main>

    <footer class="bg-yellow-600 text-white p-4 text-center">
        &copy; 2024 Part Coffee. Semua hak cipta dilindungi.
    </footer>
</body>
</html>
