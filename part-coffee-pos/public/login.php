<?php
session_start();
require_once __DIR__ . '/../src/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Login Part Coffee</h1>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username" class="block mb-2 font-semibold">Username</label>
            <input type="text" id="username" name="username" required class="w-full p-2 border border-gray-300 rounded mb-4" />
            <label for="password" class="block mb-2 font-semibold">Password</label>
            <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded mb-6" />
            <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700 transition">Masuk</button>
        </form>
    </div>
</body>
</html>
