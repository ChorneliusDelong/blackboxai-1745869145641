<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Fungsi login
function login($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['full_name'] = $user['full_name'];
        return true;
    }
    return false;
}

// Fungsi logout
function logout() {
    session_unset();
    session_destroy();
}

// Cek apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Mendapatkan role user
function get_user_role() {
    return $_SESSION['role_id'] ?? null;
}

// Cek akses role
function check_access($allowed_roles = []) {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
    $user_role = get_user_role();
    if (!in_array($user_role, $allowed_roles)) {
        http_response_code(403);
        echo "Akses ditolak.";
        exit;
    }
}
?>
