# SIFAKTUR - Sistem Point of Sale (POS) & Inventori

SIFAKTUR adalah sebuah sistem berbasis web (PHP & MySQL) yang dirancang untuk memudahkan manajemen data master (Perusahaan, Customer, Produk), pencatatan transaksi faktur penjualan, serta pelaporan stok dan omzet (khususnya untuk model bisnis seperti Apotek/Klinik).

## Fitur Utama
- **Master Data:** Kelola data Perusahaan, Customer, dan Produk dengan mudah.
- **Transaksi Faktur:** Pembuatan faktur penjualan lengkap dengan PPN, DP, dan metode pembayaran.
- **Laporan Otomatis:** Modul cetak laporan stok produk dan rekapan omzet penjualan berdasarkan rentang tanggal.
- **Cetak Dokumen Formal:** Cetak PDF/Print untuk faktur dan laporan berdesain formal hitam-putih.

---

## Panduan Instalasi (XAMPP / Localhost)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi SIFAKTUR di komputer Anda.

### 1. Persiapan Kebutuhan Sistem
Pastikan komputer Anda sudah terinstal perangkat lunak berikut:
- **XAMPP** (versi PHP 7.4 atau PHP 8.x ke atas direkomendasikan).
- Web browser (Google Chrome, Firefox, dll).

### 2. Memasukkan File Proyek
1. *Clone* atau *Download ZIP* repositori ini.
2. Jika Anda men-download ZIP, ekstrak file tersebut.
3. Pindahkan folder `sifaktur` (atau `ujikom`) ke dalam folder direktori web server XAMPP Anda:
   - Windows: `C:\xampp\htdocs\`
   - macOS: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### 3. Konfigurasi Database (Penting!)
Aplikasi ini membutuhkan database MySQL untuk berjalan. File database sudah kami sediakan di dalam folder ini bernama `sifaktur.sql`.

1. Buka aplikasi XAMPP Control Panel, lalu klik **Start** pada modul **Apache** dan **MySQL**.
2. Buka web browser Anda dan ketik URL berikut: `http://localhost/phpmyadmin`
3. Pada halaman phpMyAdmin, buat database baru:
   - Klik menu **New** (Baru) di sidebar sebelah kiri.
   - Masukkan nama database: **`faktur`**.
   - Klik tombol **Create** (Buat).
4. Import tabel dan data:
   - Klik database `faktur` yang baru saja Anda buat.
   - Klik tab **Import** di deretan menu atas.
   - Klik tombol **Choose File** (Pilih File), lalu cari dan pilih file **`sifaktur.sql`** yang ada di dalam folder proyek Anda (`C:\xampp\htdocs\sifaktur\sifaktur.sql`).
   - Scroll ke bawah dan klik tombol **Import** (atau Go).

### 4. Menjalankan Aplikasi
1. Buka web browser kesayangan Anda.
2. Ketikkan URL berikut di *address bar*:
   ```
   http://localhost/sifaktur
   ```
   *(Ganti kata "sifaktur" dengan nama folder Anda jika berbeda, contoh: `http://localhost/ujikom`)*
3. Selesai! Aplikasi SIFAKTUR siap digunakan.

---

## Catatan Konfigurasi
Jika Anda menggunakan password pada `root` MySQL Anda (secara default XAMPP *tidak* menggunakan password), Anda harus menyesuaikan kredensial koneksi di dalam file:
`classes/Database.php` pada baris pengaturan:
```php
private $host = 'localhost';
private $user = 'root';
private $pass = ''; // Isi dengan password MySQL Anda jika ada
private $dbname = 'faktur';
```
