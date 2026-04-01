<script>
/* ── Quill WYSIWYG init (single language) ──────────────────── */
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{ 'header': [1, 2, 3, false] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    ['link', 'image'],
    ['clean'],
];

const editLang = @json($editLang);
const quillEditor = new Quill('#quill-' + editLang, { theme: 'snow', modules: { toolbar: toolbarOptions } });

/* ── Form submit (AJAX) ──────────────────────────────────────── */
$('#{{ $formId }}').on('submit', function (e) {
    e.preventDefault();

    // Sync quill content to hidden input
    document.getElementById('content-' + editLang).value = quillEditor.root.innerHTML;

    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>' + (window.trans?.saving || '{{ __('common.saving') }}'));

    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post(this.dataset.action, new FormData(this))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            if (res.data.data?.redirect_url) {
                setTimeout(function () { window.location = res.data.data.redirect_url; }, 1500);
            } else {
                btn.prop('disabled', false).html('<i class="ti ti-check icon me-1"></i>{{ __('common.save') }}');
            }
        })
        .catch(function (err) {
            if (err.response?.status === 422 && err.response.data?.errors) {
                Object.entries(err.response.data.errors).forEach(function ([field, messages]) {
                    var dotField = field.replace(/\[/g, '.').replace(/\]/g, '');
                    $('[name="' + field + '"], [name="' + field.replace(/\./g, '][').replace(']', '') + '"]').addClass('is-invalid');
                    $('[data-field="' + field + '"], [data-field="' + dotField + '"]').text(messages[0]).addClass('d-block');
                });
            }
            handleAjaxError(err);
            btn.prop('disabled', false).html('<i class="ti ti-check icon me-1"></i>{{ __('common.save') }}');
        });
});
</script>
