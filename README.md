# Sistem Login dan CRUD User Management

Aplikasi web sederhana untuk manajemen pengguna dengan fitur login dan CRUD (Create, Read, Update, Delete) menggunakan PHP dan MySQL.

## Fitur

- Autentikasi user (Login/Logout)
- Registrasi user baru
- Manajemen user (CRUD)
- Role-based access control (Admin/User)
- Proteksi CSRF
- Validasi input yang kuat
- Penanganan session yang aman
- Interface yang responsif

## Persyaratan Sistem

- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Web server (Apache/Nginx)

## Instalasi

1. Clone repository ini ke direktori web server Anda:
   ```bash
   git clone https://github.com/username/praktikum-8.git
   ```

2. Import struktur database:
   - Buat database baru bernama `db_app_user`
   - Import file `database.sql` ke database tersebut
   ```bash
   mysql -u root -p db_app_user < database.sql
   ```

3. Konfigurasi database:
   - Buka file `config/database.php`
   - Sesuaikan kredensial database:
     ```php
     $host = "localhost";
     $db_name = "db_app_user";
     $username_db = "root";
     $password_db = "";
     ```

4. Pastikan folder memiliki permission yang tepat:
   ```bash
   chmod 755 -R praktikum-8/
   chmod 777 -R praktikum-8/logs/ (jika ada)
   ```

## Penggunaan

1. Akses aplikasi melalui browser:
   ```
   http://localhost/praktikum-8/sistem-login-crud/
   ```

2. Login dengan akun default admin:
   - Username: admin
   - Password: Admin123

3. Fitur yang tersedia:
   - Registrasi user baru
   - Login/logout
   - Manajemen user (lihat, tambah, edit, hapus)
   - Pengaturan profil

## Keamanan

Aplikasi ini menerapkan beberapa fitur keamanan:
- Password hashing menggunakan BCrypt
- Proteksi CSRF pada semua form
- Validasi input yang ketat
- Prepared statements untuk mencegah SQL Injection
- Session handling yang aman
- Role-based access control

## Struktur Proyek

```
sistem-login-crud/
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── config/
│   ├── database.php
│   └── functions.php
├── public/
│   └── css/
│       └── style.css
├── users/
│   ├── auth-check.php
│   ├── create.php
│   ├── delete.php
│   ├── edit.php
│   └── index.php
└── index.php
```
