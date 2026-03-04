# Dokumentasi Struktur Folder Backend (Laravel)

Berikut adalah penjelasan mengenai struktur folder dan file utama pada proyek Backend Lutech Website yang menggunakan framework Laravel:

## 📁 `app/`

Berisi kode inti (_core code_) aplikasi Laravel, termasuk pengaturan logika bisnis.

- **`Http/`**: Menangani permintaan masuk (HTTP requests) dari frontend.
    - **`Controllers/`**: Mengartikan permintaan HTTP dan meresponsnya. Bagian utama yang mengatur alur logika untuk setiap rute API.
    - **`Requests/`**: Berisi _Form Requests_ untuk memvalidasi data masukan dari frontend sebelum diproses oleh Controller.
    - **`Resources/`**: (_API Resources_) Digunakan untuk memodifikasi formasi data (transformasi data) dari Model ke dalam format JSON yang bersih saat dikirim sebagai respons API.
- **`Models/`**: Berisi kelas _Eloquent_ (ORM Laravel) yang merepresentasikan struktur tabel di database dan menyediakan alat untuk manipulasi data.
- **`Observers/`**: Berisi kelas yang _listen_ atau mendengarkan suatu peristiwa di Eloquent (seperti saat data dibuat, diupdate, atau dihapus) untuk menjalankan aksi lanjutan secara otomatis.
- **`Providers/`**: Tempat mendaftarkan berbagai layanan (_Service Providers_) bawaan aplikasi (seperti RouteServiceProvider, AppServiceProvider).
- **`Traits/`**: Berisi kumpulan fungsi (Traits) PHP yang bisa digunakan atau dipakai ulang di berbagai kelas (biasanya Model atau Controller).

## 📁 `database/`

Folder untuk mengelola segala hal terkait struktur dan isi awal dari database.

- **`migrations/`**: Berisi file-file (_blueprint_) pembentuk struktur tabel, kolom, dan indeks di database.
- **`seeders/`**: Berisi _class_ untuk mengisi data dummy (contoh data) atau data awal (seperti admin default) ke dalam database.
- **`factories/`**: (_Model Factories_) Terhubung langsung dengan seeder dan biasa digunakan untuk membuat ratusan/ribuan data dummy menggunakan generator (Faker).

## 📁 `routes/`

Tempat semua definisi _endpoints_ atau alamat URL aplikasi disimpan.

- **`api.php`**: Khusus menampung _routes_ untuk API (yang akan dikonsumsi oleh Frontend). Secara otomatis akan ditambahkan prefiks `/api` pada URL-nya.
- **`web.php`**: Menampung _routes_ untuk halaman antarmuka web langsung jika ada. Biasanya mengaktifkan _session state_ dan proteksi CSRF.
- **`console.php`**: Tempat mendaftarkan perintah khusus atau _Artisan commands_.

## 📁 Direktori Root Lainnya

- **`bootstrap/`**: Folder yang menangani proses awal aplikasi dijalankan (_bootstrapping_) dan berisi folder `cache` untuk optimasi.
- **`config/`**: Semua file konfigurasi (_database, mail, session, logging_, dll) berada di sini.
- **`public/`**: Folder tempat menyimpan `index.php` (titik masuk dari server) dan aset web yang dapat diakses publik (seperti gambar unggahan).
- **`resources/`**: Digunakan untuk menyimpan file mentah jika ada (seperti file tampilan/views Blade, file terjemahan bahasa).
- **`storage/`**: Berisi log file aplikasi, _file uploads_ dari user, dan cache file (compiled blade templates, dll).
- **`tests/`**: Folder tempat menulis automated testing menggunakan PHPUnit.
- **`.env`**: File sentral tempat menyimpan sandi, _keys_, konfigurasi _database_ secara khusus untuk server (lokal/produksi).
- **`artisan`**: File inti untuk mengeksekusi perintah CLI `php artisan`.
- **`composer.json`**: Daftar konfigurasi _dependency management_ menggunakan Composer (mendefinisikan package PHP pihak ketiga yang dipakai di project).
- **`phpunit.xml`**: File konfigurasi untuk menjalankan tes otomatis PHPUnit.
