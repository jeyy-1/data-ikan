# Sistem Data Ikan - Tugas Akhir Pemrograman Web

Aplikasi CRUD data ikan menggunakan PHP, MySQL, PDO, HTML, dan CSS.

## Data yang dikelola
- Nama ikan
- Jenis / nama ilmiah
- Habitat
- Harga
- Stok

## Fitur
- Create, Read, Update, Delete
- Validasi server-side
- Nama ikan unik
- PDO prepared statement
- htmlspecialchars untuk keamanan output
- CSRF token untuk delete
- Post-Redirect-Get
- Search/filter berdasarkan nama, jenis, dan habitat
- UI responsif dengan Flexbox

## Struktur
```text
data-ikan/
├── config/db.php
├── database/ikan_db.sql
├── public/
│   ├── _bootstrap.php
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── assets/style.css
└── README.md
```

## Cara menjalankan XAMPP
1. Ekstrak folder `data-ikan` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL.
3. Buka `http://localhost/phpmyadmin`.
4. Import `database/ikan_db.sql`.
5. Buka `http://localhost/data-ikan/public/`.

## Pengujian
1. Tambah data ikan valid.
2. Coba nama ikan kurang dari 3 karakter.
3. Coba harga 0/negatif.
4. Coba stok negatif.
5. Coba nama ikan duplikat.
6. Edit data ikan.
7. Hapus data ikan.
8. Gunakan fitur pencarian.
9. Uji input `<b>Promo</b>` untuk memastikan output di-escape.

## Catatan
Jika username/password MySQL berbeda, ubah `config/db.php`.
Sebelum dikumpulkan, ubah nama ZIP menjadi `Praktikum3_NIM_Nama.zip` sesuai format dosen.
