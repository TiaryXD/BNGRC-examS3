document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#loginForm');
    if (!form) return;

    const statusBox = document.querySelector('#formStatus');
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitBtnInitialHtml = submitBtn ? submitBtn.innerHTML : null;

    // On simplifie la map car on n'a que deux champs
    const map = {
        email: { input: '#email', err: '#emailError' },
        password: { input: '#password', err: '#passwordError' }
    };

    function setLoading(isLoading) {
        if (!submitBtn) return;
        submitBtn.disabled = !!isLoading;
        if (isLoading) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Vérification...';
        } else {
            submitBtn.innerHTML = submitBtnInitialHtml;
        }
    }

    function setStatus(type, msg) {
        if (!statusBox) return;
        if (!msg) {
            statusBox.classList.add('d-none');
            return;
        }
        statusBox.className = `alert alert-${type} border-0 shadow-sm py-2 small text-center`;
        statusBox.textContent = msg;
        statusBox.classList.remove('d-none');
    }

    function applyServerResult(data) {
        Object.keys(map).forEach((k) => {
            const input = document.querySelector(map[k].input);
            const err = document.querySelector(map[k].err);
            const msg = data.errors && data.errors[k] ? data.errors[k] : '';

            if (msg) {
                input.classList.add('is-invalid');
                if (err) err.textContent = msg;
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                if (err) err.textContent = '';
            }
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        setLoading(true);
        setStatus(null, '');

        try {
            const fd = new FormData(form);
            const res = await fetch('/api/validate/auth', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            const data = await res.json();
            applyServerResult(data);

            if (data.ok) {
                setStatus('success', data.message || 'Redirection...');
                // On laisse un petit délai pour que l'utilisateur voit le succès
                setTimeout(() => form.submit(), 1000);
            } else {
                setLoading(false);
                if (data.errors && data.errors._global) {
                    setStatus('danger', data.errors._global);
                }
            }
        } catch (err) {
            setLoading(false);
            setStatus('warning', 'Erreur de connexion au serveur.');
        }
    });
});