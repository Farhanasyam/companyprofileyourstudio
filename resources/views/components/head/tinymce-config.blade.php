<script src="https://cdn.tiny.cloud/1/9iw2xqwn1593xsb15d6xpi0y41mtrets5ms0l5s8kekdgf63/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
  tinymce.init({
    selector: 'textarea.tinymce-editor', // CSS selector for TinyMCE editor
    plugins: 'code table lists link image media paste preview searchreplace wordcount fullscreen insertdatetime directionality emoticons template advlist autolink lists charmap print preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons template paste textpattern help',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile image media template link anchor codesample | ltr rtl',
    menubar: 'file edit view insert format tools table help',
    height: 400,
    branding: false,
    promotion: false,
    resize: true,
    elementpath: true,
    statusbar: true,
    paste_data_images: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    images_upload_handler: function (blobInfo, success, failure) {
        // You can implement custom image upload logic here
        // For now, we'll use a simple data URL approach
        var reader = new FileReader();
        reader.onload = function() {
            success(reader.result);
        };
        reader.readAsDataURL(blobInfo.blob());
    },
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
  });
</script>
