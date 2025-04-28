<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../config/database.php';
check_access([1]); // Owner only

// Fetch activity logs with user info
$stmt = $pdo->query("SELECT activity_logs.*, users.username FROM activity_logs LEFT JOIN users ON activity_logs.user_id = users.id ORDER BY activity_time DESC LIMIT 100");
$logs = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Activity Logs - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Activity Logs - Part Coffee</h1>
        <a href="dashboard.php" class="hover:underline">Kembali ke Dashboard</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <section>
            <h2 class="text-xl font-semibold mb-4">Log Aktivitas Terbaru</h2>
            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-yellow-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Waktu</th>
                        <th class="p-3 text-left">Pengguna</th>
                        <th class="p-3 text-left">Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($logs) === 0): ?>
                        <tr><td colspan="3" class="p-3 text-center">Belum ada aktivitas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= htmlspecialchars($log['activity_time']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($log['username'] ?? 'Guest') ?></td>
                                <td class="p-3"><?= htmlspecialchars($log['activity']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="bg-yellow-600 text-white p-4 text-center">
        &copy; 2024 Part Coffee. Semua hak cipta dilindungi.
    </footer>
</body>
</html>
