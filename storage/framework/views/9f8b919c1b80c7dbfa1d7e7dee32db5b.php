<?php $__env->startSection('title', 'Live Support Chat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-headset me-2"></i>Live Support</h5>
                    <span class="badge bg-light text-primary">Online</span>
                </div>
                <div class="card-body p-0">
                    <div id="chat-messages" class="p-3" style="height: 400px; overflow-y: auto; background-color: var(--bs-body-bg);">
                        <div class="text-center text-muted my-5">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading conversation...</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 p-3">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="text" id="message-input" class="form-control border-0 bg-light py-2" placeholder="Type your message here..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        const chatMessages = $('#chat-messages');
        const chatForm = $('#chat-form');
        const messageInput = $('#message-input');
        const currentUserId = "<?php echo e(Auth::id()); ?>";

        function loadMessages() {
            $.get("<?php echo e(route('chat.messages')); ?>", function(messages) {
                if (messages.length === 0) {
                    chatMessages.html('<div class="text-center text-muted my-5"><p>Start a conversation with our support team.</p></div>');
                    return;
                }

                let html = '';
                messages.forEach(msg => {
                    const isSent = msg.sender_id === currentUserId;
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
            if (!message) return;

            $.post("<?php echo e(route('chat.send')); ?>", {
                message: message,
                _token: "<?php echo e(csrf_token()); ?>"
            }, function(msg) {
                messageInput.val('');
                loadMessages();
            });
        });

        // Initial load
        loadMessages();

        // Poll for new messages every 5 seconds
        setInterval(loadMessages, 5000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/user/chat/index.blade.php ENDPATH**/ ?>