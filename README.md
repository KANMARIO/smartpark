🚗 SmartPark – Analysis Report
1. Gambaran Umum Proyek

SmartPark adalah aplikasi manajemen parkir berbasis web yang dirancang untuk membantu pengguna dalam menyediakan, memesan, melakukan check-in, dan check-out slot parkir secara efisien. Sistem ini memastikan bahwa setiap slot parkir hanya dapat digunakan oleh satu pengguna dalam satu waktu, sehingga mencegah terjadinya pemesanan ganda (double booking) serta meningkatkan efisiensi penggunaan ruang parkir.

“Proyek ini tidak menggunakan Node.js atau framework JavaScript, sehingga tidak memerlukan file package.json. Seluruh dependency frontend menggunakan CDN, dan backend menggunakan PHP native.”

2. Arsitektur & Desain Basis Data

> 2.1 Arsitektur Aplikasi

SmartPark menerapkan arsitektur web monolitik dengan pemisahan tanggung jawab (separation of concerns) yang jelas antara tampilan, logika aplikasi, dan pengelolaan data.

a. Frontend (Presentation Layer)
Frontend dibangun menggunakan:
  - HTML
  - CSS
  - Bootstrap
  - JavaScript
Lapisan ini bertanggung jawab dalam menampilkan antarmuka pengguna yang responsif, intuitif, dan mudah digunakan di berbagai ukuran perangkat.

b. Backend (Application Layer)
Backend dikembangkan menggunakan PHP, dengan tanggung jawab utama:
  - Menangani autentikasi pengguna (login, registrasi dan logout)
  - Membuat Dashboard Admin untuk melakukan Fungsi (CREATE, READ, UPDATE, DELETE) agar tidak mengakses langsung ke database
  - Mengelola logika booking, check-in, dan check-out
  - Mengelola logika slot_status secara real-time (AVAILABLE, OCCUPIED, dan RESERVED)
  - Melakukan validasi data masukan dari pengguna
  - Menghubungkan aplikasi dengan basis data
  - Mengamankan setiap halaman dengan SESSION

c. Database (Data Layer)
MySQL digunakan sebagai sistem basis data untuk menyimpan data permanen seperti:
  - Data pengguna (user)
  - Data tempat parkir (parking)
  - Data slot parkir (slot)
  - Data pemesanan (booking)

Alasan Pemilihan Arsitektur
Arsitektur monolitik berbasis PHP dipilih karena:

- Proses deployment yang sederhana
- Mudah dalam pengembangan dan debugging
- Sangat kompatibel dengan layanan shared hosting seperti InfinityFree dan XAMPP
- Cocok untuk proyek skala kecil hingga menengah

> 2.2 Desain Skema Basis Data

Struktur basis data SmartPark dirancang untuk menjaga konsistensi dan integritas data.

- user
-----------------
  user_id (PK)    INT(10)
  user_name       VARCHAR(50)
  phone_num       VARCHAR(14)
  user_status     VARCHAR(14)
  user_plate      VARCHAR(10)
  user_pass       CHAR(255)

- parking
----------------
  park_id (PK)    CHAR(3)
  park_name       VARCHAR(50)
  park_slot       INT(20)
  park_address    TEXT

- slot
----------------
  park_id (FK)    CHAR(3)
  slot_id (PK)    CHAR(3)
  slot_number     VARCHAR(50)
  slot_status     ENUM('AVAILABLE', 'RESERVED', 'OCCUPIED')

- booking
-----------------
  booking_id(PK)  INT(11)
  user_id (FK)    INT(11)
  slot_id (FK)    CHAR(3)
  park_id (FK)    CHAR(3)
  book_time       DATETIME
  checkin_time    DATETIME
  checkout_time   DATETIME


> 2.3 Relasi Antar Tabel

- user → booking
Satu pengguna dapat memiliki lebih dari satu riwayat pemesanan.
Relasi: user.user_id → booking.user_id

- slot → booking
Satu slot parkir dapat dipesan berkali-kali pada waktu yang berbeda, namun hanya satu pemesanan aktif dalam satu waktu.
Relasi: slot.slot_id → booking.slot_id

- parking → slot
Satu tempat parkir memiliki banyak slot (20 slot).
Relasi: parking.park_id → slot.park_id

Alasan Desain Basis Data
Menjaga normalisasi data
Menghindari duplikasi data slot parkir
Mempermudah penerapan aturan bisnis seperti “satu slot hanya untuk satu pengguna aktif”

3. Pemilihan Teknologi
> 3.1 Ringkasan Tech Stack

Layer	        | Teknologi

Frontend	    | HTML, CSS, Bootstrap 5, JavaScript
Backend	      | PHP
Database	    | MySQL
Hosting	      | InfinityFree
Version       | Control	Git


> 3.2 Alasan Pemilihan Teknologi

- Backend – PHP
Integrasi yang mudah dengan MySQL
Cocok untuk pemrosesan form dan logika server-side
Didukung secara luas oleh shared hosting
Mempercepat proses pengembangan aplikasi

- Database – MySQL
Database relasional yang ideal untuk data terstruktur
Mendukung foreign key dan transaksi
Performa baik untuk operasi CRUD (Create, Read, Update, Delete)

- Frontend – Bootstrap
Desain responsif secara default
Mempercepat pembuatan antarmuka pengguna
Tampilan konsisten di berbagai perangkat

