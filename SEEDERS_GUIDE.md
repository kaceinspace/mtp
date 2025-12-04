# 🌱 Database Seeders - Quick Reference

## ✅ Seeders yang Tersedia

### 1. **DatabaseSeeder** (Main Seeder)
Seeder utama yang menjalankan semua seeder lainnya.

**Data yang di-seed:**
- 4 Roles: `admin`, `guru_pembimbing`, `guru_penguji`, `siswa`
- 19 Permissions (project, task, document, review, user, system)
- 4 Default Users dengan passwords

**Run:**
```bash
php artisan db:seed
```

---

### 2. **DocumentCategorySeeder**
**Total:** 14 kategori dokumen

**Categories:**
- ✅ Proposal (Required)
- ✅ BRD - Business Requirement Document (Required)
- ✅ Flowchart (Required)
- ✅ Wireframe / Mockup (Required)
- ✅ Database Design (Required)
- ✅ Source Code (Required)
- 📄 API Documentation
- ✅ Testing Documentation (Required)
- ✅ User Manual (Required)
- ✅ Laporan Akhir (Required)
- ✅ Presentasi (Required)
- 📸 Screenshot
- 🎥 Video Demo
- 📎 Lainnya

**Run individually:**
```bash
php artisan db:seed --class=DocumentCategorySeeder
```

---

### 3. **TestingTypeSeeder**
**Total:** 8 jenis testing

**Types:**
- 🔬 Unit Test
- 🔗 Integration Test
- 🖥️ UI Test
- 🔌 API Test
- ⚡ Performance Test
- 🔒 Security Test
- ✅ User Acceptance Test (UAT)
- 🔄 Regression Test

**Run:**
```bash
php artisan db:seed --class=TestingTypeSeeder
```

---

### 4. **GradingComponentSeeder**
**Total:** 8 komponen penilaian

**Components (Total Weight: 100%):**
1. **Proposal** (10%) - Kelengkapan dan kualitas proposal
2. **Analisis & Desain** (15%) - Use case, flowchart, ERD
3. **UI/UX Design** (10%) - Wireframe, mockup, usability
4. **Implementasi (Coding)** (25%) - Kualitas kode, functionality
5. **Testing** (10%) - Test documentation, coverage, bug fixing
6. **Dokumentasi** (10%) - Kelengkapan dokumen, user manual
7. **Deployment** (10%) - Server config, application running
8. **Presentasi** (10%) - Penguasaan materi, demo aplikasi

**Run:**
```bash
php artisan db:seed --class=GradingComponentSeeder
```

---

### 5. **AchievementSeeder**
**Total:** 16 achievements

**Categories:**
- **Project** (2): First Step, Project Master
- **Task** (2): Task Killer, Early Bird
- **Streak** (2): Consistency King, Marathon Runner
- **Quality** (5): Perfect Score, Bug Hunter, Code Reviewer, Document Master, Test Champion
- **Time** (2): Night Owl, Speed Demon
- **Special** (3): Team Player, Mentor, Innovator

**Rarity:**
- Common: 7 achievements
- Rare: 6 achievements
- Epic: 2 achievements
- Legendary: 1 achievement

**Run:**
```bash
php artisan db:seed --class=AchievementSeeder
```

---

### 6. **ProjectTemplateSeeder**
**Total:** 5 templates

**Templates:**
1. **Template UKK - Website E-Commerce** (RPL)
   - 8 milestones, 101 days
   - Fitur: Login, Produk, Keranjang, Checkout, Admin Dashboard

2. **Template UKK - Sistem Informasi Sekolah** (RPL)
   - 6 milestones, 90 days
   - Fitur: Multi-role, Siswa, Guru, Jadwal, Nilai, Absensi

3. **Template UKK - REST API Backend** (RPL)
   - 6 milestones, 66 days
   - Fitur: JWT Auth, CRUD, Validation, API Docs

4. **Template UKK TKJ - Web Server Setup** (TKJ)
   - 6 milestones, 48 days
   - Fitur: Linux, Web Server, Database, SSL, Firewall

5. **Template Project Akhir - Mobile App**
   - 7 milestones, 105 days
   - Fitur: UI/UX, Backend API, Mobile Dev, Push Notif

**Run:**
```bash
php artisan db:seed --class=ProjectTemplateSeeder
```

---

### 7. **SystemSettingSeeder**
**Total:** 40+ system settings

