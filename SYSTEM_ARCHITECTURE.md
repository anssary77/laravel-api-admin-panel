# Laravel 11 REST API + Admin Panel - System Architecture

## Overview
This document provides a comprehensive system architecture diagram and technical specifications for the Laravel 11 REST API and Admin Panel implementation.

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLIENT LAYER                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │
│  │   Web App   │  │ Mobile App  │  │   API Docs  │  │   Admin UI  │     │
│  │  (Frontend) │  │  (React)    │  │  (Swagger)  │  │  (Blade)    │     │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘     │
│         │              │              │              │                │
│         └──────────────┴──────────────┴──────────────┘                │
└─────────────────────────────────────────────────────────────────────────────┘
                                   │
                                   │ HTTP/HTTPS
                                   │ JSON/XML
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                          APPLICATION LAYER                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                        Laravel 11 Framework                        │    │
│  ├─────────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────┐ │    │
│  │  │   Routes    │  │ Controllers │  │  Middleware │  │  Models │ │    │
│  │  │  (api.php)  │  │   (API)     │  │   (RBAC)    │  │         │ │    │
│  │  │  (web.php)  │  │   (Admin)   │  │             │  │         │ │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────┘ │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────┐ │    │
│  │  │   Views     │  │   Services  │  │   Events    │  │  Jobs   │ │    │
│  │  │  (Blade)    │  │             │  │             │  │         │ │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────┘ │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────┘
                                   │
                                   │ Eloquent ORM
                                   │ Database Queries
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                          Database (SQLite)                           │    │
│  ├─────────────────────────────────────────────────────────────────────┤    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────┐ │    │
│  │  │    users    │  │   roles     │  │permissions  │  │settings │ │    │
│  │  │             │  │             │  │             │  │         │ │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────┘ │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────┐ │    │
│  │  │activity_logs│  │model_has_   │  │role_has_    │  │  Cache  │ │    │
│  │  │             │  │permissions  │  │permissions  │  │         │ │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────┘ │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Technology Stack

### Backend Framework
- **Laravel 11**: Modern PHP framework with built-in API support
- **PHP 8.2+**: Latest PHP version with improved performance and features

### Authentication & Authorization
- **Laravel Sanctum**: Token-based authentication for API
- **Spatie Laravel Permission**: Role-based access control (RBAC)
- **Web Guard**: Session-based authentication for admin panel

### Database
- **SQLite**: File-based database for development and small deployments
- **Eloquent ORM**: Laravel's built-in ORM for database operations
- **Migrations**: Version control for database schema

### Caching
- **Array Cache**: In-memory caching for development
- **Cache Invalidation**: Automatic cache clearing on settings updates

### Activity Logging
- **Spatie Activitylog**: Comprehensive activity tracking
- **Custom Configuration**: Table name alignment and logging controls

## API Architecture

### RESTful Endpoints
```
POST   /api/v1/register          - User registration
POST   /api/v1/login             - User authentication
POST   /api/v1/logout            - User logout
GET    /api/v1/user              - Get current user
PUT    /api/v1/user              - Update user profile
POST   /api/v1/password/reset    - Reset password

GET    /api/v1/users             - List users (paginated)
POST   /api/v1/users             - Create user
GET    /api/v1/users/{id}        - Get user details
PUT    /api/v1/users/{id}        - Update user
DELETE /api/v1/users/{id}        - Delete user
POST   /api/v1/users/bulk-delete - Bulk delete users

GET    /api/v1/roles             - List roles
POST   /api/v1/roles             - Create role
GET    /api/v1/roles/{id}        - Get role details
PUT    /api/v1/roles/{id}        - Update role
DELETE /api/v1/roles/{id}        - Delete role

GET    /api/v1/permissions        - List permissions
GET    /api/v1/permissions/{id}   - Get permission details
```

### API Features
- **Versioning**: v1 prefix for future compatibility
- **Pagination**: Consistent pagination across all list endpoints
- **Search & Filter**: Advanced filtering capabilities
- **Validation**: Comprehensive input validation
- **Error Handling**: Standardized error responses
- **Rate Limiting**: Built-in request throttling

## Admin Panel Architecture

### Controllers Structure
```
app/Http/Controllers/Admin/
├── AuthController.php        # Authentication (login/register)
├── DashboardController.php   # Dashboard with statistics
├── UserController.php      # User management (CRUD + bulk actions)
├── RoleController.php      # Role management (CRUD + permissions)
├── PermissionController.php # Permission viewing
├── ActivityLogController.php # Activity monitoring
├── SettingController.php   # System settings management
└── ReportController.php    # System reports (pending)
```

