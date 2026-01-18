<?php $__env->startSection('title', 'File Manager'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-folder-open me-2"></i>File Manager
        </h5>
        <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-1"></i>Upload
            </button>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createDirectoryModal">
                <i class="fas fa-folder-plus me-1"></i>New Folder
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="fileSearch" class="form-control form-control-sm border-start-0" placeholder="Search files and folders...">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active" id="viewList">
                        <i class="fas fa-list"></i>
                    </button>
                    <button class="btn btn-outline-secondary" id="viewGrid">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" id="breadcrumbList">
                <li class="breadcrumb-item">
                    <a href="#" onclick="navigateTo('')">
                        <i class="fas fa-home"></i> Root
                    </a>
                </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="breadcrumb-item <?php echo e($loop->last ? 'active' : ''); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                            <a href="#" onclick="navigateTo('<?php echo e($crumb['path']); ?>')">
                                <?php echo e($crumb['name']); ?>

                            </a>
                        <?php else: ?>
                            <?php echo e($crumb['name']); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ol>
        </nav>

        <!-- Current Path (Hidden but useful for JS) -->
        <input type="hidden" id="currentPathInput" value="<?php echo e($path); ?>">

        <!-- File List Wrapper -->
        <div id="fileManagerContent">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="fileTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fileListBody">
                        <!-- Directories -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $directories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $directory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="file-item" data-name="<?php echo e($directory['name']); ?>" data-type="folder">
                                <td>
                                    <i class="fas fa-folder text-warning me-2 fa-lg"></i>
                                    <a href="#" onclick="navigateTo('<?php echo e($directory['path']); ?>')"
                                       class="text-decoration-none fw-bold text-dark">
                                        <?php echo e($directory['name']); ?>

                                    </a>
                                </td>
                                <td><span class="badge bg-primary">Folder</span></td>
                                <td>-</td>
                                <td class="small text-muted"><?php echo e($directory['modified']); ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-danger" 
                                                onclick="deleteItem('<?php echo e($directory['name']); ?>', true)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Files -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="file-item" data-name="<?php echo e($file['name']); ?>" data-type="file">
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file['is_image']): ?>
                                        <i class="fas fa-file-image text-success me-2 fa-lg"></i>
                                    <?php else: ?>
                                        <i class="fas fa-file text-secondary me-2 fa-lg"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="fw-medium"><?php echo e($file['name']); ?></span>
                                </td>
                                <td><span class="badge bg-secondary"><?php echo e(strtoupper($file['type'])); ?></span></td>
                                <td class="small"><?php echo e($file['size']); ?></td>
                                <td class="small text-muted"><?php echo e($file['modified']); ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file['is_image']): ?>
                                            <button class="btn btn-outline-info" onclick="previewImage('<?php echo e($file['url']); ?>', '<?php echo e($file['name']); ?>')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e(route('admin.file-manager.download', $file['name'])); ?>?path=<?php echo e($path); ?>"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-outline-secondary" 
                                                onclick="renameItem('<?php echo e($file['name']); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" 
                                                onclick="deleteItem('<?php echo e($file['name']); ?>', false)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($directories) === 0 && count($files) === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i>
                                    <p class="h5">No files or directories found</p>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="previewTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="previewImage" src="" class="img-fluid rounded shadow-sm" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="fileInput" name="file" required>
                        <div class="form-text">Maximum file size: 10MB</div>
                    </div>
                    <div class="progress d-none" id="uploadProgress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Directory Modal -->
