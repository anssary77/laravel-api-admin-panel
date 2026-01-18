@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Settings</h1>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-warning" onclick="confirm('Are you sure you want to clear the cache?') && document.getElementById('clear-cache-form').submit();">
                    <i class="fas fa-broom me-1"></i>Clear Cache
                </button>
                <form id="clear-cache-form" method="POST" action="{{ route('admin.settings.clear-cache') }}" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="settingsSearch" class="form-control border-start-0 ps-0" placeholder="Search settings...">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Settings Groups
                </h5>
            </div>
            <div class="list-group list-group-flush">
                @foreach($groups as $groupName)
                    <a href="{{ route('admin.settings.index', ['group' => $groupName]) }}" 
                       class="list-group-item list-group-item-action {{ request('group', 'general') == $groupName ? 'active' : '' }}">
                        <i class="fas fa-{{ $groupName == 'general' ? 'cog' : ($groupName == 'api' ? 'code' : ($groupName == 'email' ? 'envelope' : 'shield')) }} me-2"></i>
                        {{ ucfirst($groupName) }}
                        <span class="badge bg-secondary float-end">{{ $settings->where('group', $groupName)->count() }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>Backup & Restore
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('backup-form').submit();">
                        <i class="fas fa-download me-1"></i>Backup Settings
                    </button>
                    <form id="backup-form" method="POST" action="{{ route('admin.settings.backup') }}" class="d-none">
                        @csrf
                    </form>
                    
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#restoreModal">
                        <i class="fas fa-upload me-1"></i>Restore Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-{{ request('group', 'general') == 'general' ? 'cog' : (request('group') == 'api' ? 'code' : (request('group') == 'email' ? 'envelope' : 'shield')) }} me-2"></i>
                    {{ ucfirst(request('group', 'general')) }} Settings
                </h5>
                @if(request('group') == 'email')
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                        <i class="fas fa-paper-plane me-1"></i>Test Connection
                    </button>
                @endif
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.group.update', request('group', 'general')) }}">
                    @csrf
                    @method('PUT')
                    
                    @foreach($settings->where('group', request('group', 'general')) as $setting)
                        <div class="row mb-3 align-items-center setting-row">
                            <div class="col-md-4">
                                <label for="{{ $setting->key }}" class="form-label fw-semibold">
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    @if($setting->is_required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                @if($setting->description)
                                    <small class="text-muted d-block">{{ $setting->description }}</small>
                                @endif
                            </div>
                            <div class="col-md-8">
                                @switch($setting->type)
                                    @case('boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="{{ $setting->key }}" name="{{ $setting->key }}" 
                                                   {{ old($setting->key, $setting->value) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $setting->key }}">
                                                {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                            </label>
                                        </div>
                                        @break
                                    
                                    @case('number')
                                        <input type="number" class="form-control @error($setting->key) is-invalid @enderror" 
                                               id="{{ $setting->key }}" name="{{ $setting->key }}" 
                                               value="{{ old($setting->key, $setting->value) }}"
                                               {{ $setting->is_required ? 'required' : '' }}>
                                        @break
                                    
                                    @case('textarea')
                                        <textarea class="form-control @error($setting->key) is-invalid @enderror" 
                                                  id="{{ $setting->key }}" name="{{ $setting->key }}" 
                                                  rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                        @break
                                    
                                    @case('select')
                                        <select class="form-select @error($setting->key) is-invalid @enderror" 
                                                id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                {{ $setting->is_required ? 'required' : '' }}>
                                            @foreach(json_decode($setting->options, true) ?? [] as $option)
                                                <option value="{{ $option['value'] }}" {{ old($setting->key, $setting->value) == $option['value'] ? 'selected' : '' }}>
                                                    {{ $option['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @break
                                    
                                    @default
                                        <input type="text" class="form-control @error($setting->key) is-invalid @enderror" 
                                               id="{{ $setting->key }}" name="{{ $setting->key }}" 
                                               value="{{ old($setting->key, $setting->value) }}"
                                               {{ $setting->is_required ? 'required' : '' }}>
                                @endswitch
                                
                                @error($setting->key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Restore Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.restore') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">Backup File</label>
                        <input type="file" class="form-control @error('backup_file') is-invalid @enderror" id="backup_file" name="backup_file" accept=".json" required>
                        <small class="text-muted">Select a JSON backup file to restore settings from.</small>
                        @error('backup_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This will overwrite all current settings with the backup data.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Restore Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('settingsSearch');
        const settingRows = document.querySelectorAll('.setting-row');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            settingRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.test-email') }}">
                @csrf
                <div class="modal-body">
                    <p>Enter an email address to send a test message using your current SMTP settings.</p>
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" id="test_email" name="test_email" placeholder="example@domain.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send Test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>