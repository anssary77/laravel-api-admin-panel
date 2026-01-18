# Laravel API & Admin Panel

A comprehensive Laravel-based ecosystem featuring a robust RESTful API, an advanced Admin Panel, and a user-facing dashboard. This project is built with a focus on SOLID principles, real-time features, and a modern user experience.

---

## Login Admin Dashboard

- **Username**: `admin@example.com`
- **Password**: `password`

## Login User Dashboard

- **Username**: `user@example.com`
- **Password**: `password`



## 🚀 Key Features

### 🔐 Authentication & Security
- **JWT-based API Authentication**: Secure login using mobile numbers and passwords via `tymon/jwt-auth`.
- **Role-Based Access Control (RBAC)**: Managed via `spatie/laravel-permission` (Admin, Manager, User).
- **Activity Logging**: Comprehensive tracking of system actions via `spatie/laravel-activitylog`.

### 📝 Post Management
- **Public & Private Feeds**: Users can view a paginated list of posts from other users (15 per page, descriptions limited to 512 characters).
- **Post Creation**: Authenticated users can create posts with titles, detailed descriptions (up to 2KB), and contact information.
- **Bulk Data**: Includes seeders for 150+ realistic posts and 100+ users for testing.

### 🛠 Admin Panel & Tools
- **Dynamic File Manager**: AJAX-powered file explorer with search, image preview, and grid/list view toggles.
- **English/Arabic Dashboard**: Fully localized interface with dynamic charts and statistics.
- **Theme Toggle**: Project-wide support for Dark and Light modes with persistence.
- **Real-time Notifications**: Instant alerts for admins when new posts are created or system updates occur.
- **Support Chat**: Integrated real-time chat between users and the support team.

### 📊 System & Reporting
- **Automated Daily Reports**: Midnight email summaries of daily activity (new posts, users, etc.).
- **Telescope Integration**: Advanced debugging and request monitoring.
- **Swagger Documentation**: Automatically generated API documentation.

---

## 🛠 Setup & Installation

### 1. Prerequisites
- **PHP**: 8.2+
- **Composer**: Latest version
- **Database**: MySQL 8.0+ or PostgreSQL
- **Redis**: Recommended for Queues and Caching

### 2. Quick Installation (Recommended)
We've provided a custom command to handle the entire setup process:

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Generate keys
php artisan key:generate
php artisan jwt:secret

# Initialize project (Runs migrations and seeders)
php artisan install:project "My Project Name"
```

### 3. Manual Setup
If you prefer manual initialization:

```bash
# Run migrations
php artisan migrate

# Seed data (Users, Roles, and 150+ Posts)
php artisan db:seed
```

---

## 🧪 Testing

The project uses **Pest** (built on top of PHPUnit) for a modern testing experience.

### Running Tests
```bash
# Run all tests
php artisan test

# Run tests with coverage (Requires Xdebug)
composer test-coverage
```

### Static Analysis
```bash
# Run PHPStan analysis
composer analyse
```

### API Documentation
View and test API endpoints via Swagger:
1. Generate docs: `php artisan l5-swagger:generate`
2. Access at: `http://localhost:8000/api/documentation`

---

## 📡 Deployment

### 1. Production Optimization
Run these commands to prepare the application for production:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Background Workers
The system relies on queues for notifications, chat messages, and reports:
```bash
# Start a queue worker
php artisan queue:work
```

### 3. Task Scheduling
Ensure the Laravel Scheduler is running to enable daily reports:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔐 Default Access (Seeded Data)

| Role | Username | Password |
|------|----------|----------|
| **Admin** | `admin` | `password` |
| **Manager** | `manager` | `password` |
| **User** | `user` | `password` |

---
*Developed with focus on Clean Code, SOLID principles, and Scalability.*
