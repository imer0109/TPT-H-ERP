import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Désactiver le bouton et ajouter le loader
                submitBtn.disabled = true;
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Traitement en cours...
                `;

                // Réactiver le bouton après la soumission
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }, 5000);
            }
        });
    });

    // Gestion des requêtes AJAX
    const showLoader = () => {
        const loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.innerHTML = `
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-indigo-500"></div>
            </div>
        `;
        document.body.appendChild(loader);
    };

    const hideLoader = () => {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.remove();
        }
    };

    // Intercepter les requêtes fetch avec timeout pour éviter les loaders bloqués
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        showLoader();
        
        // Ajouter un timeout pour éviter les loaders bloqués
        const timeoutPromise = new Promise((_, reject) => {
            setTimeout(() => reject(new Error('Request timeout')), 10000);
        });
        
        return Promise.race([originalFetch.apply(this, args), timeoutPromise])
            .then(response => {
                hideLoader();
                return response;
            })
            .catch(error => {
                hideLoader();
                // Ne pas propager l'erreur de timeout pour ne pas casser l'application
                if (error.message !== 'Request timeout') {
                    throw error;
                }
                // Retourner une réponse vide ou une valeur par défaut en cas de timeout
                return new Response('', { status: 408, statusText: 'Request Timeout' });
            });
    };
});