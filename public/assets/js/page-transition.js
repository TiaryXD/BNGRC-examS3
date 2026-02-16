document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('.page');
    if (page) {
        requestAnimationFrame(() => {
            page.classList.add('page-enter-active');
        });
    }

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;

        const href = a.getAttribute('href');
        if (!href) return;

        if (a.target === '_blank') return;
        if (a.hasAttribute('download')) return;
        if (href.indexOf('http://') === 0 || href.indexOf('https://') === 0) return;
        if (href.indexOf('#') === 0) return;

        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        if (!page) return;

        e.preventDefault();

        page.classList.remove('page-enter', 'page-enter-active');
        page.classList.add('page-leave');

        requestAnimationFrame(() => {
            page.classList.add('page-leave-active');
        });

        window.setTimeout(() => {
            window.location.href = href;
        }, 180);
    });
});