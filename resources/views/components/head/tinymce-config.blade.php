<script src="https://cdn.tiny.cloud/1/9iw2xqwn1593xsb15d6xpi0y41mtrets5ms0l5s8kekdgf63/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks visualchars code fullscreen insertdatetime media table wordcount help emoticons directionality codesample pagebreak',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview | image media link codesample | ltr rtl | code',
    menubar: 'file edit view insert format tools table help',
    height: 400,
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
        editor.on('change keyup', function () {
            editor.save();
        });
    }
  });
</script>
