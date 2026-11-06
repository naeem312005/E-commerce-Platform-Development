<!-- Rich Text Editor (CKEditor / WYSIWYG) Helper -->
@if(config('services.ckeditor.enabled', true))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editors = document.querySelectorAll('.ck-editor, [data-ckeditor]');
            if (editors.length > 0 && typeof ClassicEditor !== 'undefined') {
                editors.forEach(editorElement => {
                    ClassicEditor
                        .create(editorElement, {
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'strikethrough', 'underline', '|',
                                    'bulletedList', 'numberedList', '|',
                                    'link', 'blockQuote', '|',
                                    'undo', 'redo'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            placeholder: editorElement.getAttribute('placeholder') || 'Enter content here...'
                        })
                        .catch(error => {
                            console.warn('CKEditor initialization warning:', error);
                        });
                });
            }
        });
    </script>
@endif

