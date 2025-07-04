# Laravel Inventory Web

Aplikasi web manajemen inventory berbasis Laravel, mendukung manajemen produk, gudang, stok masuk/keluar/opname, dan pencatatan produk hilang. Sistem memiliki pembagian hak akses antara **Admin** dan **Customer**.

---

## 🚀 Fitur Utama

### 👤 Customer
- Login dan logout
- CRUD Produk
- CRUD Produk Hilang
- Pencarian Produk Hilang

### 👑 Admin (akses penuh)
- Semua fitur customer
- Manajemen:
  - Admin
  - Customer
  - Supplier
  - Gudang & Rak
  - Kategori Produk
- Stok:
  - Stok Masuk
  - Stok Keluar
  - Stok Opname
- Approve / Reject:
  - Produk Hilang
  - Stok Masuk / Keluar / Opname
- Ekspor PDF dan Excel

---

## 🛠️ Instalasi

Ikuti langkah-langkah berikut untuk menginstal aplikasi Laravel Inventory:

```bash
# 1. Clone repository
git clone https://github.com/Imanilom/ZavaStock.git

# 2. Masuk ke direktori proyek
cd web-inventory

# 3. Install dependency
composer install

# 4. Copy file environment
cp .env.example .env

# 5. Generate key aplikasi
php artisan key:generate

# 6. Konfigurasi database di file .env

# 7. Jalankan migrasi (dan seeder jika tersedia)
php artisan migrate --seed

# 8. Jalankan server lokal
php artisan serve
