# 📚 Database Schema Documentation - Tracking Project Management

Sistem tracking project management untuk SMK dengan fitur lengkap untuk mengelola project siswa, task management, review & grading, testing, deployment, dan monitoring progress.

---

## 📊 Database Overview

Total: **60+ Tables** yang terbagi dalam beberapa kategori:

### 1️⃣ User Management (5 tables)
- `users` - Data user (siswa, guru, admin)
- `roles` - Role sistem
- `permissions` - Permission sistem
- `role_user` - Pivot table role-user
- `permission_role` - Pivot table permission-role

### 2️⃣ Project Management (3 tables)
- `projects` - Data project
- `project_members` - Anggota project (untuk kelompok)
- `project_templates` - Template project

### 3️⃣ Task Management (5 tables)
- `milestones` - Fase/milestone project
- `tasks` - Task detail
- `task_dependencies` - Dependency antar task
- `task_comments` - Komentar task
- `task_time_logs` - Log waktu kerja

### 4️⃣ Document Management (4 tables)
- `document_categories` - Kategori dokumen
- `documents` - File dokumen dengan versioning
- `document_reviews` - Review dokumen
- `document_access_logs` - Log akses dokumen

### 5️⃣ Communication (6 tables)
- `notifications` - Notifikasi sistem
- `notification_settings` - Preferensi notifikasi user
- `chat_rooms` - Ruang chat
- `chat_room_members` - Anggota chat room
- `chat_messages` - Pesan chat
- `chat_message_reactions` - Reaksi emoji

### 6️⃣ Review & Grading (8 tables)
- `project_reviews` - Review project
- `milestone_reviews` - Review milestone
- `task_reviews` - Review task
- `grading_components` - Komponen penilaian
- `grades` - Nilai per komponen
- `final_grades` - Nilai akhir
- `review_requests` - Permintaan review
- `revision_history` - History revisi

### 7️⃣ Testing & Deployment (8 tables)
- `testing_types` - Jenis testing
- `testing_cases` - Test case
- `test_executions` - Hasil eksekusi test
- `bug_reports` - Laporan bug
- `deployments` - Info deployment
- `deployment_logs` - Log deployment
- `api_endpoints` - Dokumentasi API
- `database_schemas` - Schema database project

### 8️⃣ Progress Tracking (8 tables)
- `activity_logs` - Log aktivitas user
- `progress_logs` - Log progress update
- `statistics_snapshots` - Snapshot statistik
- `timeline_comparisons` - Perbandingan timeline
- `work_sessions` - Sesi kerja
- `productivity_metrics` - Metrik produktivitas
- `dashboard_stats` - Cache statistik dashboard
- `export_history` - History export

### 9️⃣ Additional Features (10 tables)
- `achievements` - Badge achievement
- `user_achievements` - Achievement user
- `help_articles` - Tutorial & panduan
- `example_projects` - Contoh project
- `document_templates` - Template dokumen
- `readiness_checklists` - Checklist kesiapan ujikom
- `plagiarism_checks` - Cek plagiarisme
- `project_qr_codes` - QR code project
- `system_settings` - Setting sistem
- `announcements` - Pengumuman
- `feedback` - Feedback & suggestion

---

## 🔑 Key Relationships

### User → Projects
```
User (created_by) → hasMany → Projects
User → belongsToMany → Projects (project_members)
```

### Project → Tasks → Subtasks
```
Project → hasMany → Milestones → hasMany → Tasks
Task → hasMany → Subtasks (self-reference)
Task → belongsToMany → Dependencies (task_dependencies)
```

### Document Versioning
```
Document (parent_document_id) → hasMany → Versions
Document → belongsTo → Project/Milestone/Task
```

### Grading System
```
Project → hasMany → Grades (per component)
Project → hasOne → FinalGrade (nilai akhir)
GradingComponent → hasMany → Grades
```

---

## 🚀 Migration Order

Migrations harus dijalankan dengan urutan berikut:

1. `0001_01_01_000000_create_users_table.php` ✅
2. `2024_12_04_000001_create_roles_and_permissions_tables.php`
3. `2024_12_04_000002_create_projects_table.php`
4. `2024_12_04_000003_create_tasks_table.php`
5. `2024_12_04_000004_create_documents_table.php`
6. `2024_12_04_000005_create_notifications_and_chats_table.php`
7. `2024_12_04_000006_create_reviews_and_grades_table.php`
8. `2024_12_04_000007_create_testing_and_deployment_table.php`
9. `2024_12_04_000008_create_progress_tracking_table.php`
10. `2024_12_04_000009_create_additional_features_table.php`

### Running Migrations

```bash
# Run all migrations
php artisan migrate

# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Rollback last migration
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset
```

---

## 📋 Model List

Semua model sudah dibuat dengan relationship lengkap:

### Core Models
- `User` - dengan soft deletes
- `Role`
- `Permission`
- `Project` - dengan soft deletes
- `ProjectTemplate`
- `Milestone`
- `Task` - dengan soft deletes

### Document Models
- `DocumentCategory`
- `Document` - dengan soft deletes
- `DocumentReview`
- `DocumentAccessLog`

### Communication Models
- `Notification`
- `ChatRoom`
- `ChatMessage` - dengan soft deletes
- `ChatMessageReaction`
- `NotificationSetting`

### Review & Grading Models
- `ProjectReview`
- `MilestoneReview`
- `TaskReview`
- `GradingComponent`
- `Grade`
- `FinalGrade`
- `ReviewRequest`
- `RevisionHistory`

