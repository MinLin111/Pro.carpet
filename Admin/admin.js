document.addEventListener('DOMContentLoaded', function() {
    const orderCards = document.querySelectorAll('.admin-order-card');
    
    orderCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('highlight');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('highlight');
        });
    });

    const statusSelects = document.querySelectorAll('.admin-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const submitBtn = this.nextElementSibling;
            if (submitBtn && submitBtn.classList.contains('admin-update-btn')) {
                submitBtn.style.background = '#3d332f';
                submitBtn.style.color = '#ffffff';
            }
        });
    });
}); 