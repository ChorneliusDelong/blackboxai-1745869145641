<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../config/database.php';
check_access([1]); // Owner only

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $discount_percent = $_POST['discount_percent'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $active = isset($_POST['active']) ? 1 : 0;

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO promotions (title, description, discount_percent, start_date, end_date, active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $discount_percent, $start_date, $end_date, $active]);
        $message = "Promosi berhasil ditambahkan.";
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE promotions SET title = ?, description = ?, discount_percent = ?, start_date = ?, end_date = ?, active = ? WHERE id = ?");
        $stmt->execute([$title, $description, $discount_percent, $start_date, $end_date, $active, $id]);
        $message = "Promosi berhasil diperbarui.";
    }
    header("Location: promosi.php?message=" . urlencode($message));
    exit;
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM promotions WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: promosi.php?message=" . urlencode("Promosi berhasil dihapus."));
    exit;
}

// Fetch promotions
$stmt = $pdo->query("SELECT * FROM promotions ORDER BY start_date DESC");
$promotions = $stmt->fetchAll();

// If editing, fetch promotion data
$edit_promo = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM promotions WHERE id = ?");
    $stmt->execute([$id]);
    $edit_promo = $stmt->fetch();
}

$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengaturan Promosi - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Pengaturan Promosi - Part Coffee</h1>
        <a href="dashboard.php" class="hover:underline">Kembali ke Dashboard</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <?php if ($message): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4"><?= $edit_promo ? 'Edit Promosi' : 'Tambah Promosi Baru' ?></h2>
            <form method="POST" action="?action=<?= $edit_promo ? 'edit&id=' . $edit_promo['id'] : 'add' ?>" class="space-y-4 bg-white p-6 rounded shadow">
                <div>
                    <label for="title" class="block font-semibold mb-1">Judul Promosi</label>
                    <input type="text" id="title" name="title" required value="<?= $edit_promo['title'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="description" class="block font-semibold mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="w-full p-2 border border-gray-300 rounded"><?= $edit_promo['description'] ?? '' ?></textarea>
                </div>
                <div>
                    <label for="discount_percent" class="block font-semibold mb-1">Diskon (%)</label>
                    <input type="number" id="discount_percent" name="discount_percent" required min="0" max="100" value="<?= $edit_promo['discount_percent'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="start_date" class="block font-semibold mb-1">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" required value="<?= $edit_promo['start_date'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="end_date" class="block font-semibold mb-1">Tanggal Berakhir</label>
                    <input type="date" id="end_date" name="end_date" required value="<?= $edit_promo['end_date'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="active" name="active" <?= ($edit_promo && $edit_promo['active']) ? 'checked' : '' ?> />
                    <label for="active" class="font-semibold">Aktif</label>
                </div>
                <button type="submit" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700 transition">
                    <?= $edit_promo ? 'Perbarui Promosi' : 'Tambah Promosi' ?>
                </button>
                <?php if ($edit_promo): ?>
                    <a href="promosi.php" class="ml-4 text-gray-600 hover:underline">Batal</a>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-4">Daftar Promosi</h2>
            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-yellow-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3 text-left">Diskon (%)</th>
                        <th class="p-3 text-left">Periode</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($promotions) === 0): ?>
                        <tr><td colspan="5" class="p-3 text-center">Belum ada promosi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($promotions as $promo): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= htmlspecialchars($promo['title']) ?></td>
                                <td class="p-3"><?= $promo['discount_percent'] ?>%</td>
                                <td class="p-3"><?= htmlspecialchars($promo['start_date']) ?> s/d <?= htmlspecialchars($promo['end_date']) ?></td>
                                <td class="p-3"><?= $promo['active'] ? 'Aktif' : 'Tidak aktif' ?></td>
                                <td class="p-3 space-x-2">
                                    <a href="?action=edit&id=<?= $promo['id'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                    <a href="?action=delete&id=<?= $promo['id'] ?>" onclick="return confirm('Yakin ingin menghapus promosi ini?');" class="text-red-600 hover:underline">Hapus</a>
                                </td>
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
