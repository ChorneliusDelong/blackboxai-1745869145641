<?php
// Fungsi umum yang digunakan di seluruh aplikasi

// Fungsi untuk redirect ke halaman lain
function redirect($url) {
    header("Location: $url");
    exit;
}

// Fungsi untuk menampilkan tanggal dalam format Indonesia
function format_tanggal($tanggal) {
    setlocale(LC_TIME, 'id_ID.UTF-8');
    return strftime('%d %B %Y', strtotime($tanggal));
}

// Fungsi untuk menampilkan status pesanan dalam bahasa Indonesia
function status_pesanan($status) {
    $status_map = [
        'pending' => 'Menunggu',
        'diproses' => 'Diproses',
        'siap' => 'Siap',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'batal' => 'Batal'
    ];
    return $status_map[$status] ?? $status;
}
?>
