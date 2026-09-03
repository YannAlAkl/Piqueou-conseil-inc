<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea.editeur',
        license_key: 'gpl',
        language: 'fr-FR',
        menubar: false,
        statusbar: false,
        height: 220,
        plugins: 'lists link',
        toolbar: 'bold italic underline | bullist numlist | link | removeformat',
        content_style: 'body { font-family: Poppins, Arial, sans-serif; font-size: 14px; }'
    });
</script>
