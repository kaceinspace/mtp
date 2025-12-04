# 🚀 Tracking Project Management System
### Sistem Manajemen Proyek Akhir SMK Assalaam Bandung

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

---

## 📖 Tentang Project

**Tracking Project Management System** adalah aplikasi web berbasis Laravel yang dirancang khusus untuk **SMK Assalaam Bandung** guna memudahkan manajemen proyek akhir siswa. Sistem ini memfasilitasi kolaborasi antara admin, guru pembimbing, guru penguji, dan siswa dalam mengelola project dari awal hingga selesai.

### 🎯 Tujuan Utama
- ✅ Memudahkan monitoring progress project siswa secara real-time
- ✅ Meningkatkan efisiensi komunikasi antara pembimbing dan siswa
- ✅ Menyediakan sistem penilaian yang terstruktur dan transparan
- ✅ Mengelola dokumentasi project secara terpusat
- ✅ Mengotomatisasi reminder dan notifikasi deadline

---

## ✨ Fitur Utama

### 👥 **User Management** (✅ COMPLETED - Phase 1)
Sistem manajemen user dengan 4 role berbeda:
- 🛡️ **Admin** - Akses penuh sistem, kelola semua user
- 📚 **Guru Pembimbing** - Bimbing siswa, monitor progress
- ✅ **Guru Penguji** - Evaluasi dan nilai project siswa
- 🎓 **Siswa** - Kelola project dan task pribadi

**Fitur User Management:**
- ✨ CRUD User lengkap dengan validation
- ✨ Filter & Search (nama, email, NISN, NIP, tipe, jurusan, status)
- ✨ Toggle Status Aktif/Nonaktif
- ✨ Form dinamis based on user type
- ✨ Stats cards per user type
- ✨ Modern UI dengan gradient & animations
- ✨ Role-based access control dengan Laravel Gates

### 🎨 **Dashboard** (✅ COMPLETED)
Dashboard yang disesuaikan untuk setiap role dengan informasi relevan:
- **Admin Dashboard** - Overview sistem, stats user, activity logs
- **Siswa Dashboard** - Progress project, task list, deadline reminders
- **Guru Dashboard** - Daftar bimbingan, review pending, siswa list
- **Penguji Dashboard** - Project untuk dinilai, jadwal presentasi, history penilaian

### 🔐 **Authentication System** (✅ COMPLETED)
- Laravel Breeze dengan custom design
- Email verification
- Password reset functionality
- Remember me feature
- School branding (Blue & Yellow theme)

### 🏠 **Landing Page** (✅ COMPLETED)
- Modern welcome page dengan school branding
- Features showcase
- Stats display
- Call-to-action sections
- Responsive design

---

## 📋 Roadmap Development

### **Phase 1: Core System** ✅ COMPLETED
- [x] User Management & Roles
- [x] Authentication System
- [x] Dashboard untuk semua role
- [x] Landing Page

### **Phase 2: Collaboration Features** 🔄 IN PROGRESS
- [ ] Project Management (CRUD)
- [ ] Task Management System
- [ ] Team Management
- [ ] File Upload & Storage

### **Phase 3: Academic Features** ⏳ UPCOMING
- [ ] Bimbingan Management
- [ ] Review & Feedback System
- [ ] Progress Tracking Timeline

### **Phase 4: Assessment & Evaluation** ⏳ PLANNED
- [ ] Penilaian Project
- [ ] Presentasi Scheduling
- [ ] Grade Management
- [ ] Reporting System

### **Phase 5: Advanced Features** 💡 FUTURE
- [ ] Real-time Notifications
- [ ] Activity Logs
- [ ] Analytics Dashboard
- [ ] Export Reports (PDF/Excel)

---

## 🛠️ Tech Stack

### Backend
- **Laravel 11.x** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL/PostgreSQL** - Database
- **Laravel Breeze** - Authentication

### Frontend
- **Tailwind CSS 3.x** - Utility-first CSS Framework
- **Alpine.js 3.x** - Lightweight JavaScript Framework
- **Blade Templates** - Laravel Templating Engine
- **Vite 5.x** - Frontend Build Tool

