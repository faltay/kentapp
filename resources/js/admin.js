import 'tom-select/dist/css/tom-select.bootstrap5.min.css';
import axios from 'axios';
import Swal from 'sweetalert2';
import ApexCharts from 'apexcharts';
import TomSelect from 'tom-select';

window.axios = axios;
window.Swal = Swal;
window.ApexCharts = ApexCharts;
window.TomSelect = TomSelect;

// window.bootstrap is set by CDN tabler.min.js (loaded in layout before this module runs)

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const _csrfToken = document.querySelector('meta[name="csrf-token"]');
if (_csrfToken) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrfToken.content;

// Global AJAX error handler
window.handleAjaxError = function (error) {
    if (error.response) {
        const status = error.response.status;
        const data = error.response.data;

        if (status === 422) {
            // Validation errors
            const errors = data.errors || {};
            let messages = Object.values(errors).flat().join('\n');
            Swal.fire({ icon: 'warning', title: window.trans?.validation_error || 'Validation Error', text: messages });
        } else if (status === 403) {
            Swal.fire({ icon: 'error', title: window.trans?.forbidden || 'Forbidden', text: data.message || '' });
        } else if (status === 404) {
            Swal.fire({ icon: 'error', title: window.trans?.not_found || 'Not Found', text: data.message || '' });
        } else {
            Swal.fire({ icon: 'error', title: window.trans?.error || 'Error', text: data.message || '' });
        }
    }
};

window.handleAjaxSuccess = function (message) {
    Swal.fire({ icon: 'success', title: window.trans?.success || 'Success', text: message, timer: 2000, showConfirmButton: false });
};

window.confirmDelete = function (callback) {
    Swal.fire({
        icon: 'warning',
        title: window.trans?.confirm_delete_title || 'Are you sure?',
        text: window.trans?.confirm_delete_text || 'This action cannot be undone.',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: window.trans?.delete || 'Delete',
        cancelButtonText: window.trans?.cancel || 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) callback();
    });
};
