<div class="tinymce-wrapper">
    <textarea 
        class="tinymce-editor" 
        name="{{ $name ?? 'content' }}" 
        id="{{ $id ?? 'tinymce-editor' }}"
        placeholder="{{ $placeholder ?? 'Enter your content here...' }}"
        {{ $required ? 'required' : '' }}
    >{!! str_replace('</textarea>', '&lt;/textarea&gt;', $value ?? '') !!}</textarea>
</div>

<style>
.tinymce-wrapper {
    margin: 1rem 0;
}

.tinymce-editor {
    width: 100%;
    min-height: 400px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    font-family: inherit;
}

.tinymce-editor:focus {
    outline: none;
    border-color: #007cba;
    box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.2);
}
</style>