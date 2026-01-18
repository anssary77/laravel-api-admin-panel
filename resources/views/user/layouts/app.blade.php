<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard') - Laravel Task</title>
    
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
        .badge {
            font-size: 0.75em;
        }

        /* Floating Chat Widget */
        #chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        #chat-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        #chat-button:hover {
            transform: scale(1.1);
        }

        #chat-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 450px;
            background-color: var(--bs-body-bg);
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid var(--bs-border-color);
        }

        #chat-window.active {
            display: flex;
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: var(--bs-tertiary-bg);
        }

        .chat-input-area {
            padding: 15px;
            border-top: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
        }

        .message {
            margin-bottom: 10px;
            max-width: 80%;
            padding: 10px;
            border-radius: 10px;
        }

        .message-sent {
            align-self: flex-end;
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }

        .message-received {
            align-self: flex-start;
            background-color: #e9ecef;
            color: black;
        }

        [data-bs-theme="dark"] .message-received {
            background-color: #2c3136;
            color: white;
        }

        /* Bot styles */
        .bot-option {
            cursor: pointer;
            padding: 8px 12px;
            background-color: #f0f2f5;
            border-radius: 15px;
            margin-bottom: 5px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            text-align: center;
        }
        .bot-option:hover {
            background-color: #007bff;
            color: white;
        }
        [data-bs-theme="dark"] .bot-option {
            background-color: #1a1d20;
            color: #dee2e6;
            border-color: #2c3136;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar position-fixed d-flex flex-column p-3" style="width: 250px;">
        <div class="mb-4 text-center">
            <h4 class="text-white">User Panel</h4>
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i>My Profile
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('user.settings') }}" class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
        </ul>
        
        <hr class="text-white-50">
        
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar_url }}" alt="" class="rounded-circle me-2" width="32" height="32">
                <strong>{{ Auth::user()->name }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a></li>
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
                                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none">View all</a>
                            </li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url }}" alt="" class="rounded-circle me-2" width="24" height="24">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user-circle me-2 text-muted"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.settings') }}"><i class="fas fa-cog me-2 text-muted"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Sign out</button>
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

    <!-- Floating Chat Widget -->
    <div id="chat-widget">
        <div id="chat-window">
            <div class="chat-header">
                <h6 class="mb-0"><i class="fas fa-headset me-2"></i>Live Support</h6>
                <button type="button" class="btn-close btn-close-white" id="close-chat"></button>
            </div>
            <div class="chat-messages d-flex flex-column" id="widget-chat-messages">
                <!-- Messages will be loaded here -->
                <div class="text-center text-muted my-auto" id="chat-welcome">
                    <i class="fas fa-robot fa-3x mb-3 text-primary"></i>
                    <p class="fw-bold">Hello! I'm your Support Bot.</p>
                    <p class="small">How can I assist you today?</p>
                    <div class="bot-options mt-3 px-3">
                        <div class="bot-option" onclick="handleBotQuery('How to create a post?')">How to create a post?</div>
                        <div class="bot-option" onclick="handleBotQuery('How to update my profile?')">How to update my profile?</div>
                        <div class="bot-option" onclick="handleBotQuery('Contact human support')">Contact human support</div>
                    </div>
                </div>
            </div>
            <div class="chat-input-area">
                <form id="widget-chat-form">
                    <div class="input-group">
                        <input type="text" class="form-control" id="widget-message-input" placeholder="Type a message..." required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="chat-button">
            <i class="fas fa-comments fa-2x"></i>
            <span id="chat-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                0
            </span>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Notification Logic
        const unreadNotificationsUrl = "{{ route('user.notifications.unread') }}";
        const markAsReadUrl = "{{ route('user.notifications.mark-as-read') }}";

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
        
        if (themeToggle) {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);

            themeToggle.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                setTheme(newTheme);
            });
        }

        function setTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            
            if (themeIcon) {
                if (theme === 'dark') {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    themeIcon.classList.replace('text-secondary', 'text-warning');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    themeIcon.classList.replace('text-warning', 'text-secondary');
                }
            }
        }

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.navbar-toggler');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('show');
                });
            }
        });

        // Floating Chat Widget Logic
        $(document).ready(function() {
            const chatButton = $('#chat-button');
            const chatWindow = $('#chat-window');
            const closeChat = $('#close-chat');
            const chatForm = $('#widget-chat-form');
            const messageInput = $('#widget-message-input');
            const chatMessages = $('#widget-chat-messages');
            const chatBadge = $('#chat-badge');
            
            let pollInterval;

            chatButton.on('click', function() {
                chatWindow.toggleClass('active');
                if (chatWindow.hasClass('active')) {
                    // Check if we have history, if not show welcome
                    $.get("{{ route('chat.messages') }}", function(messages) {
                        if (messages.length > 0) {
                            loadWidgetMessages();
                            startPolling();
                        }
                    });
                    chatBadge.addClass('d-none').text('0');
                } else {
                    stopPolling();
                }
            });

            closeChat.on('click', function() {
                chatWindow.removeClass('active');
                stopPolling();
            });

            window.handleBotQuery = function(query) {
                appendMessage(query, 'message-sent');
                
                // Show typing indicator
                const typingId = 'typing-' + Date.now();
                chatMessages.append(`<div class="message message-received" id="${typingId}"><i class="fas fa-ellipsis-h fa-small"></i></div>`);
                scrollToBottom();

                setTimeout(() => {
                    $(`#${typingId}`).remove();
                    let response = "";
                    switch(query) {
                        case 'How to create a post?':
                            response = "To create a post, go to your dashboard and look for the 'Create Post' button (if available) or contact admin to enable post creation for your account.";
                            break;
                        case 'How to update my profile?':
                            response = "You can update your profile by clicking on 'Profile' in the sidebar or from the top right user menu.";
                            break;
                        case 'Contact human support':
                            response = "I am connecting you to our human support team. Please type your message below.";
                            startPolling(); // Start checking for human replies
                            break;
                        default:
                            response = "I'm not sure about that. Would you like to speak with human support?";
                    }
                    appendMessage(response, 'message-received');
                    
                    // If it's a bot response, we don't necessarily save it to DB 
                    // unless you want to log bot interactions.
                }, 1000);
            };

            function appendMessage(text, className) {
                $('#chat-welcome').hide();
                chatMessages.append(`<div class="message ${className}">${text}</div>`);
                scrollToBottom();
            }

            function loadWidgetMessages() {
                $.get("{{ route('chat.messages') }}", function(messages) {
                    if (messages.length === 0) return;
                    
                    $('#chat-welcome').hide();
                    const currentUserId = "{{ Auth::id() }}";
                    chatMessages.html(messages.map(msg => `
                        <div class="message ${msg.sender_id == currentUserId ? 'message-sent' : 'message-received'}">
                            ${msg.message}
                        </div>
                    `).join(''));
                    
                    scrollToBottom();
                });
            }

            function scrollToBottom() {
                chatMessages.scrollTop(chatMessages[0].scrollHeight);
            }

            chatForm.on('submit', function(e) {
                e.preventDefault();
                const message = messageInput.val().trim();
                if (!message) return;

                $.post("{{ route('chat.send') }}", {
                    message: message,
                    _token: "{{ csrf_token() }}"
                }, function(msg) {
                    messageInput.val('');
                    loadWidgetMessages();
                });
            });

            function startPolling() {
                if (pollInterval) clearInterval(pollInterval);
                pollInterval = setInterval(loadWidgetMessages, 5000);
            }

            function stopPolling() {
                if (pollInterval) clearInterval(pollInterval);
            }

            // Optional: Background polling for badge
            setInterval(function() {
                if (!chatWindow.hasClass('active')) {
                    $.get("{{ route('chat.messages') }}", function(messages) {
                        const unreadCount = messages.filter(m => !m.is_read && m.receiver_id == "{{ Auth::id() }}").length;
                        if (unreadCount > 0) {
                            chatBadge.text(unreadCount).removeClass('d-none');
                        }
                    });
                }
            }, 10000);
        });
    </script>
    @stack('scripts')
</body>
</html>