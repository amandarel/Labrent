# LabRent — Sistem Peminjaman Alat atau Bahan Laboratorium Kimia

LabRent adalah aplikasi web berbasis PHP untuk mengelola peminjaman alat dan bahan laboratorium kimia Universitas Negeri Manado. Dibangun untuk memudahkan mahasiswa dalam meminjam alat dan mengupload modul, serta membantu admin laboratorium dalam mengelola inventaris dan modul serta dalam memantau laporan peminjaman.

---

## Fitur Utama

### Admin
- Dashboard 
- Kelola inventaris alat & bahan
- Persetujuan & pengelolaan peminjaman
- Kelola modul praktikum mahasiswa
- Cetak laporan peminjaman

### Mahasiswa
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

## Cara Instalasi

### Prasyarat
- XAMPP / Laragon / WAMP (PHP + MySQL/MariaDB)
- Web browser

### Langkah Instalasi

**1. Clone atau download repository ini**
```bash
git clone https://github.com/amandarel/labrent.git
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
cp config/database.php config/database.php
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

## Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin` |
| Mahasiswa | Daftar sendiri via halaman Register | — |

> Segera ganti password admin setelah pertama kali login.

---

## Screenshot Hasil Tampilan Website
<img width="2358" height="1313" alt="IMG_0997" src="https://github.com/user-attachments/assets/4576e4fe-bd13-49fd-9b17-61dea4a3775f" />
<img width="2358" height="1302" alt="IMG_0998" src="https://github.com/user-attachments/assets/56f318e9-5793-4fca-a777-73feb2702b4c" />
<img width="2358" height="1298" alt="IMG_0999" src="https://github.com/user-attachments/assets/7f490d64-ecec-4ffb-b4e4-9846e8c88176" /><img width="2358" height="1292" alt="IMG_1001" src="https://github.com/user-attachments/assets/857336b9-cb37-4d91-831f-30759e355af6" />
<img width="2358" height="1438" alt="IMG_1002" src="https://github.com/user-attachments/assets/e2d76a4e-def1-4a73-b14c-35100ef595b5" />
<img width="2358" height="1543" alt="IMG_1003" src="https://github.com/user-attachments/assets/1a737a9c-b528-4cf0-8fdf-3471b8a54ddc" />
<img width="2352" height="1139" alt="IMG_1004" src="https://github.com/user-attachments/assets/f842f789-abb4-40b5-b7e5-df35b0590ef3" /> <img width="2358" height="1271" alt="IMG_1005" src="https://github.com/user-attachments/assets/9a46cc07-6e96-4dc6-bf7c-db8684b13162" />
<img width="2341" height="912" alt="IMG_1006" src="https://github.com/user-attachments/assets/71414aff-a94f-4d1c-9980-c4ce2ae680ec" />
<img width="2358" height="1555" alt="IMG_1007" src="https://github.com/user-attachments/assets/c6aaaa68-37be-427a-8ec0-600a22ee4c3d" />
<img width="2358" height="1566" alt="IMG_1008" src="https://github.com/user-attachments/assets/f3ed08c8-9bbd-489d-8c48-7520929af3d7" />

---

## Pengembang

Dikembangkan sebagai proyek mata kuliah Manajemen Proyek Perangkat Lunak untuk sistem informasi manajemen inventaris aset laboratorium pada Laboratorium Kimia.

Universitas Negeri Manado (UNIMA)
