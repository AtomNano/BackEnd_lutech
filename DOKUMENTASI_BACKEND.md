# Backend Technical Documentation (Laravel 11)
*Project: Lutech Core API Engine*

Dokumentasi ini merinci arsitektur server-side Lutech yang berfungsi sebagai otak pemrosesan data, otentikasi, dan otomatisasi.

---

## 1. Architecture Implementation
Backend Lutech mengikuti pola **Service-Oriented Architecture** di Laravel:

- **Controllers**: Fokus pada alur kerja HTTP dan pemanggilan *Service/Logic*.
- **Resources**: Menggunakan JSON Resources untuk standarisasi format data yang dikirim ke Frontend.
- **Requests**: Validasi input yang ketat melalui FormRequest untuk menjamin integritas data.
- **Models & Migrations**: Desain skema database yang mendukung multitenancy (Workspace-based).

---

## 2. Authentication System (Sanctum)
Otentikasi dikelola secara modern untuk mendukung SPA dan Integrasi Mobile/Third-party.

- **Bearer Token**: Token dikeluarkan saat login sukses dan harus disertakan dalam Header `Authorization`.
- **Google SSO**: Endpoint `/v1/auth/google` mendukung pertukaran *Google Identity Token* dengan *Lutech Bearer Token*.
- **Remember Me Logic**: Mendukung TTL (Time-to-Live) dinamis; 30 hari untuk sesi permanen, 2 jam untuk sesi sementara.

---

## 3. Multitenancy Strategy
Setiap entitas (Finance, Ticket, Customer) wajib memiliki kolom `workspace_id`.

- **Ownership**: Data hanya dapat diakses/dimanipulasi jika `workspace_id` cocok dengan kepemilikan pengguna yang sedang login (`Auth::id()`).
- **Workspace PIN**: Fitur enkripsi PIN menggunakan `Hash::make()` untuk memberikan lapis keamanan tambahan pada workspace sensitif.

---

## 4. API Endpoints Map (v1)
Aplikasi membagi Route menjadi dua kategori besar:

### ════ Public Endpoints ════
- `POST /api/login` - Tradisional Login.
- `POST /api/v1/auth/google` - Google SSO Integration.
- `GET /api/v1/galleries` - Portofolio Landing.
- `GET /api/v1/track/{query}` - Lacak status servis perbaikan.

### ════ Protected Endpoints ════
- `GET /api/v1/user` - Profil User & Avatar.
- `GET/POST /api/v1/workspaces` - Manajemen Ruang Kerja.
- `Route::prefix('workspaces/{workspace}')` - Grup rute Finance & Goals (Isolasi Workspace).
- `GET/POST /api/v1/tickets` - Manajemen Tiket Servis.
- `GET/POST /api/v1/customers` - Manajemen Database Pelanggan.

---

## 5. Automation Integration (n8n & AI)
Backend memiliki jalur khusus untuk otomatisasi di bawah prefix `v1/n8n`.

- **Security Middleware**: Dilindungi oleh **IP Whitelisting**, hanya mengizinkan request dari alamat IP Server n8n yang terdaftar.
- **Data Ingestion**: Mendukung entri data finansial dan tiket servis secara massal tanpa campur tangan manual UI.

---

## 6. Installation & Maintenance
1. Salin `.env.example` ke `.env` dan konfigurasikan database.
2. Jalankan `composer install` dan `php artisan migrate --seed`.
3. Gunakan `php artisan storage:link` untuk mengaktifkan akses publik ke file avatar/gambar.

---
*Lutech Backend Team - Integrity, Stability, Security.*
