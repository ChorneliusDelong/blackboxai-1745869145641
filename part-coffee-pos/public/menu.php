<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../config/database.php';
check_access([1]); // Owner only

// Handle add/edit/delete actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $available = isset($_POST['available']) ? 1 : 0;
    $category_id = $_POST['category_id'] ?? null;

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO menu_items (category_id, name, description, price, stock, available) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $name, $description, $price, $stock, $available]);
        $message = "Menu berhasil ditambahkan.";
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE menu_items SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, available = ? WHERE id = ?");
        $stmt->execute([$category_id, $name, $description, $price, $stock, $available, $id]);
        $message = "Menu berhasil diperbarui.";
    }
    header("Location: menu.php?message=" . urlencode($message));
    exit;
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: menu.php?message=" . urlencode("Menu berhasil dihapus."));
    exit;
}

// Fetch menu items
$stmt = $pdo->query("SELECT menu_items.*, categories.name AS category_name FROM menu_items LEFT JOIN categories ON menu_items.category_id = categories.id ORDER BY menu_items.created_at DESC");
$menu_items = $stmt->fetchAll();

// Fetch categories for dropdown
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

// If editing, fetch menu item data
$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Menu - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Manajemen Menu - Part Coffee</h1>
        <a href="dashboard.php" class="hover:underline">Kembali ke Dashboard</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <?php if ($message): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4"><?= $edit_item ? 'Edit Menu' : 'Tambah Menu Baru' ?></h2>
            <form method="POST" action="?action=<?= $edit_item ? 'edit&id=' . $edit_item['id'] : 'add' ?>" class="space-y-4 bg-white p-6 rounded shadow">
                <div>
                    <label for="category_id" class="block font-semibold mb-1">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Pilih kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($edit_item && $edit_item['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="name" class="block font-semibold mb-1">Nama Menu</label>
                    <input type="text" id="name" name="name" required value="<?= $edit_item['name'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="description" class="block font-semibold mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="w-full p-2 border border-gray-300 rounded"><?= $edit_item['description'] ?? '' ?></textarea>
                </div>
                <div>
                    <label for="price" class="block font-semibold mb-1">Harga (Rp)</label>
                    <input type="number" id="price" name="price" required min="0" step="0.01" value="<?= $edit_item['price'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="stock" class="block font-semibold mb-1">Stok</label>
                    <input type="number" id="stock" name="stock" required min="0" value="<?= $edit_item['stock'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="available" name="available" <?= ($edit_item && $edit_item['available']) ? 'checked' : '' ?> />
                    <label for="available" class="font-semibold">Tersedia</label>
                </div>
                <button type="submit" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700 transition">
                    <?= $edit_item ? 'Perbarui Menu' : 'Tambah Menu' ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="menu.php" class="ml-4 text-gray-600 hover:underline">Batal</a>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-4">Daftar Menu</h2>
            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-yellow-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Harga (Rp)</th>
                        <th class="p-3 text-left">Stok</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($menu_items) === 0): ?>
                        <tr><td colspan="6" class="p-3 text-center">Belum ada menu.</td></tr>
                    <?php else: ?>
                        <?php foreach ($menu_items as $item): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= htmlspecialchars($item['category_name'] ?? '-') ?></td>
                                <td class="p-3"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="p-3">Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
                                <td class="p-3"><?= $item['stock'] ?></td>
                                <td class="p-3"><?= $item['available'] ? 'Tersedia' : 'Tidak tersedia' ?></td>
                                <td class="p-3 space-x-2">
                                    <a href="?action=edit&id=<?= $item['id'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                    <a href="?action=delete&id=<?= $item['id'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?');" class="text-red-600 hover:underline">Hapus</a>
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
