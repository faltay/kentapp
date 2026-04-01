import axios from 'axios';
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/* ============================================================
   Public Menu — Client-side interactions
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    initSearch();
});

/* ── Client-side search ───────────────────────────────────── */
function initSearch() {
    const input     = document.getElementById('menu-search');
    const noResults = document.getElementById('menu-no-results');

    if (!input) return;

    input.addEventListener('input', () => {
        const q = input.value.trim().toLowerCase();

        document.querySelectorAll('.menu-item-card').forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const desc = (card.dataset.desc || '').toLowerCase();
            card.classList.toggle('hidden', q.length > 0 && !name.includes(q) && !desc.includes(q));
        });

        // Show/hide sections based on visible items
        document.querySelectorAll('.menu-section').forEach(section => {
            const hasVisible = [...section.querySelectorAll('.menu-item-card')].some(
                c => !c.classList.contains('hidden')
            );
            section.style.display = hasVisible ? '' : 'none';
        });

        // No results message
        const anyVisible = [...document.querySelectorAll('.menu-item-card')].some(
            c => !c.classList.contains('hidden')
        );
        if (noResults) noResults.classList.toggle('visible', q.length > 0 && !anyVisible);
    });
}