### Tools & Libraries
- **Composer** - PHP Dependency Manager
- **NPM** - Node Package Manager
- **Git** - Version Control

---

## 📦 Installation

### Prerequisites
```bash
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Git
```

### Step 1: Clone Repository
```bash
git clone https://github.com/kaceinspace/mtp.git
cd tracking-project-management
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration
Edit file `.env` dan sesuaikan database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tracking_project
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Run seeders (optional - untuk data dummy)
php artisan db:seed
```

### Step 6: Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 7: Run Application
```bash
# Start Laravel development server
php artisan serve

# Access aplikasi di: http://localhost:8000
```

---

## 👤 Default Users (After Seeding)

| Role | Email | Password | NISN/NIP |
|------|-------|----------|----------|
| **Admin** | admin@smkassalaambandung.sch.id | password | 198501012010011001 |
| **Guru Pembimbing** | budi.santoso@smkassalaam.sch.id | password | 199001012015011002 |
| **Guru Penguji** | siti.nurhaliza@smkassalaam.sch.id | password | 198805012016012001 |
| **Siswa** | ahmad.fauzi@student.smkassalaam.sch.id | password | 0051234567 |

---

## 📁 Project Structure

```
tracking-project-management/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/
│   │           └── UserController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── UserProfile.php
│   └── Providers/
│       └── AppServiceProvider.php (Gates)
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   └── create_user_profiles_table.php
│   └── seeders/
│       └── UserSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── layouts/
│       │   ├── dashboard.blade.php
│       │   └── guest.blade.php
│       ├── includes/
│       │   └── dashboard/
│       │       ├── side.blade.php
│       │       ├── nav.blade.php
│       │       └── foot.blade.php
│       ├── pages/
│       │   ├── admin/
│       │   │   ├── dashboard.blade.php
│       │   │   └── users/
│       │   │       ├── index.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       └── show.blade.php
│       │   ├── siswa/
│       │   ├── guru/
│       │   └── penguji/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       └── welcome.blade.php
└── routes/
    └── web.php
```

---

## 🎨 UI/UX Features

### Design System
- **Primary Colors:** Blue (#2563EB) to Indigo (#4F46E5)
- **Accent Color:** Yellow (#EAB308)
- **Typography:** Inter font family
- **Icons:** Heroicons (Tailwind UI)

### Modern UI Elements
- ✨ Gradient backgrounds & borders
- ✨ Smooth hover animations & transitions
- ✨ Shadow effects dengan color matching
- ✨ Rounded corners (rounded-xl, rounded-2xl)
- ✨ Emoji icons untuk visual enhancement
- ✨ Color-coded badges per user type
- ✨ Responsive grid layouts
- ✨ Progress indicators & stats cards

---

## 🔒 Security Features

- ✅ CSRF Protection (Laravel default)
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection
- ✅ Password Hashing (Bcrypt)
- ✅ Email Verification
- ✅ Role-based Access Control
- ✅ Input Validation & Sanitization

---

## 🚀 Performance Optimization

- ⚡ Vite for fast asset compilation
- ⚡ Lazy loading images
- ⚡ Database indexing
- ⚡ Query optimization
- ⚡ Asset minification (production)
- ⚡ Browser caching headers

---

## 📱 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS & Android)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Developer

**Project developed for SMK Assalaam Bandung**

- GitHub: [@kaceinspace](https://github.com/kaceinspace)
- Repository: [mtp](https://github.com/kaceinspace/mtp)

---

## 📧 Contact & Support

Untuk pertanyaan, bug reports, atau feature requests:
- Email: admin@smkassalaambandung.sch.id
- Create an issue di GitHub repository

---

## 🙏 Acknowledgments

- Laravel Framework Team
- Tailwind CSS Team
- SMK Assalaam Bandung
- All contributors

---

<p align="center">
  <strong>Made with ❤️ for SMK Assalaam Bandung</strong><br>
  © 2025 Tracking Project Management System
</p>