<div class="modal fade" id="createDirectoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createDirectoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="directoryName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="directoryName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rename</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="renameForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newName" class="form-label">New Name</label>
                        <input type="text" class="form-control" id="newName" name="new_name" required>
                        <input type="hidden" id="oldName" name="old_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Rename</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentPath = <?php echo json_encode($path, 15, 512) ?>;
    let currentFiles = <?php echo json_encode($files, 15, 512) ?>;
    let currentDirectories = <?php echo json_encode($directories, 15, 512) ?>;
    let viewMode = 'list';

    // Routes
    const routes = {
        index: <?php echo json_encode(route('admin.file-manager.index'), 15, 512) ?>,
        upload: <?php echo json_encode(route('admin.file-manager.upload'), 15, 512) ?>,
        createDirectory: <?php echo json_encode(route('admin.file-manager.create-directory'), 15, 512) ?>,
        rename: <?php echo json_encode(route('admin.file-manager.rename'), 15, 512) ?>,
        destroy: <?php echo json_encode(route('admin.file-manager.destroy', ''), 512) ?>,
        download: <?php echo json_encode(route('admin.file-manager.download', ':file'), 512) ?>
    };

    // Initialize UI
    document.addEventListener('DOMContentLoaded', function() {
        setupSearch();
        setupViewToggles();
    });

    function setupSearch() {
        const searchInput = document.getElementById('fileSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                filterItems(term);
            });
        }
    }

    function filterItems(term) {
        const items = document.querySelectorAll('.file-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            if (name.includes(term)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function setupViewToggles() {
        const listBtn = document.getElementById('viewList');
        const gridBtn = document.getElementById('viewGrid');
        
        if (listBtn && gridBtn) {
            listBtn.addEventListener('click', () => setViewMode('list'));
            gridBtn.addEventListener('click', () => setViewMode('grid'));
        }
    }

    function setViewMode(mode) {
        viewMode = mode;
        document.getElementById('viewList').classList.toggle('active', mode === 'list');
        document.getElementById('viewGrid').classList.toggle('active', mode === 'grid');
        renderContent();
    }

    function navigateTo(path) {
        currentPath = path;
        document.getElementById('currentPathInput').value = path;
        
        fetch(`${routes.index}?path=${encodeURIComponent(path)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                currentFiles = response.data.files;
                currentDirectories = response.data.directories;
                renderContent();
                renderBreadcrumbs(response.data.breadcrumbs);
                // Update URL without reload
                const url = new URL(window.location);
                url.searchParams.set('path', path);
                window.history.pushState({}, '', url);
            }
        })
        .catch(error => console.error('Navigation failed:', error));
    }

    function renderBreadcrumbs(breadcrumbs) {
        const list = document.getElementById('breadcrumbList');
        let html = `
            <li class="breadcrumb-item">
                <a href="#" onclick="navigateTo(''); return false;">
                    <i class="fas fa-home"></i> Root
                </a>
            </li>
        `;
        
        breadcrumbs.forEach((crumb, index) => {
            const isLast = index === breadcrumbs.length - 1;
            html += `
                <li class="breadcrumb-item ${isLast ? 'active' : ''}">
                    ${isLast ? crumb.name : `<a href="#" onclick="navigateTo('${crumb.path}'); return false;">${crumb.name}</a>`}
                </li>
            `;
        });
        
        list.innerHTML = html;
    }

    function renderContent() {
        const wrapper = document.getElementById('fileManagerContent');
        
        if (viewMode === 'list') {
            renderListView(wrapper);
        } else {
            renderGridView(wrapper);
        }
    }

    function renderListView(wrapper) {
        let html = `
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="fileTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fileListBody">
        `;

        if (currentDirectories.length === 0 && currentFiles.length === 0) {
            html += `
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i>
                        <p class="h5">No files or directories found</p>
                    </td>
                </tr>
            `;
        } else {
            currentDirectories.forEach(dir => {
                html += `
                    <tr class="file-item" data-name="${dir.name}" data-type="folder">
                        <td>
                            <i class="fas fa-folder text-warning me-2 fa-lg"></i>
                            <a href="#" onclick="navigateTo('${dir.path}'); return false;" class="text-decoration-none fw-bold text-dark">
                                ${dir.name}
                            </a>
                        </td>
                        <td><span class="badge bg-primary">Folder</span></td>
                        <td>-</td>
                        <td class="small text-muted">${dir.modified}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-danger" onclick="deleteItem('${dir.name}', true)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            currentFiles.forEach(file => {
                const downloadUrl = `${routes.download}?path=${encodeURIComponent(currentPath)}`.replace(':file', encodeURIComponent(file.name));
                html += `
                    <tr class="file-item" data-name="${file.name}" data-type="file">
                        <td>
                            <i class="fas ${file.is_image ? 'fa-file-image text-success' : 'fa-file text-secondary'} me-2 fa-lg"></i>
                            <span class="fw-medium">${file.name}</span>
                        </td>
                        <td><span class="badge bg-secondary">${file.type.toUpperCase()}</span></td>
                        <td class="small">${file.size}</td>
                        <td class="small text-muted">${file.modified}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                ${file.is_image ? `
                                    <button class="btn btn-outline-info" onclick="previewImage('${file.url}', '${file.name}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                ` : ''}
                                <a href="${downloadUrl}" class="btn btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="btn btn-outline-secondary" onclick="renameItem('${file.name}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteItem('${file.name}', false)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        html += `</tbody></table></div>`;
        wrapper.innerHTML = html;
    }

    function renderGridView(wrapper) {
        let html = `<div class="row g-3">`;

        if (currentDirectories.length === 0 && currentFiles.length === 0) {
            html += `
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i>
                    <p class="h5">No files or directories found</p>
                </div>
            `;
        } else {
            currentDirectories.forEach(dir => {
                html += `
                    <div class="col-6 col-md-4 col-lg-2 file-item" data-name="${dir.name}" data-type="folder">
                        <div class="card h-100 text-center border-0 shadow-sm hover-shadow cursor-pointer" onclick="navigateTo('${dir.path}')">
                            <div class="card-body p-3">
                                <i class="fas fa-folder text-warning fa-4x mb-2"></i>
                                <div class="text-truncate fw-bold small">${dir.name}</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            currentFiles.forEach(file => {
                const downloadUrl = `${routes.download}?path=${encodeURIComponent(currentPath)}`.replace(':file', encodeURIComponent(file.name));
                html += `
                    <div class="col-6 col-md-4 col-lg-2 file-item" data-name="${file.name}" data-type="file">
                        <div class="card h-100 text-center border-0 shadow-sm hover-shadow">
                            <div class="card-body p-3 position-relative">
                                ${file.is_image ? 
                                    `<img src="${file.url}" class="img-fluid rounded mb-2" style="height: 64px; object-fit: cover; cursor: pointer;" onclick="previewImage('${file.url}', '${file.name}')">` :
                                    `<i class="fas fa-file text-secondary fa-4x mb-2"></i>`
                                }
                                <div class="text-truncate fw-medium small">${file.name}</div>
                                <div class="dropdown position-absolute top-0 end-0 p-1">
                                    <button class="btn btn-link btn-sm text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end small">
                                        ${file.is_image ? `<li><a class="dropdown-item" href="#" onclick="previewImage('${file.url}', '${file.name}'); return false;"><i class="fas fa-eye me-2"></i>View</a></li>` : ''}
                                        <li><a class="dropdown-item" href="${downloadUrl}"><i class="fas fa-download me-2"></i>Download</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="renameItem('${file.name}'); return false;"><i class="fas fa-edit me-2"></i>Rename</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteItem('${file.name}', false); return false;"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        html += `</div>`;
        wrapper.innerHTML = html;
    }

    function previewImage(url, name) {
        document.getElementById('previewImage').src = url;
        document.getElementById('previewTitle').innerText = name;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    // Upload form submission
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files.length) return;

        formData.append('file', fileInput.files[0]);
        formData.append('path', currentPath);
        formData.append('_token', <?php echo json_encode(csrf_token(), 15, 512) ?>);
        
        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = progressDiv.querySelector('.progress-bar');
        
        progressDiv.classList.remove('d-none');
        
        fetch(routes.upload, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                if (modal) modal.hide();
                fileInput.value = '';
                navigateTo(currentPath);
            } else {
                alert(data.message);
            }
        })
        .catch(error => alert('Upload failed: ' + error.message))
        .finally(() => progressDiv.classList.add('d-none'));
    });
    
    // Create directory form submission
    document.getElementById('createDirectoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nameInput = document.getElementById('directoryName');
        const formData = new FormData();
        formData.append('name', nameInput.value);
        formData.append('path', currentPath);
        formData.append('_token', <?php echo json_encode(csrf_token(), 15, 512) ?>);
        
        fetch(routes.createDirectory, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('createDirectoryModal'));
                if (modal) modal.hide();
                nameInput.value = '';
                navigateTo(currentPath);
            } else {
                alert(data.message);
            }
        })
        .catch(error => alert('Failed to create directory: ' + error.message));
    });
    
    function renameItem(oldName) {
        document.getElementById('oldName').value = oldName;
        document.getElementById('newName').value = oldName;
        new bootstrap.Modal(document.getElementById('renameModal')).show();
    }
    
    document.getElementById('renameForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('old_name', document.getElementById('oldName').value);
        formData.append('new_name', document.getElementById('newName').value);
        formData.append('path', currentPath);
        formData.append('_token', <?php echo json_encode(csrf_token(), 15, 512) ?>);
        
        fetch(routes.rename, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('renameModal'));
                if (modal) modal.hide();
                navigateTo(currentPath);
            } else {
                alert(data.message);
            }
        })
        .catch(error => alert('Rename failed: ' + error.message));
    });
    
    function deleteItem(name, isDirectory) {
        if (confirm(`Are you sure you want to delete "${name}"?`)) {
            const formData = new FormData();
            formData.append('path', currentPath);
            formData.append('_token', <?php echo json_encode(csrf_token(), 15, 512) ?>);
            
            fetch(`${routes.destroy}/${encodeURIComponent(name)}`, {
                method: 'DELETE',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    navigateTo(currentPath);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => alert('Delete failed: ' + error.message));
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/file-manager/index.blade.php ENDPATH**/ ?>