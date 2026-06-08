# LabRent — Sistem Peminjaman Alat atau Bahan Laboratorium Kimia

LabRent adalah aplikasi web berbasis PHP untuk mengelola peminjaman alat dan bahan laboratorium kimia Universitas Negeri Manado. Dibangun untuk memudahkan mahasiswa dalam meminjam alat dan mengupload modul, serta membantu admin laboratorium dalam mengelola inventaris dan modul serta dalam memantau laporan peminjaman.

---

## Fitur Utama

### 👨‍💼 Admin
- Dashboard 
- Kelola inventaris alat & bahan
- Persetujuan & pengelolaan peminjaman
- Kelola modul praktikum mahasiswa
- Cetak laporan peminjaman

### 👨‍🎓 Mahasiswa
- Daftar & login akun
- Lihat daftar alat yang tersedia
- Ajukan peminjaman alat
- Memasukkan modul praktikum
- Kelola profil

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Keterangan |
|-----------|------------|
| PHP | Backend |
| MySQL  | Database |
| HTML, CSS, JavaScript | Frontend |
| Bootstrap | UI Framework |

---

## Struktur Folder

```
labrent/
├── index.php               # Halaman utama
├── login.php               # Halaman login
├── logout.php              # Proses logout
├── register.php            # Halaman registrasi mahasiswa
├── about.php               # Halaman tentang
├── profil.php              # Halaman profil pengguna
├── labrent_db.sql          # File SQL database
│
├── config/
│   └── database.php        # Konfigurasi koneksi database
│
├── admin/
│   ├── dashboard_admin.php # Dashboard admin
│   ├── inventaris.php      # Kelola inventaris alat & bahan
│   ├── peminjaman.php      # Kelola data peminjaman
│   ├── modul.php           # Kelola modul praktikum
│   └── laporan.php         # Cetak laporan
│
├── mahasiswa/
│   ├── dashboard_user.php  # Dashboard mahasiswa
│   ├── daftar_alat.php     # Lihat daftar alat
│   ├── pinjam.php          # Form peminjaman
│   └── modul_user.php      # Lihat & download modul
│
├── actions/
│   ├── proses_pinjam.php        # Proses peminjaman (admin)
│   ├── proses_pinjam_user.php   # Proses peminjaman (mahasiswa)
│   ├── proses_inventaris.php    # Proses CRUD inventaris
│   ├── proses_modul.php         # Proses upload modul
│   └── proses_profil.php        # Proses update profil
│
├── includes/
│   ├── header.php          # Template header
│   └── sidebar.php         # Template sidebar navigasi
│
├── assets/
│   └── img/                # Gambar / logo
│
└── uploads/
    └── modul/              # File PDF modul yang diupload
```

---

## ⚙️ Cara Instalasi

### Prasyarat
- XAMPP / Laragon / WAMP (PHP + MySQL/MariaDB)
- Web browser

### Langkah Instalasi

**1. Clone atau download repository ini**
```bash
git clone https://github.com/username/labrent.git
```

**2. Pindahkan folder ke direktori server lokal**
- XAMPP: `C:/xampp/htdocs/labrent`
- Laragon: `C:/laragon/www/labrent`

**3. Import database**
- Buka phpMyAdmin di browser: `http://localhost/phpmyadmin`
- Buat database baru bernama `labrent_db`
- Import file `labrent_db.sql` yang ada di dalam folder proyek

**4. Konfigurasi koneksi database**

Salin file contoh konfigurasi dan sesuaikan:
```bash
cp config/database.example.php config/database.php
```

Edit file `config/database.php`:
```php
$host = 'localhost';
$db   = 'labrent_db';
$user = 'root';       // sesuaikan username MySQL kamu
$pass = '';           // sesuaikan password MySQL kamu
```

**5. Jalankan aplikasi**

Buka browser dan akses:
```
http://localhost/labrent/
```

---

## 🔐 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Mahasiswa | Daftar sendiri via halaman Register | — |

> ⚠️ Segera ganti password admin setelah pertama kali login.

---

## Screenshot

> *(Tambahkan screenshot aplikasi di sini)*

---

## Pengembang

Dikembangkan sebagai proyek web untuk sistem manajemen laboratorium.

Universitas Negeri Manado (UNIMA)

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik.
