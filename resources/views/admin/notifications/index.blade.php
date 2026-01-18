@extends('admin.layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Notifications</h1>
            <button class="btn btn-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double me-1"></i>Mark All as Read
            </button>
        </div>
        
        <div class="card">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item list-group-item-action py-3 {{ $notification->read_at ? 'bg-light' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $notification->data['title'] }}</h5>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $notification->data['message'] }}</p>
                            <div class="mt-2">
                                <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary">View Detail</a>
                                @if(!$notification->read_at)
                                    <button class="btn btn-sm btn-link text-muted mark-as-read" data-id="{{ $notification->id }}">Mark as read</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p>No notifications found.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($notifications->hasPages())
                    <div class="p-3 border-top">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '.mark-as-read', function() {
        const btn = $(this);
        const id = btn.data('id');
        $.post('{{ route("admin.notifications.mark-as-read") }}', { id: id, _token: "{{ csrf_token() }}" }, function() {
            btn.closest('.list-group-item').addClass('bg-light');
            btn.remove();
        });
    });
</script>
@endpush
@endsection
