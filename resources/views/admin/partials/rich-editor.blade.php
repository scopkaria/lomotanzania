{{-- ============================================================
     Rich Text Editor (Jodit) — Free forever, no API key needed
     Props:
       $name      — form field name (e.g. 'description')
       $id        — unique editor ID (e.g. 'description_editor')
       $value     — current HTML content
       $label     — field label (optional)
       $rows      — editor height: small(200) / medium(350) / large(500) — default medium
       $placeholder — placeholder text (optional)
     ============================================================ --}}

@php
    $editorId = $id ?? 'editor_' . str_replace(['[', ']', '.'], '_', $name);
    $height = match($rows ?? 'medium') {
        'small'  => 200,
        'large'  => 500,
        default  => 350,
    };
@endphp

@if($label ?? null)
    <label for="{{ $editorId }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
@endif

<textarea name="{{ $name }}" id="{{ $editorId }}" class="jodit-editor"
          data-height="{{ $height }}"
          data-placeholder="{{ $placeholder ?? 'Start writing content...' }}"
          style="visibility:hidden;height:0;overflow:hidden;">{{ $value ?? '' }}</textarea>

@error($name) <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

@pushOnce('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.2.27/es2015/jodit.min.css">
<style>
    .jodit-container { border-radius: 0.5rem !important; border-color: rgb(209 213 219) !important; }
    .jodit-container:focus-within { border-color: #FEBC11 !important; box-shadow: 0 0 0 2px rgba(254,188,17,0.15) !important; }
    .jodit-toolbar__box { background: #fff !important; border-radius: 0.5rem 0.5rem 0 0 !important; }
    .jodit-workplace { background: #fff !important; }
    .jodit-status-bar { border-top-color: rgb(209 213 219) !important; border-radius: 0 0 0.5rem 0.5rem !important; }
</style>
@endPushOnce

@pushOnce('scripts')
<script src="https://cdn.jsdelivr.net/npm/jodit@4.2.27/es2015/jodit.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Jodit === 'undefined') {
        console.error('Jodit editor failed to load from CDN');
        // Show the textareas so users can at least type
        document.querySelectorAll('.jodit-editor').forEach(function(el) {
            el.style.visibility = 'visible';
            el.style.height = 'auto';
            el.style.overflow = 'auto';
        });
        return;
    }

    const uploadUrl = '{{ route("admin.editor.upload-image") }}';
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';

    function initEditor(el) {
        if (el.dataset.joditInit) return;
        el.dataset.joditInit = 'true';

        const height = parseInt(el.dataset.height) || 350;
        const placeholder = el.dataset.placeholder || 'Start writing...';

        const editor = Jodit.make(el, {
            height: height,
            placeholder: placeholder,
            toolbarAdaptive: false,
            showCharsCounter: false,
            showWordsCounter: true,
            showXPathInStatusbar: false,
            askBeforePasteHTML: false,
            askBeforePasteFromWord: false,
            defaultActionOnPaste: 'insert_clear_html',
            buttons: [
                'paragraph', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'ul', 'ol', '|',
                'link', 'image', 'table', '|',
                'align', '|',
                'hr', 'source', 'eraser',
                'undo', 'redo'
            ],
            buttonsMD: [
                'paragraph', '|',
                'bold', 'italic', 'underline', '|',
                'ul', 'ol', '|',
                'link', 'image', '|',
                'source', 'eraser', 'undo', 'redo'
            ],
            buttonsSM: [
                'paragraph', 'bold', 'italic', '|',
                'ul', 'ol', '|',
                'link', 'image', '|',
                'source', 'dots'
            ],
            controls: {
                paragraph: {
                    list: {
                        p: 'Paragraph',
                        h2: 'Heading 2',
                        h3: 'Heading 3',
                        h4: 'Heading 4',
                        h5: 'Heading 5',
                        h6: 'Heading 6',
                        blockquote: 'Blockquote',
                        pre: 'Preformatted'
                    }
                }
            },
            uploader: {
                url: uploadUrl,
                format: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                filesVariableName: 'file',
                withCredentials: true,
                insertImageAsBase64URI: false,
                imagesExtensions: ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                isSuccess: function(resp) {
                    return resp && resp.location;
                },
                process: function(resp) {
                    return {
                        files: resp.location ? [resp.location] : [],
                        path: '',
                        baseurl: '',
                        isImages: [true],
                        error: resp.location ? 0 : 1,
                        msg: resp.message || ''
                    };
                },
                defaultHandlerSuccess: function(data) {
                    if (data.files && data.files.length) {
                        for (var i = 0; i < data.files.length; i++) {
                            this.j.s.insertImage(data.files[i]);
                        }
                    }
                },
                defaultHandlerError: function(resp) {
                    this.j.e.fire('errorMessage', (resp && resp.msg) || 'Upload failed');
                }
            }
        });

        // Store reference for global access
        el._joditEditor = editor;
    }

    // Init all editors on the page
    document.querySelectorAll('.jodit-editor').forEach(initEditor);

    // Global function to init a single editor (used by translation tabs)
    window.initRichEditor = initEditor;

    // Global function to sync all editors to their textareas
    window.syncAllEditors = function() {
        document.querySelectorAll('.jodit-editor').forEach(function(el) {
            if (el._joditEditor) {
                el.value = el._joditEditor.value;
            }
        });
    };

    // Global function to set editor content (used by copy-from-English / auto-translate)
    window.setEditorContent = function(editorId, content) {
        var el = document.getElementById(editorId);
        if (el && el._joditEditor) {
            el._joditEditor.value = content || '';
        }
    };

    // Global function to get editor content
    window.getEditorContent = function(editorId) {
        var el = document.getElementById(editorId);
        if (el && el._joditEditor) {
            return el._joditEditor.value;
        }
        return el ? el.value : '';
    };
});
</script>
@endPushOnce
