document.addEventListener('DOMContentLoaded', function() {
    // Class Filter Functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const courseCards = document.querySelectorAll('.course-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            courseCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    const categories = card.getAttribute('data-category').split(' ');
                    if (categories.includes(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
    }

    // Registration form handling
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Reset form
                    form.reset();
                    // Close modal if exists
                    const modal = bootstrap.Modal.getInstance(document.getElementById('registrationModal'));
                    if (modal) {
                        modal.hide();
                    }
                } else {
                    alert(data.message);
                    // Display errors if any
                    if (data.errors) {
                        // Clear previous errors
                        document.querySelectorAll('.is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                        });
                        document.querySelectorAll('.invalid-feedback').forEach(el => {
                            el.remove();
                        });
                        
                        // Add new errors
                        for (const field in data.errors) {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                errorDiv.textContent = data.errors[field];
                                input.parentNode.appendChild(errorDiv);
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }

    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value;
            
            if (!email) {
                alert('Please enter your email address');
                return;
            }
            
            fetch('subscribe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    emailInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});