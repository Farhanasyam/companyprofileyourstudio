@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        TinyMCE Editor Test
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
            <div class="card shadow mt-4">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearContent() {
    if (confirm('Are you sure you want to clear all content?')) {
        // Clear all TinyMCE editors
        tinymce.get('main-content').setContent('');
        tinymce.get('description-editor').setContent('');
        document.getElementById('title').value = '';
        updatePreview();
    }
}

function updatePreview() {
    const content = tinymce.get('main-content').getContent();
    const preview = document.getElementById('content-preview');
    
    if (content.trim()) {
        preview.innerHTML = content;
    } else {
        preview.innerHTML = '<p class="text-muted">Content will appear here as you type...</p>';
    }
}

// Update preview when content changes
document.addEventListener('DOMContentLoaded', function() {
    // Wait for TinyMCE to initialize
    setTimeout(function() {
        if (tinymce.get('main-content')) {
            tinymce.get('main-content').on('change keyup', updatePreview);
        }
    }, 1000);
});
</script>
@endpush
@endsection
