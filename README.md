# Sistem Audit

Sistem ini dibangun menggunakan Laravel framework untuk menangani proses audit internal perusahaan.

## Fitur Utama

- Manajemen audit
- Pelaporan hasil audit
- Tracking tindak lanjut
- Dashboard analitik
- Manajemen pengguna dan peran

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM
- Tailwind CSS

## Instalasi

1. Clone repository
```bash
git clone [url-repository]
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file .env
```bash
cp .env.example .env
```

4. Generate key aplikasi
```bash
php artisan key:generate
```

5. Migrasi database
```bash
php artisan migrate
```

6. Jalankan seeder (opsional)
```bash
php artisan db:seed
```

7. Install dan konfigurasi Tailwind CSS
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

8. Compile assets
```bash
npm run dev
```

9. Jalankan server
```bash
php artisan serve
```

## Teknologi yang Digunakan

- Laravel Framework
- Tailwind CSS untuk styling
- MySQL Database
- Node.js & NPM untuk manajemen assets

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
