<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Our Summer Classes | " . SITE_NAME;

// Get all active classes
try {
    $stmt = $pdo->query("SELECT * FROM classes ORDER BY title ASC");
    $classes = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading classes. Please try again later.";
}

require_once 'includes/header.php';
?>

<section id="summer-classes" class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="badge bg-primary mb-3">Monthly Enrichment Programs</span>
            <h2 class="display-4 fw-bold text-primary mb-3">Group Classes  For Academic</h2>
            <p class="lead text-muted">Choose from our carefully curated selection of engaging and educational summer courses</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="filter-section mb-4" data-aos="fade-up">
            <div class="filter-buttons d-flex flex-wrap gap-2">
                <button class="filter-btn btn btn-primary rounded-pill px-4 active" data-filter="all">All Subjects</button>
                <?php 
                // Get unique categories from classes
                $categories = [];
                foreach($classes as $class) {
                    $cats = explode(',', $class['category']);
                    $categories = array_merge($categories, array_map('trim', $cats));
                }
                $categories = array_unique($categories);
                
                foreach($categories as $category): 
                    if(!empty($category)):
                        $category_clean = trim($category);
                ?>
                <button class="filter-btn btn btn-outline-primary rounded-pill px-4" data-filter="<?php echo $category_clean; ?>"><?php echo ucfirst($category_clean); ?></button>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
        
        <div class="classes-grid row g-4" data-aos="fade-up" data-aos-delay="200">
            <?php if(empty($classes)): ?>
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h3 class="h4 text-muted">No classes found</h3>
                    <p class="text-muted">Check back later for new classes</p>
                </div>
            </div>
            <?php endif; ?>
            <?php foreach($classes as $class): ?>
                <div class="col-md-6 col-lg-4" data-category="<?php echo htmlspecialchars($class['category']); ?>" data-aos="fade-up">
                    <div class="card h-100 shadow-sm hover-lift">
                        <div class="position-relative">
                            <img src="<?php echo isset($class['image_url']) && !empty($class['image_url']) ? $class['image_url'] : 'assets/img/default-class.jpg'; ?>" 
                                class="card-img-top" 
                                alt="<?php echo htmlspecialchars($class['title']); ?>"
                                style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                                <?php echo isset($class['category']) ? ucfirst($class['category']) : 'General'; ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h3 class="h5 card-title text-primary"><?php echo htmlspecialchars($class['title']); ?></h3>
                            <div class="small text-muted mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock me-2"></i> 
                                    <?php echo isset($class['duration']) ? $class['duration'] : 'Schedule TBA'; ?>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users me-2"></i> 
                                    <?php echo isset($class['capacity']) ? $class['capacity'] . ' students max' : 'Limited slots'; ?>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar me-2"></i> 
                                    <?php 
                                    $start_date = isset($class['start_date']) && !empty($class['start_date']) 
                                        ? date('M j, Y', strtotime($class['start_date'])) 
                                        : 'Starting soon'; 
                                    echo $start_date;
                                    ?>
                                </div>
                            </div>
                            <p class="card-text mb-4"><?php echo substr(htmlspecialchars($class['description']), 0, 120); ?>...</p>
                            <div class="card-actions mt-3">
                                <div class="text-center">
                                    <a href="class_detail.php?id=<?php echo $class['id']; ?>" class="btn btn-primary w-100">
                                        <i class="fas fa-check-circle me-2"></i> Avail a Slot
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="scheduleContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.card-actions .btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-actions .btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.card-actions .btn:hover::after {
    width: 200%;
    height: 200%;
}

.card-actions .btn:active {
    transform: scale(0.95);
}

.card-actions .btn i {
    transition: transform 0.3s ease;
}

.card-actions .btn:hover i {
    transform: scale(1.2);
}

.card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-text {
    flex-grow: 1;
}

