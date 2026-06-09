document.addEventListener('DOMContentLoaded', function () {
    // Initialize Navigation
    switchView('dashboard');

    // Initialize Charts
    if (typeof initCharts === 'function') {
        initCharts();
    }
});
