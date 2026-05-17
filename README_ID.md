# Sistema Rental AC (Air Conditioner Rental System)

Sistem manajemen rental air conditioner berbasis web dengan Laravel 12 dan MySQL.

## 🎯 Fitur Utama

- **Manajemen AC**: Tambah, edit, hapus, dan lihat daftar unit air conditioner
- **Manajemen Rental**: Kelola transaksi rental AC dengan tanggal mulai dan berakhir
- **Kategori AC**: Organisir AC berdasarkan tipe dan kapasitas
- **Status Tracking**: Pantau status AC (tersedia, disewa, maintenance)
- **Dashboard**: Ringkasan statistik penjualan dan inventori AC
- **Autentikasi**: Sistem login/register untuk pengguna

## 📋 Requirements

- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js (optional, untuk frontend)

## 🚀 Instalasi

### 1. Clone atau download project
```bash
cd c:\Users\ASUS\Documents\tugas akhir\website
```

### 2. Instalasi dependencies
```bash
composer install
```

### 3. Setup environment
```bash
cp .env.example .env
```

Edit `.env` dengan database configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_ac
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate app key
```bash
php artisan key:generate
```

### 5. Jalankan migrations
```bash
php artisan migrate
```

### 6. Seed kategori data
```bash
php artisan db:seed --class=CategorySeeder
```

### 7. Jalankan server
```bash
php artisan serve
```

Website akan accessible di: `http://127.0.0.1:8000`

## 📁 Struktur Folder

```
project/
├── app/
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── Rental.php
│   │   └── Category.php
│   └── Http/
│       └── Controllers/
│           ├── AdminController.php
│           └── RentalController.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── dashboard.blade.php
│       ├── admin/
│       └── rentals/
├── routes/
│   └── web.php
└── .env
```

## 🗄️ Database Schema

### Categories Table
- id (Primary Key)
- name (String, Unique)
- description (Text)
- timestamps

### Air Conditioners Table
- id (Primary Key)
- category_id (Foreign Key)
- model (String, Unique)
- description (Text)
- cooling_capacity (Decimal - BTU)
- type (Enum: window, split, portable, central)
- rental_price_per_day (Decimal)
- rental_price_per_month (Decimal)
- status (Enum: available, rented, maintenance, inactive)
- serial_number (String, Unique)
- timestamps

### Rentals Table
- id (Primary Key)
- user_id (Foreign Key)
- air_conditioner_id (Foreign Key)
- rental_start (DateTime)
- rental_end (DateTime, Nullable)
- rental_type (Enum: daily, monthly)
- total_price (Decimal)
- status (Enum: active, completed, cancelled)
- customer_name (String)
- customer_phone (String)
- customer_address (Text)
- notes (Text, Nullable)
- timestamps

## 🔐 Autentikasi

Sistem menggunakan Laravel Breeze dengan Blade templates. User harus login untuk mengakses fitur manajemen.

Routes yang dilindungi:
- `/dashboard` - Dashboard utama
- `/admin/*` - Manajemen Admin
- `/rentals/*` - Manajemen Rental

## 🛠️ Development Commands

### Database Commands
```bash
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback migrations
php artisan db:seed              # Run all seeders
php artisan db:wipe              # Drop all tables
```

### Model & Migration Generation
```bash
php artisan make:model ModelName
php artisan make:model ModelName -m  # With migration
php artisan make:seeder SeederName
```

### Server
```bash
php artisan serve                           # Start server
php artisan serve --host=0.0.0.0 --port=80 # Custom host/port
```

## 📊 API Routes

### Admin Management Routes
- `GET /admin` - List all AC
- `GET /admin/create` - Create form
- `POST /admin` - Store AC
- `GET /admin/{id}` - Show AC details
- `GET /admin/{id}/edit` - Edit form
- `PUT /admin/{id}` - Update AC
- `DELETE /admin/{id}` - Delete AC

### Rental Routes
- `GET /rentals` - List all rentals
- `GET /rentals/create` - Create rental form
- `POST /rentals` - Store rental
- `GET /rentals/{id}` - Show rental details
- `GET /rentals/{id}/edit` - Edit rental form
- `PUT /rentals/{id}` - Update rental
- `DELETE /rentals/{id}` - Delete rental

## 🎨 Frontend

Frontend menggunakan:
- **Bootstrap 5** - CSS Framework
- **Blade Templates** - Laravel templating
- **Responsive Design** - Mobile-friendly layout

## 📝 Contoh Penggunaan

### Menambah AC Baru
1. Login ke system
2. Pergi ke menu "Daftar AC" → "Tambah AC"
3. Isi form dengan detail AC
4. Klik "Simpan"

### Membuat Rental
1. Pergi ke menu "Transaksi Rental" → "Buat Rental"
2. Pilih AC yang akan disewa
3. Isi data pelanggan dan tanggal rental
4. Klik "Simpan"

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL service running
- Check `.env` database configuration
- Verify database credentials

### Migration Error
```bash
php artisan migrate:reset --force  # Reset migrations
php artisan db:wipe               # Clear database
php artisan migrate               # Run again
```

### Permission Error
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 📞 Support

Untuk bantuan lebih lanjut, silakan hubungi developer atau cek dokumentasi Laravel di https://laravel.com/docs

## 📄 License

MIT License - Bebas digunakan untuk keperluan komersial maupun non-komersial

---

**Version**: 1.0.0  
**Last Updated**: April 2026  
**Developer**: AC Rental System Team
