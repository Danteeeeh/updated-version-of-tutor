<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Dashboard";

// Get stats for dashboard
$registrations = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
$messages = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
$classes = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();

$professors = $pdo->query("SELECT COUNT(*) FROM professors")->fetchColumn();
$services = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Get recent registrations
$recentRegistrations = $pdo->query("
    SELECT r.*, c.title as class_name 
    FROM registrations r
    LEFT JOIN classes c ON r.class_id = c.id
    ORDER BY r.registration_date DESC
    LIMIT 5
")->fetchAll();

// Get recent messages
$recentMessages = $pdo->query("
    SELECT * FROM messages 
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll();
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="manage_classes.php" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-book"></i> Manage Classes
            </a>
            <a href="manage_professors.php" class="btn btn-sm btn-outline-success">
                <i class="bi bi-person-video3"></i> Manage Professors
            </a>
            <a href="manage_services.php" class="btn btn-sm btn-outline-info">
                <i class="bi bi-gear"></i> Manage Services
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">Classes</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $classes; ?></h2>
                        <p class="mb-0 text-white-50">Active Courses</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-book" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_classes.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-primary border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">View All Classes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">Registrations</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $registrations; ?></h2>
                        <p class="mb-0 text-white-50">Total Enrollments</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-clipboard-check" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_registrations.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-success border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">Manage Registrations</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">New Messages</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $messages; ?></h2>
                        <p class="mb-0 text-white-50">Unread Messages</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_messages.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-warning border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">View Messages</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">Services</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $services; ?></h2>
                        <p class="mb-0 text-white-50">Available Services</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-gear" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_services.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-info border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">Manage Services</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-secondary mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">Professors</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $professors; ?></h2>
                        <p class="mb-0 text-white-50">Teaching Faculty</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-video3" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_professors.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-secondary border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">View All Professors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-dark mb-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-3 text-white-50">Total Services</h5>
                        <h2 class="display-4 mb-2 fw-bold"><?php echo $services; ?></h2>
                        <p class="mb-0 text-white-50">Available Programs</p>
                    </div>
                    <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-gear" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <a href="manage_services.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-dark border-0 py-3">
                <div class="row align-items-center g-0">
                    <div class="col-auto">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                    </div>
                    <div class="col">Manage Services</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Quick Actions</h5>
                <?php if (IS_ADMIN): ?>
                <a href="manage_admins.php" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-shield-lock"></i> Manage Admins
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="manage_classes.php?action=add" class="btn btn-outline-primary d-block">
                            <i class="bi bi-plus-circle"></i> Add New Class
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_professors.php?action=add" class="btn btn-outline-success d-block">
                            <i class="bi bi-person-plus"></i> Add New Professor
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_services.php?action=add" class="btn btn-outline-info d-block">
                            <i class="bi bi-plus-square"></i> Add New Service
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_messages.php?filter=unread" class="btn btn-outline-warning d-block">
                            <i class="bi bi-envelope"></i> View Unread Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Recent Registrations
                </h5>
                <a href="manage_registrations.php" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Student</th>
                                <th>Class</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentRegistrations)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No recent registrations
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($recentRegistrations as $reg): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                <?php echo strtoupper(substr($reg['student_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <?php echo htmlspecialchars($reg['student_name']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?php echo htmlspecialchars($reg['class_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <?php echo date('M j, Y', strtotime($reg['registration_date'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-warning">
                    <i class="bi bi-envelope me-2"></i>Recent Messages
                </h5>
                <a href="manage_messages.php" class="btn btn-sm btn-warning">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">From</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentMessages)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-envelope fs-2 d-block mb-2"></i>
                                    No recent messages
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($recentMessages as $msg): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-warning text-dark me-2">
                                                <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <?php echo htmlspecialchars($msg['name']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-muted mb-0 text-truncate" style="max-width: 250px;">
                                            <?php echo htmlspecialchars($msg['message']); ?>
                                        </p>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <?php echo date('M j, Y', strtotime($msg['created_at'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fetch today's schedule
async function refreshSchedules() {
    try {
        const response = await fetch('get_today_schedule.php');
        const data = await response.json();
        let html = '';
        
        if (data.length === 0) {
            html = `<tr><td colspan="6" class="text-center">No classes scheduled for today</td></tr>`;
        } else {
            data.forEach(schedule => {
                html += `
                    <tr>
                        <td>${schedule.start_time} - ${schedule.end_time}</td>
                        <td>
                            <strong>${schedule.class_name}</strong>
                            <div class="small text-muted">${schedule.category}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${schedule.instructor_image || '../assests/image/default-avatar.jpg'}" 
                                     class="rounded-circle me-2" 
                                     width="32" height="32">
                                ${schedule.instructor_name}
                            </div>
                        </td>
                        <td>${schedule.room}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                    <div class="progress-bar" style="width: ${(schedule.enrolled/schedule.capacity)*100}%"></div>
                                </div>
                                ${schedule.enrolled}/${schedule.capacity}
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="viewClassDetails(${schedule.class_id})">
                                <i class="bi bi-info-circle"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="manageEnrollments(${schedule.class_id})">
                                <i class="bi bi-people"></i>
                            </button>
                        </td>
                    </tr>`;
            });
        }
        
        document.getElementById('todaySchedule').innerHTML = html;
    } catch (error) {
        console.error('Error fetching schedules:', error);
    }
}

// View all schedules
function viewAllSchedules() {
    window.location.href = 'manage_schedules.php';
}

// View class details
function viewClassDetails(classId) {
    window.location.href = `manage_classes.php?action=view&id=${classId}`;
}

// Manage class enrollments
function manageEnrollments(classId) {
    window.location.href = `manage_enrollments.php?class_id=${classId}`;
}

// Initialize dashboard data
document.addEventListener('DOMContentLoaded', function() {
    refreshSchedules();
    
    // Refresh schedule every 5 minutes
    setInterval(() => {
        refreshSchedules();
    }, 300000);
});
</script>

<?php require_once '../includes/admin_footer.php'; ?>