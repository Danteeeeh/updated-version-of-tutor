<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Contact Us | " . SITE_NAME;
$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone'] ?? '');
    $message = sanitize_input($_POST['message']);
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required";
    }
    
    if (empty($message)) {
        $errors['message'] = "Message is required";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO messages (name, email, phone, message, created_at)
                VALUES (:name, :email, :phone, :message, NOW())
            ");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message
            ]);
            
            $message_sent = true;
            
            // In a real application, you would also send an email here
            
        } catch(PDOException $e) {
            error_log("Contact form error: " . $e->getMessage());
            $errors[] = "There was an error sending your message. Please try again.";
        }
    }
}

require_once 'includes/header.php';
?>

<section class="contact py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Contact Us</h2>
                <p>Have questions about our summer programs? Get in touch with us!</p>
                
                <?php if($message_sent): ?>
                    <div class="alert alert-success">
                        Thank you for your message! We'll get back to you soon.
                    </div>
                <?php elseif(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php echo implode('<br>', $errors); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="contact.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required
                               value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                               value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required><?php 
                            echo isset($message) ? htmlspecialchars($message) : ''; 
                        ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Education Avenue, Learning City</p>
                    <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i> info@tutorslounge.com</p>
                    <p><i class="fas fa-clock me-2"></i> Mon-Fri: 9:00 AM - 6:00 PM</p>
                    
                    <div class="map mt-4">
                        <!-- Embed Google Map here -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.678901234567!2d121.000000!3d14.000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDAwJzAwLjAiTiAxMjHCsDAwJzAwLjAiRQ!5e0!3m2!1sen!2sph!4v1234567890123!5m2!1sen!2sph" 
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>