.filter-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-btn {
    transition: all 0.3s ease;
    font-weight: 500;
    min-width: 120px;
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.filter-btn.active {
    color: white !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.filter-btn.active i {
    animation: bounce 0.5s;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.classes-grid [data-category] {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
</style>

<script>
// Function to fetch and display schedule
// Quick View Modal Handler
const quickViewModal = {
    modal: null,
    activeButton: null,
    loadingTemplate: `
        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="text-primary mb-2">Loading Class Information</h6>
                <p class="text-muted small">Please wait while we fetch the latest schedule...</p>
            </div>
        </div>
    `,
    errorTemplate: (message) => `
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-exclamation-circle text-danger fa-3x"></i>
            </div>
            <h5 class="text-danger mb-2">Oops! Something went wrong</h5>
            <p class="text-muted mb-4">${message}</p>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="quickViewModal.retry()">
                <i class="fas fa-redo me-2"></i>Try Again
            </button>
        </div>
    `,

    init() {
        this.modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        this.setupEventListeners();
    },

    setupEventListeners() {
        const modalEl = document.getElementById('scheduleModal');
        modalEl.addEventListener('hidden.bs.modal', () => this.resetState());
    },

    showLoading() {
        document.getElementById('scheduleContent').innerHTML = this.loadingTemplate;
    },

    showError(message) {
        document.getElementById('scheduleContent').innerHTML = this.errorTemplate(message);
    },

    resetState() {
        if (this.activeButton) {
            this.activeButton.disabled = false;
            this.activeButton.innerHTML = this.activeButton.dataset.originalContent;
            this.activeButton = null;
        }
    },

    setButtonLoading(button) {
        this.activeButton = button;
        this.activeButton.dataset.originalContent = button.innerHTML;
        this.activeButton.disabled = true;
        this.activeButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Loading...
        `;
    },

    async retry() {
        if (this.activeButton) {
            const classId = this.activeButton.dataset.classId;
            await this.fetchClassData(classId);
        }
    },

    async fetchClassData(classId) {
        try {
            const response = await fetch(`get_schedule.php?class_id=${classId}`);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            
            // Update modal content with class data
            document.getElementById('scheduleContent').innerHTML = `
                <div class="modal-body p-0">
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <img src="${data.instructor_image || 'assets/img/default-avatar.png'}" 
                                     class="rounded-circle" 
                                     alt="${data.instructor_name}"
                                     style="width: 64px; height: 64px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">${data.class_name}</h5>
                                <div class="text-muted mb-2">
                                    <i class="fas fa-tag me-2"></i>${data.category}
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="badge bg-${data.available_slots > 0 ? 'success' : 'danger'} rounded-pill">
                                        ${data.available_slots > 0 ? `${data.available_slots} slots available` : 'Class Full'}
                                    </div>
                                    <div class="badge bg-primary rounded-pill">
                                        ₱${parseFloat(data.price).toLocaleString()}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="instructor-info p-3 bg-light rounded mb-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Instructor</h6>
                                    <p class="mb-1">
                                        <i class="fas fa-user me-2 text-primary"></i>${data.instructor_name}
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-envelope me-2"></i>${data.instructor_email || 'Email not available'}<br>
                                        <i class="fas fa-phone me-2"></i>${data.instructor_phone || 'Phone not available'}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded h-100">
                                    <i class="fas fa-calendar-alt text-primary fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Course Duration</h6>
                                        <p class="mb-0 text-muted">
                                            ${data.start_date} - ${data.end_date}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded h-100">
                                    <i class="fas fa-clock text-primary fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Session Length</h6>
                                        <p class="mb-0 text-muted">${data.duration} minutes per class</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="description mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Course Description
                            </h6>
                            <p class="text-muted mb-0">${data.description}</p>
                        </div>

                        <div class="schedule-list">
                            <h6 class="mb-3">
                                <i class="fas fa-calendar-week me-2 text-primary"></i>
                                Class Schedule
                            </h6>
                            <div class="list-group list-group-flush">
                                ${data.schedule.map(session => `
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">${session.day}</h6>
                                                <p class="mb-0 small text-muted">${session.time}</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="badge bg-light text-dark">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    ${session.room}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-light border-top">
                        <div class="enrollment-stats p-3 bg-white rounded mb-3">
                            <div class="row text-center g-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-1">${data.max_students}</h4>
                                        <small class="text-muted">Total Capacity</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="mb-1">${data.enrolled_students}</h4>
                                        <small class="text-muted">Enrolled</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="text-${data.available_slots > 0 ? 'success' : 'danger'} mb-1">
                                            ${data.available_slots}
                                        </h4>
                                        <small class="text-muted">Available</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${data.available_slots > 0 ? `
                            <div class="alert alert-success mb-3" role="alert">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading mb-1">Registration Open!</h6>
                                        <p class="mb-0 small">Secure your spot now - only ${data.available_slots} slots remaining</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="enroll_class.php?class_id=${classId}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Register Now
                                </a>
                            </div>
                        ` : `
                            <div class="alert alert-danger mb-3" role="alert">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading mb-1">Class is Full</h6>
                                        <p class="mb-0 small">This class has reached maximum capacity. Please check other available classes.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="classes.php" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>View Other Classes
                                </a>
                            </div>
                        `}
                    </div>
                </div>
            `;
            
            return data;
        } catch (error) {
            throw new Error(error.message || 'Failed to fetch class data');
        }
    }
};

// Initialize Quick View Modal
document.addEventListener('DOMContentLoaded', () => quickViewModal.init());

// Show Schedule Function
async function showSchedule(classId) {
    const button = event.currentTarget;
    button.dataset.classId = classId;
    
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    modal.show();
    
    document.getElementById('scheduleContent').innerHTML = `
        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="text-primary mb-2">Loading Class Information</h6>
                <p class="text-muted small">Please wait while we fetch the latest schedule...</p>
            </div>
        </div>
    `;
    
    button.disabled = true;
    const originalContent = button.innerHTML;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Loading...
    `;

    try {
        // Fetch class data
        const data = await quickViewModal.fetchClassData(classId);

        // Fetch class data
        const response = await fetch(`get_schedule.php?class_id=${classId}`);
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        // Build the content HTML
        const contentHtml = `
            <div class="p-4 border-bottom">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        <img src="${data.instructor_image || 'assets/img/default-avatar.png'}" 
                             class="rounded-circle" 
                             alt="${data.instructor_name}"
                             style="width: 64px; height: 64px; object-fit: cover;">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">${data.class_name}</h5>
                        <div class="text-muted mb-2">
                            <i class="fas fa-tag me-2"></i>${data.category}
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge bg-${data.available_slots > 0 ? 'success' : 'danger'} rounded-pill">
                                ${data.available_slots > 0 ? `${data.available_slots} slots available` : 'Class Full'}
                            </div>
                            <div class="badge bg-primary rounded-pill">
                                ₱${parseFloat(data.price).toLocaleString()}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="instructor-info p-3 bg-light rounded mb-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Instructor</h6>
                            <p class="mb-1">
                                <i class="fas fa-user me-2 text-primary"></i>${data.instructor_name}
                            </p>
                            <p class="mb-0 small text-muted">
                                <i class="fas fa-envelope me-2"></i>${data.instructor_email || 'Email not available'}<br>
                                <i class="fas fa-phone me-2"></i>${data.instructor_phone || 'Phone not available'}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded h-100">
                            <i class="fas fa-calendar-alt text-primary fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Course Duration</h6>
                                <p class="mb-0 text-muted">
                                    ${data.start_date} - ${data.end_date}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded h-100">
                            <i class="fas fa-clock text-primary fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Session Length</h6>
                                <p class="mb-0 text-muted">${data.duration} minutes per class</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="description mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Course Description
                    </h6>
                    <p class="text-muted mb-0">${data.description}</p>
                </div>
            </div>

            <div class="p-4 border-bottom bg-light">
                <div class="row text-center">
                    <div class="col-4 border-end">
                        <h6 class="mb-1">
                            <i class="fas fa-users me-2 text-primary"></i>
                            ${data.max_students}
                        </h6>
                        <small class="text-muted">Capacity</small>
                    </div>
                    <div class="col-4 border-end">
                        <h6 class="mb-1">
                            <i class="fas fa-user-check me-2 text-success"></i>
                            ${data.max_students - data.available_slots}
                        </h6>
                        <small class="text-muted">Enrolled</small>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-1">
                            <i class="fas fa-chair me-2 text-warning"></i>
                            ${data.available_slots}
                        </h6>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <h6 class="mb-3">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    Class Schedule
                </h6>
                <div class="schedule-list">
                    ${data.schedule.map(session => `
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${session.day}</h6>
                                <p class="mb-0 text-muted">
                                    <i class="far fa-clock me-2"></i>${session.time}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-hourglass-half me-2"></i>${session.duration} mins
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-door-open me-2"></i>Room ${session.room}
                                </p>
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-${data.available_slots > 0 ? 'success' : 'danger'} rounded-pill">
                                    ${data.available_slots > 0 ? 'Available' : 'Full'}
                                </span>
                            </div>
                        </div>
                    `).join('')}
                </div>
                ${data.schedule.length === 0 ? `
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No schedule available yet.<br>Please contact us for more information.</p>
                    </div>
                ` : ''}
            </div>

            <div class="p-4 border-bottom">
                <h6 class="mb-3">
                    <i class="fas fa-phone-alt me-2 text-primary"></i>
                    Contact Options
                </h6>
                <div class="d-flex gap-2 mb-3">
                    <a href="tel:+1234567890" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-phone me-2"></i>Call Us
                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-white rounded">
                                    <h4 class="mb-1">${data.enrolled_students}</h4>
                                    <small class="text-muted">Enrolled</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-white rounded">
                                    <h4 class="text-${data.available_slots > 0 ? 'success' : 'danger'} mb-1">
                                        ${data.available_slots}
                                    </h4>
                                    <small class="text-muted">Available</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success mb-3" role="alert">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">Registration Open!</h6>
                                <p class="mb-0 small">Secure your spot now - only ${data.available_slots} slots remaining</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="enroll_class.php?id=${classId}" class="btn btn-lg btn-success">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </a>
                        <div class="d-flex gap-2">
                            <a href="class_detail.php?id=${classId}" class="btn btn-outline-primary flex-grow-1">
                                <i class="fas fa-info-circle me-2"></i>View Details
                            </a>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                ` : `
                    <div class="alert alert-danger mb-3" role="alert">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">Class Full</h6>
                                <p class="mb-0 small">This class is currently at maximum capacity. Please check other available classes or contact us for waitlist options.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="class_detail.php?id=${classId}" class="btn btn-primary">
                            <i class="fas fa-info-circle me-2"></i>View Class Details
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                `}
            </div>
            </div>`;

        document.getElementById('scheduleContent').innerHTML = scheduleHtml;
        document.getElementById('enrollButton').onclick = () => enrollInClass(classId);

        // Show the modal
        const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        scheduleModal.show();

        // Update enrollment button state
        const enrollButton = document.getElementById('enrollButton');
        if (data.available_slots === 0) {
            enrollButton.disabled = true;
            enrollButton.innerHTML = '<i class="fas fa-ban me-2"></i> Class Full';
            enrollButton.classList.remove('btn-primary');
            enrollButton.classList.add('btn-secondary');
        } else if (data.user_enrolled) {
            enrollButton.disabled = true;
            enrollButton.innerHTML = '<i class="fas fa-check me-2"></i> Already Enrolled';
            enrollButton.classList.remove('btn-primary');
            enrollButton.classList.add('btn-success');
        } else {
            enrollButton.disabled = false;
            enrollButton.innerHTML = '<i class="fas fa-user-plus me-2"></i> Enroll Now';
            enrollButton.classList.remove('btn-secondary', 'btn-success');
            enrollButton.classList.add('btn-primary');
        }
    } catch (error) {
        console.error('Error fetching schedule:', error);
        document.getElementById('scheduleContent').innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                <h5 class="text-danger">Unable to Load Schedule</h5>
                <p class="text-muted">There was an error loading the class schedule. Please try again later.</p>
            </div>
        `;
        // Show the modal even on error
        const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        scheduleModal.show();
        // Disable enroll button
        const enrollButton = document.getElementById('enrollButton');
        enrollButton.disabled = true;
        enrollButton.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Try Again Later';
        enrollButton.classList.remove('btn-primary');
        enrollButton.classList.add('btn-secondary');
    }
}

// Function to handle enrollment
async function enrollInClass(classId) {
    if (confirm('Would you like to enroll in this class?')) {
        try {
            const response = await fetch('enroll_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ class_id: classId })
            });
            const result = await response.json();
            
            if (result.success) {
                alert('Successfully enrolled in the class!');
                location.reload(); // Refresh to update availability
            } else {
                alert(result.message || 'Unable to enroll. Please try again.');
            }
        } catch (error) {
            console.error('Error enrolling:', error);
            alert('Unable to process enrollment. Please try again later.');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.classes-grid [data-category]');
    let activeFilter = 'all';

    function filterCards(filter) {
        let visibleCount = 0;
        let total = 0;
        let visibleCount = 0;

        // Show/hide cards based on filter
        cards.forEach(card => {
            const categories = card.getAttribute('data-category').split(',').map(cat => cat.trim());
            const matches = filter === 'all' || categories.includes(filter);
            
            // Remove any existing animation classes
            card.classList.remove('animate__animated', 'animate__fadeIn', 'animate__fadeOut');
            
            if (matches) {
                total++;
                card.style.display = '';
                // Add fade in animation with delay
                setTimeout(() => {
                    card.classList.add('animate__animated', 'animate__fadeIn');
                }, 50 * visibleCount);
                visibleCount++;
            } else {
                // Hide non-matching cards with animation
                card.classList.add('animate__animated', 'animate__fadeOut');
                setTimeout(() => {
                    card.style.display = 'none';
                }, 500);
            }
        });

        // Update active button style
        filterButtons.forEach(btn => {
            const btnFilter = btn.getAttribute('data-filter');
            const isActive = btnFilter === filter;
            
            // Remove all button styles
            btn.classList.remove('btn-primary', 'btn-outline-primary', 'active');
            
            if (isActive) {
                btn.classList.add('btn-primary', 'active');
            } else {
                btn.classList.add('btn-outline-primary');
            }
            
            // Add scale animation to icon if active
            const icon = btn.querySelector('i');
            if (icon && isActive) {
                icon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    icon.style.transform = 'scale(1)';
                }, 200);
            }
        });

        // Show/hide no results message
        const noResultsDiv = document.querySelector('.no-results');
        if (total === 0) {
            if (!noResultsDiv) {
                const message = `
                    <div class="col-12 no-results text-center py-5 animate__animated animate__fadeIn">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No classes found for "${filter}"</h4>
                        <p class="text-muted mb-4">Try selecting a different subject</p>
                        <button class="btn btn-outline-primary rounded-pill px-4" onclick="resetFilter()">
                            <i class="fas fa-redo me-2"></i>Show All Classes
                        </button>
                    </div>`;
                document.querySelector('.classes-grid').insertAdjacentHTML('beforeend', message);
            }
        } else if (noResultsDiv) {
            noResultsDiv.classList.add('animate__fadeOut');
            setTimeout(() => noResultsDiv.remove(), 500);
        }
    }

    // Reset filter function
    window.resetFilter = function() {
        filterCards('all');
    };

    // Add click handlers to filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            if (filter !== activeFilter) {
                activeFilter = filter;
                filterCards(filter);
            }
        });
    });

    // Hover effect for cards
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.querySelector('.card').style.transform = 'translateY(-10px)';
        });

        card.addEventListener('mouseleave', () => {
            card.querySelector('.card').style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>