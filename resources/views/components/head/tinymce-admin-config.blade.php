<script src="https://cdn.tiny.cloud/1/9iw2xqwn1593xsb15d6xpi0y41mtrets5ms0l5s8kekdgf63/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing TinyMCE for admin...');
    
    // Wait a bit for DOM to be fully ready
    setTimeout(function() {
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            // Plugin yang kompatibel dengan TinyMCE 8 (paste, print, template, textpattern sudah dihapus di v8)
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks visualchars code fullscreen insertdatetime media table wordcount help emoticons directionality codesample pagebreak',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview | image media link codesample | ltr rtl | code',
            menubar: 'file edit view insert format tools table help',
            height: 420,
            branding: false,
            promotion: false,
            resize: true,
            elementpath: true,
            statusbar: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_handler: function (blobInfo) {
                return new Promise(function(resolve) {
                    var reader = new FileReader();
                    reader.onload = function() { resolve(reader.result); };
                    reader.readAsDataURL(blobInfo.blob());
                });
            },
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
            setup: function (editor) {
                console.log('TinyMCE editor setup for:', editor.id);
                // Simpan ke textarea setiap kali konten berubah
                editor.on('change keyup', function () {
                    editor.save();
                });
                // Simpan ke textarea saat editor kehilangan fokus
                editor.on('blur', function () {
                    editor.save();
                });
            },
            init_instance_callback: function (editor) {
                console.log('TinyMCE editor initialized:', editor.id);
                // Jika ada data awal dari server (window.__articleInitial), set ke editor
                if (window.__articleInitial && window.__articleInitial[editor.id] !== undefined) {
                    var initialContent = window.__articleInitial[editor.id] || '';
                    if (initialContent) {
                        editor.setContent(initialContent);
                    }
                }
                // Simpan konten (awal atau kosong) ke textarea
                editor.save();
            }
        });
        
        console.log('TinyMCE initialization completed');
    }, 100);
});
</script>