### Views Structure
```
resources/views/admin/
├── layouts/
│   ├── app.blade.php       # Main admin layout
│   └── auth.blade.php      # Authentication layout
├── auth/
│   ├── login.blade.php     # Login form
│   ├── register.blade.php  # Registration form
│   └── password-reset.blade.php # Password reset
├── users/
│   ├── index.blade.php     # User listing
│   ├── form.blade.php      # Create/edit form
│   └── show.blade.php      # User details
├── roles/
│   ├── index.blade.php     # Role listing
│   └── form.blade.php      # Create/edit form
├── permissions/
│   └── index.blade.php     # Permission listing
├── activity-logs/
│   └── index.blade.php     # Activity monitoring
├── settings/
│   └── index.blade.php     # Settings management
└── dashboard.blade.php     # Dashboard view
```

### UI Features
- **Bootstrap 5**: Modern, responsive design
- **Font Awesome**: Comprehensive icon set
- **Chart.js**: Interactive charts and graphs
- **Data Tables**: Sortable, searchable tables
- **Bulk Actions**: Mass operations on records
- **Real-time Updates**: Live activity monitoring
- **Mobile Responsive**: Works on all device sizes

## Security Features

### Authentication
- **Multi-guard Support**: Separate authentication for web and API
- **Password Requirements**: Strong password validation
- **Session Management**: Secure session handling
- **Remember Me**: Persistent login functionality

### Authorization
- **Role-based Permissions**: Granular access control
- **Permission Groups**: Logical organization of permissions
- **Middleware Protection**: Route-level security
- **Super Admin**: Unrestricted system access

### Data Protection
- **Input Validation**: Comprehensive form validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping
- **CSRF Protection**: Token-based form protection
- **File Upload Security**: Safe file handling

## Database Schema

### Core Tables
```sql
-- Users table with UUID primary keys
CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP,
    password VARCHAR(255),
    status VARCHAR(50),
    avatar VARCHAR(255),
    last_login_at TIMESTAMP,
    last_login_ip VARCHAR(45),
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Roles table
CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) UNIQUE,
    guard_name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Permissions table
CREATE TABLE permissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) UNIQUE,
    guard_name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    log_name VARCHAR(255),
    description TEXT NOT NULL,
    subject_type VARCHAR(255),
    subject_id INTEGER,
    causer_type VARCHAR(255),
    causer_id INTEGER,
    properties TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- System settings table
CREATE TABLE system_settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key VARCHAR(255) UNIQUE,
    value TEXT,
    type VARCHAR(50),
    group VARCHAR(50),
    description TEXT,
    options TEXT,
    is_required BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Performance Optimizations

### Caching Strategy
- **Settings Cache**: System settings cached in memory
- **Permission Cache**: RBAC data cached for performance
- **Query Optimization**: Efficient database queries
- **Pagination**: Limited result sets

### Resource Management
- **Image Optimization**: Avatar upload handling
- **Database Indexing**: Optimized database queries
- **Lazy Loading**: Efficient relationship loading
- **Asset Optimization**: Minified CSS/JS

## Deployment Considerations

### Environment Configuration
- **Environment Variables**: Secure configuration management
- **Database Configuration**: SQLite for development, MySQL/PostgreSQL for production
- **Cache Configuration**: Redis/Memcached for production
- **Queue Configuration**: Background job processing

### Production Setup
- **SSL/HTTPS**: Secure connections
- **Rate Limiting**: API request throttling
- **Error Monitoring**: Application monitoring
- **Backup Strategy**: Database and file backups
- **Security Headers**: Enhanced security

## Testing Strategy

### Test Types
- **Unit Tests**: Individual component testing
- **Feature Tests**: End-to-end functionality
- **Integration Tests**: API endpoint testing
- **Browser Tests**: Admin panel UI testing

### Test Coverage
- **Authentication Flows**: Login, registration, password reset
- **CRUD Operations**: All create, read, update, delete operations
- **Permission System**: Role-based access control
- **API Endpoints**: All REST API endpoints
- **Admin Panel**: All admin interface functionality

## Monitoring & Logging

### Application Monitoring
- **Activity Logging**: Comprehensive user activity tracking
- **Error Logging**: Application error monitoring
- **Performance Monitoring**: Response time tracking
- **Security Monitoring**: Suspicious activity detection

### Health Checks
- **Database Connectivity**: Database health monitoring
- **Cache Performance**: Cache system monitoring
- **API Health**: Endpoint availability checking
- **System Resources**: Server resource monitoring

This architecture provides a robust, scalable foundation for building enterprise-grade applications with comprehensive admin panel functionality and REST API capabilities.