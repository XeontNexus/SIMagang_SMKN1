# SIMagang - Sistem Informasi Magang SMK

Aplikasi web untuk mengelola sistem magang siswa SMK dengan 4 role:
- Siswa: Input logbook, presensi, dan pengajuan surat izin magang
- Guru Pembimbing: Monitoring dan penilaian siswa
- Dudi Mitra: Pengumuman tempat magang dan pengawasan
- Admin: Manajemen semua role

## Teknologi
- **Backend**: PHP 8+, MySQL
- **Frontend**: PHP Murni, CSS3, JavaScript Vanilla
- **Database**: MySQL
- **UI Framework**: Bootstrap 5 + Custom CSS

## Struktur Folder
```
SIMagang2/
├── backend/           # API PHP
│   ├── api/          # Endpoints API
│   ├── config/       # Konfigurasi database
│   ├── models/       # Model data
│   ├── controllers/  # Logic controller
│   ├── middleware/   # Middleware auth
│   └── utils/        # Helper functions
├── views/            # Halaman PHP
│   ├── auth/         # Login, Register, Forgot Password
│   ├── siswa/        # Dashboard & fitur siswa
│   ├── guru/         # Dashboard & fitur guru
│   ├── dudi/         # Dashboard & fitur dudi
│   ├── admin/        # Dashboard & fitur admin
│   └── components/   # Header, Footer, Layout
├── assets/           # Static files
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript
│   └── images/       # Gambar
├── database/         # File SQL dan migrasi
└── docs/            # Dokumentasi
```

## Instalasi
1. **Setup Database**
   ```bash
   # Import database
   mysql -u root -p < database/simagang.sql
   ```

2. **Konfigurasi Database**
   - Edit `backend/config/database.php`
   - Sesuaikan hostname, username, password, dan database name

3. **Web Server**
   - Gunakan XAMPP/WAMP/LAMP
   - Pastikan PHP 8+ dan MySQL aktif
   - Point document root ke folder `SIMagang2`

4. **Akses Aplikasi**
   ```bash
   # Browser
   http://localhost/SIMagang2

5. **Akses Dashboard**
   - Siswa: http://localhost/SIMagang2/siswa
   - Guru: http://localhost/SIMagang2/guru
   - Dudi: http://localhost/SIMagang2/dudi
   - Admin: http://localhost/SIMagang2/admin

6. **Login Credentials**

   Role	Email	Password
   👨‍🎓 Siswa	test@test.com	test123
   👨‍🏫 Guru	guru@test.com	guru123
   🏢 DUDI	dudi@test.com	dudi123
   👨‍💼 Admin	admin@test.com	admin123
      ```

## Fitur

### ✅ Selesai Implementasi:
- **Authentication System**
  - Login dengan role-based redirect
  - Register untuk siswa, guru, dudi
  - Forgot password dengan token reset
  - Session management PHP

- **Dashboard Multi-Role**
  - **Siswa**: Stats logbook, presensi, izin, nilai
  - **Guru**: Monitoring siswa, review logbook, penilaian
  - **Dudi**: Pengumuman, tempat magang, monitoring
  - **Admin**: Manajemen user, overview sistem, laporan

- **UI/UX**
  - Responsive design (mobile & desktop)
  - Bootstrap 5 + custom CSS
  - Interactive sidebar navigation
  - Real-time notifications
  - Loading states & animations

### 🔄 Dalam Pengembangan:
- Fitur siswa (logbook, presensi, pengajuan izin)
- Fitur guru (monitoring & penilaian)
- Fitur dudi (pengumuman & pengawasan)
- Fitur admin (manajemen lengkap)

## Default Login
- **Admin**: admin@simagang.com / password
- **Role lain**: Register melalui halaman registrasi

## API Endpoints
- `POST /backend/api/auth.php?action=login` - Login user
- `POST /backend/api/auth.php?action=register` - Register user
- `POST /backend/api/auth.php?action=forgot-password` - Forgot password

## Kontribusi
1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## License
© 2024 SIMagang. All rights reserved.