Mengapa Tidak Menggunakan MERN / MEVN?
Dengan mempertimbangkan skala proyek dan keterbatasan hosting, kombinasi PHP + MySQL dinilai lebih ringan, praktis, serta tidak memerlukan infrastruktur tambahan seperti server Node.js.


4. Desain API
> 4.1 Gaya API
  Aplikasi SmartPark menggunakan pendekatan REST-like API yang diimplementasikan melalui skrip PHP.

// Alasan Menggunakan REST-like API
    Sederhana dan mudah dipahami
    Mudah dikembangkan dan dipelihara
    Pemisahan aksi yang jelas berdasarkan metode HTTP (GET, POST)

> 4.2 Endpoint API Utama
--- 1. Login Pengguna

Endpoint: /login.php
Method: POST
Deskripsi: Mengautentikasi pengguna dan memulai sesi

--- 2. Melihat Slot Parkir

Endpoint: /main.php
Method: GET
Deskripsi: Menampilkan daftar slot parkir yang tersedia dan terisi

--- 3. Booked Slot Parkir

Endpoint: /booking.php
Method: POST
Deskripsi: Membuat data pemesanan slot parkir

--- 4. Check-in

Endpoint: /checkin.php
Method: POST
Deskripsi: Mengubah status pemesanan menjadi checked_in

--- 5. Check-out

Endpoint: /checkout.php
Method: POST
Deskripsi: Mengakhiri pemesanan dan mengosongkan slot parkir

--- 6. Admin Dashboard

Endpoint: /dashboard.php
Method: POST
Deskripsi: Menampilkan semua isi dari database dan dapat melakukan (CRUD)


5. Analisis Hasil & Penanganan Error
> 5.1 Strategi Penanganan Error

Aplikasi menangani kesalahan dengan beberapa pendekatan berikut:
- Validasi sesi
Pengguna yang belum login akan diarahkan ke login.php

- Validasi input
Mencegah pengiriman data kosong atau tidak valid

- Kendala basis data
Foreign key mencegah referensi data yang tidak sah

- Logika antarmuka
Tombol check-in dan check-out hanya muncul sesuai status pemesanan

Contoh Kasus:
Pengguna belum login → otomatis diarahkan ke halaman login
Slot sudah dipesan → tidak ditampilkan sebagai slot tersedia


> 5.2 Tantangan Teknis Terbesar

  Tantangan:
Mencegah satu slot parkir digunakan oleh lebih dari satu pengguna secara bersamaan.

  Solusi:
Menambahkan atribut booking_status

  Menerapkan logika sistem untuk:
Menyembunyikan slot yang sudah dibooking
Membatasi satu pemesanan aktif per slot
Memperbarui status slot saat check-in dan check-out

Pendekatan ini menjaga konsistensi data serta mencegah konflik penggunaan slot.


> 5.3 Pengembangan Lanjutan

Jika diberikan waktu tambahan satu minggu, pengembangan berikut akan dilakukan:
- Menambahkan sistem Pembayaran
- Dan Biaya member

Peningkatan keamanan:
- Enkripsi password menggunakan password_hash()

=========================================================================================================
HOW TO RUN MY PROGRAM: 
=========================================================================================================

> Langkah 1: Instalasi Dependency

Install XAMPP 

Jalankan service Apache dan MySQL
📌 Tidak ada dependency tambahan yang perlu diinstal.
=========================================================================================================

> Langkah 2: Pengaturan Environment

Pindahkan folder project ke:
htdocs/

Atur koneksi database di file:
/includes/config.php

Contoh:
"$conn = mysqli_connect("localhost", "root", "", "smartpark");"

=========================================================================================================

> Langkah 3: Menjalankan Database Seed

Buka phpMyAdmin

Buat database baru dengan nama:
smartpark

Import file SQL:
smartpark.sql

=========================================================================================================

> Langkah 4: Menjalankan Backend

Backend berjalan otomatis melalui Apache
Tidak perlu menjalankan server tambahan Akses melalui browser:
http://localhost/smartpark

=========================================================================================================

> Langkah 5: Menjalankan Frontend

Frontend disajikan langsung oleh Apache
Tidak ada frontend server terpisah

Buka:http://localhost/smartpark/login.php

=========================================================================================================

PENTING !!!

Dependencies

Proyek ini dikembangkan menggunakan PHP Native dan tidak menggunakan framework backend berbasis Node.js.
Seluruh dependensi backend disediakan oleh environment server, sedangkan dependensi frontend dimuat melalui CDN.

Backend Dependencies
	•	PHP 8.x – Bahasa pemrograman sisi server
	•	MySQL – Sistem manajemen basis data relasional
	•	Apache Web Server – Web server yang disediakan melalui XAMPP
	•	phpMyAdmin – Tools untuk pengelolaan dan administrasi database

Frontend Dependencies
	•	HTML5 – Bahasa markup untuk struktur halaman
	•	CSS3 – Styling antarmuka
	•	Bootstrap 5 – Framework UI (menggunakan CDN)
	•	JavaScript (Vanilla JS) – Logika dan interaksi sisi klien

Development Environment
	•	XAMPP – Environment pengembangan lokal (Apache, PHP, MySQL)

Catatan: Karena proyek ini menggunakan PHP Native dan library frontend berbasis CDN, maka tidak diperlukan file package.json untuk manajemen dependensi.
