</main>

<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h3><?php echo SITE_NAME; ?></h3>
                <p>Providing quality education and enrichment programs to help students succeed academically and grow personally.</p>
                <div class="social-links">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Home</a></li>
                    <li><a href="classes.php" class="text-white">Classes</a></li>
                    <li><a href="services.php" class="text-white">Services</a></li>
                    <li><a href="about.php" class="text-white">About</a></li>
                    <li><a href="contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i> 123 Education Avenue, Learning City</li>
                    <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                    <li><i class="fas fa-envelope me-2"></i> <?php echo SITE_EMAIL; ?></li>
                    <li><i class="fas fa-clock me-2"></i> Mon-Fri: 9:00 AM - 6:00 PM</li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5>Newsletter</h5>
                <p>Subscribe to our newsletter for updates and promotions.</p>
                <form class="newsletter-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center pt-4 border-top">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>
</body>
</html>