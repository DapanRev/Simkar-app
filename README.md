# SIMKAR - Sistem Informasi Manajemen Kendaraan

## Deskripsi Aplikasi
SIMKAR adalah aplikasi berbasis web yang digunakan untuk mengelola pemesanan kendaraan di lingkungan perusahaan tambang nikel. Aplikasi ini memungkinkan pengguna untuk melakukan pemesanan kendaraan, manajemen data kendaraan, monitoring pemeliharaan, dan pembuatan laporan.

---

## Teknologi yang Digunakan
- **PHP Version**: 7.4 atau lebih tinggi
- **Database**: MySQL 5.7 atau lebih tinggi
- **Web Server**: Apache 2.4 atau lebih tinggi
- **Bootstrap**: v4.5.2
- **Chart.js**: v3.0.0 untuk visualisasi data
- **Font Awesome**: v5.15.1 untuk ikon

---

## Instalasi dan Konfigurasi
1. **Clone Repository**
   - Clone kode proyek dari repositori ke direktori web server Anda.
     ```bash
     git clone https://github.com/username/simkar.git
     ```

2. **Import Database**
   - Buat database baru di MySQL dengan nama `simkar_db`.
   - Import file SQL ke database tersebut:
     ```bash
     mysql -u root -p simkar_db < simkar.sql
     ```
  - Atau anda bisa menggunakan koding ini untuk di jalankan di mySQL
    -- Script SQL untuk membuat database SIMKAR

-- 1. Membuat Database
CREATE DATABASE simkar;
USE simkar;

-- 2. Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'approver', 'driver') NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(15) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabel vehicles
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('angkutan_orang', 'angkutan_barang') NOT NULL,
    name VARCHAR(100) NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
    fuel_type ENUM('diesel', 'bensin', 'electric') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabel reservations
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    driver_id INT,
    approver_level1_id INT NOT NULL,
    approver_level2_id INT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    purpose TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (driver_id) REFERENCES users(id),
    FOREIGN KEY (approver_level1_id) REFERENCES users(id),
    FOREIGN KEY (approver_level2_id) REFERENCES users(id)
);

-- 5. Tabel logs
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 6. Tabel services
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    service_date DATETIME NOT NULL,
    description TEXT,
    cost DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);


3. **Konfigurasi Database**
   - Buka file `config.php` dan sesuaikan pengaturan database Anda:
     ```php
     <?php
     $host = 'localhost';
     $db   = 'simkar_db';
     $user = 'root';
     $pass = '';
     $charset = 'utf8mb4';

     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
     $options = [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     ];
     try {
         $pdo = new PDO($dsn, $user, $pass, $options);
     } catch (PDOException $e) {
         throw new PDOException($e->getMessage());
     }
     ?>
     ```

4. **Jalankan Aplikasi**
   - Pastikan Apache dan MySQL sudah berjalan.
   - Akses aplikasi melalui browser:
     ```
     http://localhost/simkar/
     ```

---

## Akun Login
Berikut adalah daftar username dan password untuk role pengguna yang berbeda:

### **Administrator**
- **Username**: admin
- **Password**: admin123

### **Approver**
- **Username**: approver
- **Password**: approver123

### **User**
- **Username**: user
- **Password**: user123

> **Catatan**: Anda dapat menambahkan lebih banyak pengguna melalui halaman manajemen user di dalam aplikasi.

---

## Panduan Penggunaan Aplikasi

### 1. **Login**
   - Buka halaman login dan masukkan username serta password yang sesuai dengan role Anda.
   - Role pengguna yang tersedia:
     - **Admin**: Mengelola semua data kendaraan, pemesanan, pengguna, dan laporan.
     - **Approver**: Menyetujui pemesanan kendaraan.
     - **User**: Melakukan pemesanan kendaraan.

### 2. **Dashboard**
   - Setelah login, Anda akan diarahkan ke halaman dashboard yang menampilkan statistik:
     - Total kendaraan
     - Total reservasi
     - Kendaraan dalam pemeliharaan

### 3. **Manajemen Pemesanan Kendaraan**
   - Akses menu **Reservations** untuk melakukan pemesanan kendaraan baru.
   - Pilih kendaraan yang tersedia, atur tanggal mulai dan berakhir, lalu kirim pemesanan.
   - Admin atau Approver dapat menyetujui atau menolak pemesanan yang masuk.

### 4. **Manajemen Kendaraan**
   - Akses menu **Vehicles** untuk:
     - Menambahkan kendaraan baru.
     - Mengedit data kendaraan.
     - Menghapus kendaraan yang tidak digunakan.

### 5. **Monitoring Pemeliharaan**
   - Akses menu **Services** untuk melihat daftar kendaraan yang sedang dalam pemeliharaan.
   - Admin dapat menambahkan detail pemeliharaan kendaraan seperti tanggal, deskripsi, dan biaya.

### 6. **Laporan Penggunaan Kendaraan**
   - Akses menu **Reports** untuk melihat laporan pemesanan kendaraan.
   - Laporan dapat diunduh dalam format Excel atau PDF.

---

## Struktur Direktori
```
SIMKAR/
├── assets/                 # File gambar, CSS, JS
├── config.php              # Konfigurasi database
├── login.php               # Halaman login
├── logout.php              # Logout pengguna
├── admin_dashboard.php     # Dashboard Admin
├── reservations/           # Folder untuk manajemen reservasi
├── vehicles/               # Folder untuk manajemen kendaraan
├── services/               # Folder untuk pemeliharaan kendaraan
├── users/                  # Folder untuk manajemen user
├── reports/                # Folder untuk laporan
└── simkar.sql              # File database SQL
```

---

## Kontak dan Dukungan
Jika Anda mengalami kendala dalam penggunaan aplikasi ini, hubungi:
- **Nama**: [Christian Emmanuel Mercy Danian]
- **Email**: [christiandanian183@gmail.com]
- **Telepon**: [0814-5604-1710]

---

**Terima Kasih telah menggunakan SIMKAR!**

---
