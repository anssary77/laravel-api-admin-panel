<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Laravel Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-bg: #343a40;
            --sidebar-color: #ffffff;
            --sidebar-hover: #495057;
            --main-bg: #f8f9fa;
        }

        [data-bs-theme="dark"] {
            --sidebar-bg: #1a1d20;
            --sidebar-color: #dee2e6;
            --sidebar-hover: #2c3136;
            --main-bg: #121212;
        }

        body {
            background-color: var(--main-bg);
            transition: background-color 0.3s ease;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            transition: background-color 0.3s ease;
        }
        .sidebar .nav-link {
            color: var(--sidebar-color);
            border-radius: 0;
        }
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        /* Dark mode specific adjustments */
        [data-bs-theme="dark"] .navbar {
            background-color: #1a1d20 !important;
            border-bottom: 1px solid #2c3136;
        }
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border: 1px solid #2c3136;
        }
        [data-bs-theme="dark"] .table {
            --bs-table-bg: #1a1d20;
            --bs-table-hover-bg: #2c3136;
            color: #dee2e6;
        }
        [data-bs-theme="dark"] .bg-white {
            background-color: #1a1d20 !important;
        }
        [data-bs-theme="dark"] .text-muted {
            color: #adb5bd !important;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: #2c3136 !important;
        }
        
        .theme-toggle {
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        .theme-toggle:hover {
            background-color: rgba(0,0,0,0.05);
        }
        [data-bs-theme="dark"] .theme-toggle:hover {
            background-color: rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .stats-card .card-body {
            padding: 2rem;
        }
        .stats-card h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.05);
        }
        .badge {
            font-size: 0.75em;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .transition-hover {
            transition: all 0.3s ease;
        }
        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: var(--bs-body-bg) !important;
            border-color: #007bff !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar position-fixed d-flex flex-column p-3" style="width: 250px;">
        <div class="mb-4">
            <h4 class="text-white">Laravel Admin</h4>
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Users
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-2"></i>Posts
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}">
                    <i class="fas fa-comments me-2"></i>Support Chat
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i>Roles
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="fas fa-key me-2"></i>Permissions
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history me-2"></i>Activity Logs
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i>Reports
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.file-manager.index') }}" class="nav-link {{ request()->routeIs('admin.file-manager.*') ? 'active' : '' }}">
                    <i class="fas fa-folder me-2"></i>File Manager
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments me-2"></i>Chat Support
                </a>
            </li>
        </ul>
        
        <hr class="text-white-50">
        
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-2"></i>
                <strong>{{ Auth::guard('web')->user()->name ?? 'Admin' }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="navbar-nav ms-auto align-items-center">
                    <!-- Theme Toggle -->
                    <div class="nav-item me-3">
                        <div class="theme-toggle" id="themeToggle" title="Toggle Theme">
                            <i class="fas fa-moon text-secondary" id="themeIcon"></i>
                        </div>
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-0" aria-labelledby="notificationDropdown" style="width: 300px;">
                            <li class="dropdown-header border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Notifications</h6>
                                    <button class="btn btn-sm btn-link text-primary p-0" onclick="markAllAsRead()">Mark all as read</button>
                                </div>
                            </li>
                            <div id="notification-list" style="max-height: 300px; overflow-y: auto;">
                                <!-- Notifications will be loaded here -->
                                <div class="p-3 text-center text-muted">
                                    No new notifications
                                </div>
                            </div>
                            <li class="border-top py-2 text-center">
                                <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none">View all</a>
                            </li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::guard('web')->user()->name ?? 'Admin' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="container-fluid px-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('[data-bs-target="#sidebarMenu"]');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('show');
                });
            }
        });
        
        // CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Notification Logic
        const unreadNotificationsUrl = "{{ route('admin.notifications.unread') }}";
        const markAsReadUrl = "{{ route('admin.notifications.mark-as-read') }}";

        function loadNotifications() {
            $.get(unreadNotificationsUrl, function(data) {
                const badge = $('#notification-badge');
                const list = $('#notification-list');
                
                if (data.count > 0) {
                    badge.text(data.count).removeClass('d-none');
                } else {
                    badge.addClass('d-none');
                }

                if (data.notifications.length > 0) {
                    list.html(data.notifications.map(n => `
                        <li>
                            <a class="dropdown-item py-3 border-bottom ${n.read_at ? '' : 'bg-light'} notification-item" href="${n.data.url || '#'}" data-id="${n.id}">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                            <i class="fas ${n.data.icon || 'fa-bell'}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="small mb-1 text-dark fw-bold">${n.data.title || 'Notification'}</p>
                                        <p class="small mb-0 text-muted">${n.data.message || ''}</p>
                                        <small class="text-muted mt-1 d-block">${n.created_at_human}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                    `).join(''));
                } else {
                    list.html('<div class="p-3 text-center text-muted">No new notifications</div>');
                }
            });
        }

        function markAllAsRead() {
            $.post(markAsReadUrl, { _token: "{{ csrf_token() }}" }, function() {
                loadNotifications();
            });
        }

        $(document).on('click', '.notification-item', function(e) {
            const id = $(this).data('id');
            $.post(markAsReadUrl, { id: id, _token: "{{ csrf_token() }}" });
        });

        // Initial load and poll every 30 seconds
        if ($('#notificationDropdown').length) {
            loadNotifications();
            setInterval(loadNotifications, 30000);
        }

        // Theme Toggle Logic
        const htmlElement = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            setTheme(newTheme);
        });

        function setTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            
            if (theme === 'dark') {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
                themeIcon.classList.replace('text-secondary', 'text-warning');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
                themeIcon.classList.replace('text-warning', 'text-secondary');
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>