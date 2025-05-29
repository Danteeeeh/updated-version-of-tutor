<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: classes.php");
    exit;
}

$class_id = (int)$_GET['id'];

try {
    // Get class details
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$class_id]);
    $class = $stmt->fetch();
    
    if(!$class) {
        header("Location: classes.php");
        exit;
    }
    
    // Get professor details
    $stmt = $pdo->prepare("SELECT * FROM professors WHERE name = ?");
    $stmt->execute([$class['instructor']]);
    $professor = $stmt->fetch();
    
    // Get schedule
    $stmt = $pdo->prepare("
        SELECT * FROM schedule 
        WHERE class_id = ? 
        ORDER BY week_number, day_of_week, start_time
    ");
    $stmt->execute([$class_id]);
    $schedule = $stmt->fetchAll();
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading this class. Please try again later.";
}

$page_title = $class['title'] . " | " . SITE_NAME;
require_once 'includes/header.php';
?>

<section class="class-detail py-5">
    <div class="container">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $class['image_url']; ?>" alt="<?php echo htmlspecialchars($class['title']); ?>" class="img-fluid rounded">
                </div>
                <div class="col-md-6">
                    <h1><?php echo htmlspecialchars($class['title']); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($class['description']); ?></p>
                    
                    <div class="class-meta mb-4">
                        <div><strong>Age Group:</strong> <?php echo $class['age_group']; ?></div>
                        <div><strong>Instructor:</strong> <?php echo $class['instructor']; ?></div>
                        <div><strong>Schedule:</strong> <?php echo $class['schedule']; ?></div>
                        <div><strong>Category:</strong> <?php echo ucwords(str_replace(' ', ', ', $class['category'])); ?></div>
                    </div>
                    
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#registrationModal">
                        Register Now
                    </button>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h3>About the Instructor</h3>
                    <?php if($professor): ?>
                        <div class="professor-bio">
                            <h4><?php echo $professor['name']; ?></h4>
                            <p><?php echo $professor['bio']; ?></p>
                            <p><strong>Specialization:</strong> <?php echo $professor['specialization']; ?></p>
                        </div>
                    <?php else: ?>
                        <p>No instructor information available.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h3>Class Schedule</h3>
                    <?php if(!empty($schedule)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($schedule as $slot): ?>
                                        <tr>
                                            <td>Week <?php echo $slot['week_number']; ?></td>
                                            <td><?php echo $slot['day_of_week']; ?></td>
                                            <td>
                                                <?php 
                                                    echo date("g:i A", strtotime($slot['start_time'])) . " - " . 
                                                    date("g:i A", strtotime($slot['end_time'])); 
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $start = new DateTime($slot['start_time']);
                                                    $end = new DateTime($slot['end_time']);
                                                    $diff = $start->diff($end);
                                                    echo $diff->h . " hours";
                                                    if($diff->i > 0) echo " " . $diff->i . " minutes";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No schedule available for this class.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrationModalLabel">Register for <?php echo htmlspecialchars($class['title']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_registration.php" method="POST" id="registrationForm">
                <div class="modal-body">
                    <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                    <input type="hidden" name="class_name" value="<?php echo htmlspecialchars($class['title']); ?>">
                    <input type="hidden" name="instructor" value="<?php echo htmlspecialchars($class['instructor']); ?>">
                    
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentName" name="student_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="studentAge" class="form-label">Student Age</label>
                        <input type="number" class="form-control" id="studentAge" name="student_age" min="3" max="18" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parentName" class="form-label">Parent/Guardian Name</label>
                        <input type="text" class="form-control" id="parentName" name="parent_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="parentEmail" name="parent_email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parentPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="parentPhone" name="parent_phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="specialNotes" class="form-label">Special Notes</label>
                        <textarea class="form-control" id="specialNotes" name="special_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<script>
function showNotice(message, type = 'success') {
    const toast = document.getElementById('notificationToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const bsToast = new bootstrap.Toast(toast);

    // Set the title and style based on type
    if (type === 'success') {
        toastTitle.textContent = 'Success';
        toast.classList.remove('bg-danger', 'text-white');
        toast.classList.add('bg-success', 'text-white');
    } else {
        toastTitle.textContent = 'Error';
        toast.classList.remove('bg-success', 'text-white');
        toast.classList.add('bg-danger', 'text-white');
    }

    toastMessage.textContent = message;
    bsToast.show();
}

// Handle registration form submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
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
            showNotice(data.message, 'success');
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
        } else {
            showNotice(data.message, 'error');
            if (data.errors) {
                for (const field in data.errors) {
                    const input = form.querySelector(`[name="${field}"]`);
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
        showNotice('An error occurred. Please try again.', 'error');
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>