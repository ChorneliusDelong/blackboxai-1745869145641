<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../config/database.php';
check_access([1]); // Owner only

// Handle add/edit/delete actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $role_id = $_POST['role_id'] ?? 3; // default role Kasir
    $password = $_POST['password'] ?? '';

    if ($action === 'add') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, phone, email, role_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $full_name, $phone, $email, $role_id]);
        $message = "Karyawan berhasil ditambahkan.";
    } elseif ($action === 'edit' && $id) {
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, full_name = ?, phone = ?, email = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$username, $hashed_password, $full_name, $phone, $email, $role_id, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, phone = ?, email = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$username, $full_name, $phone, $email, $role_id, $id]);
        }
        $message = "Karyawan berhasil diperbarui.";
    }
    header("Location: karyawan.php?message=" . urlencode($message));
    exit;
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: karyawan.php?message=" . urlencode("Karyawan berhasil dihapus."));
    exit;
}

// Fetch employees
$stmt = $pdo->query("SELECT users.*, roles.name AS role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.full_name ASC");
$employees = $stmt->fetchAll();

// Fetch roles for dropdown
$role_stmt = $pdo->query("SELECT * FROM roles ORDER BY name ASC");
$roles = $role_stmt->fetchAll();

// If editing, fetch employee data
$edit_employee = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $edit_employee = $stmt->fetch();
}

$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Karyawan - Part Coffee</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-yellow-600 text-white p-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Manajemen Karyawan - Part Coffee</h1>
        <a href="dashboard.php" class="hover:underline">Kembali ke Dashboard</a>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <?php if ($message): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4"><?= $edit_employee ? 'Edit Karyawan' : 'Tambah Karyawan Baru' ?></h2>
            <form method="POST" action="?action=<?= $edit_employee ? 'edit&id=' . $edit_employee['id'] : 'add' ?>" class="space-y-4 bg-white p-6 rounded shadow">
                <div>
                    <label for="username" class="block font-semibold mb-1">Username</label>
                    <input type="text" id="username" name="username" required value="<?= $edit_employee['username'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="password" class="block font-semibold mb-1">Password <?= $edit_employee ? '(Kosongkan jika tidak diubah)' : '' ?></label>
                    <input type="password" id="password" name="password" <?= $edit_employee ? '' : 'required' ?> class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="full_name" class="block font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" required value="<?= $edit_employee['full_name'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="phone" class="block font-semibold mb-1">Nomor HP</label>
                    <input type="text" id="phone" name="phone" value="<?= $edit_employee['phone'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="email" class="block font-semibold mb-1">Email</label>
                    <input type="email" id="email" name="email" value="<?= $edit_employee['email'] ?? '' ?>" class="w-full p-2 border border-gray-300 rounded" />
                </div>
                <div>
                    <label for="role_id" class="block font-semibold mb-1">Jabatan</label>
                    <select id="role_id" name="role_id" required class="w-full p-2 border border-gray-300 rounded">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= ($edit_employee && $edit_employee['role_id'] == $role['id']) ? 'selected' : '' ?>><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700 transition">
                    <?= $edit_employee ? 'Perbarui Karyawan' : 'Tambah Karyawan' ?>
                </button>
                <?php if ($edit_employee): ?>
                    <a href="karyawan.php" class="ml-4 text-gray-600 hover:underline">Batal</a>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-4">Daftar Karyawan</h2>
            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-yellow-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Username</th>
                        <th class="p-3 text-left">Nama Lengkap</th>
                        <th class="p-3 text-left">Jabatan</th>
                        <th class="p-3 text-left">Nomor HP</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($employees) === 0): ?>
                        <tr><td colspan="6" class="p-3 text-center">Belum ada karyawan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= htmlspecialchars($emp['username']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($emp['full_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($emp['role_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($emp['phone']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($emp['email']) ?></td>
                                <td class="p-3 space-x-2">
                                    <a href="?action=edit&id=<?= $emp['id'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                    <a href="?action=delete&id=<?= $emp['id'] ?>" onclick="return confirm('Yakin ingin menghapus karyawan ini?');" class="text-red-600 hover:underline">Hapus</a>
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
