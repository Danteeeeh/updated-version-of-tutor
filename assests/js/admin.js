// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Handle sidebar toggle on mobile
document.addEventListener('DOMContentLoaded', function() {
    var sidebarToggle = document.querySelector('[data-bs-toggle="collapse"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