**Groups:**
- **General** (7): App name, school info, timezone, date format
- **Notification** (4): Enable email/WA, reminder days
- **Email** (6): SMTP configuration
- **WhatsApp** (2): API URL & key
- **Project** (4): Max members, auto progress, code prefix
- **Upload** (3): Max size, allowed types, versioning
- **Grading** (3): Passing grade, scale, revision
- **Security** (4): Session lifetime, password, 2FA, login attempts
- **Backup** (3): Auto backup, frequency, retention
- **Achievement** (2): Enable achievements, leaderboard
- **Maintenance** (2): Mode, message

**Run:**
```bash
php artisan db:seed --class=SystemSettingSeeder
```

---

## 🚀 Usage Guide

### Run All Seeders
```bash
php artisan db:seed
```

### Run Specific Seeder
```bash
php artisan db:seed --class=AchievementSeeder
```

### Fresh Migration + Seed
```bash
php artisan migrate:fresh --seed
```

### Refresh Database (Rollback + Migrate + Seed)
```bash
php artisan migrate:refresh --seed
```

---

## 👥 Default Users

Setelah seeding, login dengan kredensial berikut:

| Role | Email | Password | User Type |
|------|-------|----------|-----------|
| **Admin** | admin@tracking.test | password | admin |
| **Guru Pembimbing** | guru@tracking.test | password | guru |
| **Guru Penguji** | penguji@tracking.test | password | guru_penguji |
| **Siswa** | siswa@tracking.test | password | siswa |

---

## 🔑 Permissions Matrix

### Admin
✅ All permissions (19 total)

### Guru Pembimbing
- ✅ create_project, edit_project, view_all_projects
- ✅ create_task, edit_task, assign_task
- ✅ upload_document, approve_document
- ✅ review_project, grade_project, approve_milestone
- ✅ view_reports, export_data

### Guru Penguji
- ✅ view_all_projects
- ✅ review_project
- ✅ grade_project
- ✅ view_reports

### Siswa
- ✅ create_task
- ✅ edit_task
- ✅ upload_document

---

## 📊 Data Statistics

After seeding, you will have:
- ✅ **4 Roles**
- ✅ **19 Permissions**
- ✅ **4 Users**
- ✅ **14 Document Categories**
- ✅ **8 Testing Types**
- ✅ **8 Grading Components** (100% weight)
- ✅ **16 Achievements**
- ✅ **5 Project Templates**
- ✅ **40+ System Settings**

**Total:** ~94 initial database records

---

## 🎯 Next Steps After Seeding

1. **Test Login** dengan salah satu user default
2. **Create Project** menggunakan template yang tersedia
3. **Upload Documents** sesuai kategori yang diperlukan
4. **Assign Tasks** ke anggota project
5. **Track Progress** dan update status
6. **Review & Grade** project siswa
7. **Check Achievements** yang sudah earned

---

## ⚠️ Important Notes

### Database Requirements
Pastikan database sudah dibuat sebelum running migrations & seeders:

```sql
CREATE DATABASE tracking_project_management;
```

### Environment Setup
Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tracking_project_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Production Seeding
⚠️ **PERHATIAN:** Jangan run seeder di production setelah sistem sudah live!

Untuk production:
1. Hanya seed data master (categories, templates, settings)
2. Jangan seed user dummy
3. Backup database sebelum seeding

**Selective Seeding:**
```bash
php artisan db:seed --class=DocumentCategorySeeder
php artisan db:seed --class=TestingTypeSeeder
php artisan db:seed --class=GradingComponentSeeder
php artisan db:seed --class=SystemSettingSeeder
```

---

## 🔄 Re-seeding Strategy

Jika perlu re-seed (development only):

```bash
# Drop all tables, migrate, and seed
php artisan migrate:fresh --seed

# Or just rollback and re-run
php artisan migrate:refresh --seed
```

---

## 📝 Custom Seeder Example

Jika ingin membuat seeder tambahan:

```bash
php artisan make:seeder YourCustomSeeder
```

Edit `database/seeders/YourCustomSeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class YourCustomSeeder extends Seeder
{
    public function run(): void
    {
        // Your seeding logic here
    }
}
```

Tambahkan ke `DatabaseSeeder.php`:
```php
$this->call([
    YourCustomSeeder::class,
]);
```

---

## 🐛 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Foreign key constraint fails"
Pastikan urutan seeding benar (users dulu, baru yang lain)

### Error: "Duplicate entry"
Drop database dan buat ulang:
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📞 Support

Dokumentasi lengkap ada di:
- `DATABASE_SCHEMA.md` - Complete database schema
- `SETUP_SUMMARY.md` - Setup guide & roadmap

**Happy Seeding! 🌱**
