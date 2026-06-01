# Sistem Reservasi Hotel

## Deskripsi

Sistem Reservasi Hotel adalah aplikasi berbasis web yang dikembangkan menggunakan Laravel untuk membantu proses pengelolaan hotel secara digital. Sistem ini memudahkan admin dan resepsionis dalam mengelola data tamu, reservasi kamar, check-in, check-out, serta laporan keuangan hotel.

## Fitur Sistem

### Admin

* Login Admin
* Kelola Data Kamar (Tambah, Edit, Hapus)
* Melihat Data Tamu
* Melihat Data Reservasi
* Melihat Data Check-In dan Check-Out
* Melihat Laporan Keuangan
* Melihat Aktivitas Resepsionis

### Resepsionis

* Login Resepsionis
* Input Data Tamu
* Melakukan Reservasi Kamar
* Check-In Tamu
* Check-Out Tamu
* Melihat Data Reservasi

## Teknologi yang Digunakan

* Laravel 12
* PHP 8+
* MySQL
* Bootstrap 5
* JavaScript
* Git & GitHub

## Alur Sistem

1. Pengguna melakukan login sebagai Admin atau Resepsionis.
2. Resepsionis menginput data tamu.
3. Tamu melakukan reservasi kamar.
4. Sistem menyimpan data reservasi.
5. Saat tamu datang dilakukan proses check-in.
6. Saat masa inap selesai dilakukan proses check-out.
7. Sistem menghitung pembayaran dan menyimpan data ke laporan keuangan.
8. Admin dapat melihat seluruh aktivitas dan laporan.

## Instalasi

### Clone Repository

```bash
git clone https://github.com/Rdhok4me/sistem-hotel.git
```

### Masuk ke Folder Project

```bash
cd sistem-hotel
```

### Install Dependency

```bash
composer install
```

### Copy File Environment

```bash
cp .env.example .env
```

### Generate Application Key

```bash
php artisan key:generate
```

### Konfigurasi Database

Edit file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_hotel
DB_USERNAME=root
DB_PASSWORD=
```

### Jalankan Migrasi

```bash
php artisan migrate --seed
```

### Menjalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

## Database

Database menggunakan MySQL dan terdiri dari beberapa tabel utama seperti:

* users
* tamus
* kamars
* reservasis
* check_ins
* check_outs
* pembayarans

## ERD

Tambahkan gambar ERD pada folder:

```text
docs/erd.png
```

## Flowchart

Tambahkan gambar Flowchart pada folder:

```text
docs/flowchart.png
```

## Pengembang

M Ridho

## License

Project ini menggunakan MIT License.
