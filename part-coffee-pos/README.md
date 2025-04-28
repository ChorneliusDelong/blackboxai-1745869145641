# Part Coffee - Sistem Pemesanan POS

Sistem Point of Sale (POS) sederhana untuk Coffee Shop "Part Coffee" menggunakan PHP, Tailwind CSS, dan MySQL.

## Fitur Utama

- Halaman Owner dengan dashboard statistik, manajemen menu, karyawan, laporan keuangan, promosi, dan log aktivitas.
- Halaman Karyawan untuk penerimaan pesanan, update status, manajemen stok, komunikasi internal, dan reminder stok.
- Halaman Pelanggan untuk browsing menu, keranjang belanja, checkout tanpa akun, dan pelacakan pesanan.
- Desain responsif dan mobile-friendly.
- Notifikasi pesanan via WhatsApp.

## Struktur Proyek

- `/public` - Folder untuk file yang dapat diakses publik (entry point, assets publik).
- `/config` - Konfigurasi database.
- `/src` - Kode sumber PHP (autentikasi, fungsi umum, dll).
- `/assets` - Gambar, logo, dan aset statis lainnya.
- `/sql` - Skrip database.

## Instalasi

1. Buat database MySQL dengan menjalankan skrip SQL di `sql/schema.sql`.
2. Sesuaikan konfigurasi database di `config/database.php`.
3. Jalankan server PHP (misal: `php -S localhost:8000 -t public`).
4. Akses aplikasi melalui browser di `http://localhost:8000`.

## Catatan

- Pastikan ekstensi PDO MySQL sudah aktif di PHP.
- Password user disimpan dengan hashing menggunakan `password_hash`.
- Semua halaman dan teks menggunakan bahasa Indonesia.
- Logo Part Coffee dapat ditemukan di `assets/images/part-coffee-logo.png`.

## Lisensi

Proyek ini dibuat untuk kebutuhan demonstrasi dan dapat dikembangkan lebih lanjut sesuai kebutuhan.
