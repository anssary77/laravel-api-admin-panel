@extends('admin.layouts.app')

@section('title', 'User Support Chat')

@section('content')
<div class="container-fluid py-4">
    <div class="row h-100" style="min-height: 600px;">
        <!-- Users List -->
        <div class="col-md-4 border-end">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Conversations</h5>
                </div>
                <div class="list-group list-group-flush overflow-auto" style="max-height: 550px;">
                    @foreach($users as $user)
                        <button class="list-group-item list-group-item-action user-chat-link py-3 border-bottom" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">User</small>
                                    </div>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="col-md-8">
            <div id="chat-window" class="card h-100 border-0 shadow-sm d-none">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0" id="active-user-name">Chat</h5>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div id="chat-messages" class="p-3 flex-grow-1" style="height: 450px; overflow-y: auto; background-color: var(--bs-body-bg);">
                        <!-- Messages will be loaded here -->
                    </div>
                </div>
                <div class="card-footer bg-white p-3">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="hidden" id="receiver-id">
                        <input type="text" id="message-input" class="form-control border-0 bg-light py-2" placeholder="Type your message..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div id="no-chat-selected" class="card h-100 border-0 shadow-sm d-flex align-items-center justify-content-center text-muted">
                <div class="text-center">
                    <i class="fas fa-comments fa-4x mb-3 opacity-25"></i>
                    <p>Select a user to start chatting</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .message {
        margin-bottom: 15px;
        max-width: 80%;
    }
    .message-content {
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 0.95rem;
    }
    .message-sent {
        margin-left: auto;
    }
    .message-sent .message-content {
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 2px;
    }
    .message-received {
        margin-right: auto;
    }
    .message-received .message-content {
        background-color: #e9ecef;
        color: #212529;
        border-bottom-left-radius: 2px;
    }
    [data-bs-theme="dark"] .message-received .message-content {
        background-color: #2c3136;
        color: #dee2e6;
    }
    .message-time {
        font-size: 0.75rem;
        margin-top: 5px;
    }
    .user-chat-link.active {
        background-color: rgba(0, 123, 255, 0.05);
        border-left: 3px solid #007bff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        let activeUserId = null;
        const chatMessages = $('#chat-messages');
        const chatWindow = $('#chat-window');
        const noChatSelected = $('#no-chat-selected');
        const chatForm = $('#chat-form');
        const messageInput = $('#message-input');
        const receiverIdInput = $('#receiver-id');
        const activeUserName = $('#active-user-name');
        const currentAdminId = "{{ Auth::id() }}";

        $('.user-chat-link').on('click', function() {
            $('.user-chat-link').removeClass('active');
            $(this).addClass('active');
            
            activeUserId = $(this).data('user-id');
            const userName = $(this).data('user-name');
            
            activeUserName.text(userName);
            receiverIdInput.val(activeUserId);
            
            noChatSelected.addClass('d-none');
            chatWindow.removeClass('d-none');
            
            loadMessages();
        });

        function loadMessages() {
            if (!activeUserId) return;

            $.get(`/chat/messages/${activeUserId}`, function(messages) {
                let html = '';
                messages.forEach(msg => {
                    const isSent = msg.sender_id === currentAdminId;
                    const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    
                    html += `
                        <div class="message ${isSent ? 'message-sent' : 'message-received'}">
                            <div class="message-content shadow-sm">${msg.message}</div>
                            <div class="message-time text-muted ${isSent ? 'text-end' : ''}">${time}</div>
                        </div>
                    `;
                });
                chatMessages.html(html);
                scrollToBottom();
            });
        }

        function scrollToBottom() {
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }

        chatForm.on('submit', function(e) {
            e.preventDefault();
            const message = messageInput.val().trim();
            if (!message || !activeUserId) return;

            $.post("{{ route('chat.send') }}", {
                message: message,
                receiver_id: activeUserId,
                _token: "{{ csrf_token() }}"
            }, function(msg) {
                messageInput.val('');
                loadMessages();
            });
        });

        // Poll for new messages every 5 seconds if a chat is active
        setInterval(function() {
            if (activeUserId) loadMessages();
        }, 5000);
    });
</script>
@endpush
@endsection
