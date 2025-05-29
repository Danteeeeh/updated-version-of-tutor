<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Register | " . SITE_NAME;

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get all classes for dropdown
try {
    $stmt = $pdo->query("SELECT id, title FROM classes ORDER BY title");
    $classes = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading classes. Please try again later.";
}

require_once 'includes/header.php';
?>

<section class="registration py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center mb-4">Register for Monthly Enrichment Programs</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form action="process_registration.php" method="POST" id="registrationForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="studentName" name="student_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="studentAge" class="form-label">Student Age</label>
                            <input type="number" class="form-control" id="studentAge" name="student_age" min="3" max="18" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parentName" class="form-label">Parent/Guardian Name</label>
                            <input type="text" class="form-control" id="parentName" name="parent_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parentEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="parentEmail" name="parent_email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parentPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="parentPhone" name="parent_phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="classSelect" class="form-label">Select Class</label>
                        <select class="form-select" id="classSelect" name="class_id" required>
                            <option value="">-- Select a Class --</option>
                            <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="specialNotes" class="form-label">Special Notes (Allergies, Learning Needs, etc.)</label>
                        <textarea class="form-control" id="specialNotes" name="special_notes" rows="3"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Registration</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    // Clear previous error messages
    form.querySelectorAll('.is-invalid').forEach(input => {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.remove();
        }
    });

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Remove any existing notices
        const existingNotice = document.querySelector('.alert');
        if (existingNotice) {
            existingNotice.remove();
        }

        // Create new notice
        const notice = document.createElement('div');
        notice.className = `alert ${data.success ? 'alert-success' : 'alert-danger'} mb-4`;
        notice.textContent = data.message;

        // Insert notice at the top of the form
        form.insertAdjacentElement('beforebegin', notice);

        if (data.success) {
            form.reset();
            // Scroll to the notice
            notice.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else if (data.errors) {
            // Display field-specific errors
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
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>