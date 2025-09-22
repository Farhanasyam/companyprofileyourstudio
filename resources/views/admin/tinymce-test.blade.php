@extends('admin.layout')

@section('title', 'TinyMCE Test - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>TinyMCE Test - Admin Panel</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">
            <i class="bi bi-pencil-square me-2"></i>
            TinyMCE Editor Test - Admin Panel
        </h3>
    </div>
    <div class="card-body">
        <form method="POST" action="#" class="needs-validation" novalidate>
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <x-forms.tinymce-editor 
                    name="content" 
                    id="main-content"
                    placeholder="Enter your content here..."
                    :required="true"
                />
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <x-forms.tinymce-editor 
                    name="description" 
                    id="description-editor"
                    placeholder="Enter a brief description..."
                />
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary me-md-2" onclick="clearContent()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Clear
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Save Content
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Section -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">
            <i class="bi bi-eye me-2"></i>
            Content Preview
        </h4>
    </div>
    <div class="card-body">
        <div id="content-preview" class="border p-3 rounded bg-light">
            <p class="text-muted">Content will appear here as you type...</p>
        </div>
    </div>
</div>

<!-- Debug Information -->
<div class="card mt-4">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="bi bi-bug me-2"></i>
            Debug Information
        </h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>TinyMCE Status:</h6>
                <p id="tinymce-status">Checking...</p>
                
                <h6>Available Editors:</h6>
                <ul id="editors-list"></ul>
            </div>
            <div class="col-md-6">
                <h6>Console Logs:</h6>
                <div id="console-logs" style="height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Debug function to capture console logs
const originalLog = console.log;
const originalError = console.error;
const originalWarn = console.warn;

function logToDiv(type, ...args) {
    const logsDiv = document.getElementById('console-logs');
    const timestamp = new Date().toLocaleTimeString();
    const message = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' ');
    logsDiv.innerHTML += `<div style="color: ${type === 'error' ? 'red' : type === 'warn' ? 'orange' : 'black'}">[${timestamp}] ${type.toUpperCase()}: ${message}</div>`;
    logsDiv.scrollTop = logsDiv.scrollHeight;
}

console.log = function(...args) {
    originalLog.apply(console, args);
    logToDiv('log', ...args);
};

console.error = function(...args) {
    originalError.apply(console, args);
    logToDiv('error', ...args);
};

console.warn = function(...args) {
    originalWarn.apply(console, args);
    logToDiv('warn', ...args);
};

function clearContent() {
    if (confirm('Are you sure you want to clear all content?')) {
        // Clear all TinyMCE editors
        if (tinymce.get('main-content')) {
            tinymce.get('main-content').setContent('');
        }
        if (tinymce.get('description-editor')) {
            tinymce.get('description-editor').setContent('');
        }
        document.getElementById('title').value = '';
        updatePreview();
    }
}

function updatePreview() {
    const content = tinymce.get('main-content') ? tinymce.get('main-content').getContent() : '';
    const preview = document.getElementById('content-preview');
    
    if (content.trim()) {
        preview.innerHTML = content;
    } else {
        preview.innerHTML = '<p class="text-muted">Content will appear here as you type...</p>';
    }
}

function checkTinyMCEStatus() {
    const statusDiv = document.getElementById('tinymce-status');
    const editorsList = document.getElementById('editors-list');
    
    if (typeof tinymce !== 'undefined') {
        statusDiv.innerHTML = '<span class="text-success">✓ TinyMCE is loaded</span>';
        
        // List available editors
        const editors = tinymce.get();
        editorsList.innerHTML = '';
        if (editors.length > 0) {
            editors.forEach(editor => {
                const li = document.createElement('li');
                li.textContent = `ID: ${editor.id}, Status: ${editor.initialized ? 'Initialized' : 'Not Initialized'}`;
                editorsList.appendChild(li);
            });
        } else {
            editorsList.innerHTML = '<li class="text-muted">No editors found</li>';
        }
    } else {
        statusDiv.innerHTML = '<span class="text-danger">✗ TinyMCE is not loaded</span>';
        editorsList.innerHTML = '<li class="text-muted">TinyMCE not available</li>';
    }
}

// Update preview when content changes
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Wait for TinyMCE to initialize
    setTimeout(function() {
        console.log('Checking TinyMCE after timeout');
        checkTinyMCEStatus();
        
        if (tinymce.get('main-content')) {
            console.log('Main content editor found');
            tinymce.get('main-content').on('change keyup', updatePreview);
        } else {
            console.log('Main content editor not found');
        }
    }, 2000);
    
    // Check again after 5 seconds
    setTimeout(checkTinyMCEStatus, 5000);
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global Error:', e.error, e.message, e.filename, e.lineno);
});

// Unhandled promise rejection handler
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled Promise Rejection:', e.reason);
});
</script>
@endsection