### Testing & Deployment Models
- `TestingType`
- `TestingCase` - dengan soft deletes
- `TestExecution`
- `BugReport` - dengan soft deletes
- `Deployment`
- `DeploymentLog`
- `ApiEndpoint`
- `DatabaseSchema`

### Tracking Models
- `ActivityLog`
- `ProgressLog`
- `StatisticsSnapshot`
- `TimelineComparison`
- `WorkSession`
- `ProductivityMetric`
- `DashboardStat`
- `ExportHistory`

### Feature Models
- `Achievement`
- `HelpArticle`
- `ExampleProject`
- `DocumentTemplate`
- `ReadinessChecklist`
- `PlagiarismCheck`
- `ProjectQrCode`
- `SystemSetting`
- `Announcement`
- `Feedback`

---

## 💡 Usage Examples

### Create a Project
```php
$project = Project::create([
    'code' => 'PRJ-2024-001',
    'title' => 'Sistem E-Commerce',
    'type' => 'individual',
    'category' => 'UKK',
    'created_by' => auth()->id(),
    'jurusan' => 'RPL',
    'kelas' => 'XII RPL 1',
    'tahun_ajaran' => 2024,
    'start_date' => now(),
    'end_date' => now()->addMonths(3),
    'status' => 'not_started',
]);
```

### Add Project Members
```php
$project->members()->attach($userId, [
    'role' => 'member',
    'responsibilities' => 'Backend Developer',
    'is_active' => true,
]);
```

### Create Task with Dependencies
```php
$task = Task::create([
    'project_id' => $project->id,
    'milestone_id' => $milestone->id,
    'title' => 'Buat API Login',
    'priority' => 'high',
    'assigned_to' => $userId,
    'created_by' => auth()->id(),
    'due_date' => now()->addWeek(),
]);

// Add dependency
$task->dependencies()->attach($previousTaskId, [
    'dependency_type' => 'finish_to_start'
]);
```

### Upload Document with Versioning
```php
$document = Document::create([
    'project_id' => $project->id,
    'title' => 'Proposal Project',
    'type' => 'file',
    'file_name' => 'proposal.pdf',
    'file_path' => $path,
    'version' => 1,
    'uploaded_by' => auth()->id(),
    'status' => 'submitted',
]);
```

### Send Notification
```php
Notification::create([
    'user_id' => $userId,
    'from_user_id' => auth()->id(),
    'title' => 'Task Assigned',
    'message' => 'You have been assigned to: ' . $task->title,
    'type' => 'task_assigned',
    'project_id' => $project->id,
    'task_id' => $task->id,
    'priority' => 'medium',
]);
```

### Create Review Request
```php
$request = ReviewRequest::create([
    'project_id' => $project->id,
    'requested_by' => auth()->id(),
    'requested_to' => $guruId,
    'message' => 'Mohon review milestone Desain UI',
    'priority' => 'high',
    'status' => 'pending',
]);
```

### Grade a Project
```php
$grade = Grade::create([
    'project_id' => $project->id,
    'grading_component_id' => $component->id,
    'grader_id' => auth()->id(),
    'score' => 85,
    'weighted_score' => 85 * ($component->weight / 100),
    'notes' => 'Good work!',
    'is_final' => true,
    'graded_at' => now(),
]);
```

### Log Activity
```php
ActivityLog::create([
    'user_id' => auth()->id(),
    'event' => 'updated',
    'subject_type' => 'App\Models\Task',
    'subject_id' => $task->id,
    'description' => 'Updated task status',
    'project_id' => $task->project_id,
    'old_values' => ['status' => 'todo'],
    'new_values' => ['status' => 'in_progress'],
]);
```

---

## 🔐 Important Notes

### Soft Deletes
Models dengan soft deletes:
- `User`
- `Project`
- `Task`
- `Document`
- `ChatMessage`
- `TaskComment`
- `TestingCase`
- `BugReport`

### JSON Casting
Fields yang di-cast sebagai JSON:
- `tags`, `settings`, `checklist` (various tables)
- `attachments`, `mentions` (chat & comments)
- `detailed_scores` (grades)
- `tech_stack`, `environment_variables` (deployments)
- Dan banyak lagi...

### Auto-calculated Fields
- `progress_percentage` (projects, milestones)
- `is_overdue` (projects, tasks)
- `duration_minutes` (work sessions, time logs)
- `weighted_score` (grades)
- `completion_percentage` (readiness checklist)

### Indexes
Indexes sudah ditambahkan pada:
- Foreign keys
- Status fields yang sering di-query
- Date fields untuk filtering
- Composite indexes untuk query optimization

---

## 🎯 Next Steps

1. **Run migrations**: `php artisan migrate`
2. **Create seeders** untuk data dummy (roles, permissions, categories)
3. **Implement Controllers** untuk setiap module
4. **Create API Routes** atau web routes
5. **Build Frontend** (Laravel Blade / Vue / React)
6. **Implement real-time features** dengan Laravel Echo & Pusher
7. **Add queue jobs** untuk notifikasi & export
8. **Setup storage** untuk file uploads
9. **Configure email & WhatsApp gateway**
10. **Testing & deployment**

---

## 📞 Support

Jika ada pertanyaan atau butuh bantuan, silakan buat issue atau hubungi tim development.

**Happy Coding! 🚀**
