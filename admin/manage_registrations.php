<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Manage Registrations";

// All registration actions are now handled by admin_actions.php

// Get filter parameters
$class_filter = isset($_GET['class']) ? (int)$_GET['class'] : 0;

// Get all classes for filter
$classes_stmt = $pdo->query("SELECT id, title FROM classes ORDER BY title");
$classes = $classes_stmt->fetchAll();

// Build query based on filters
$query = "
    SELECT r.*, c.title as class_title, c.instructor as instructor_name
    FROM registrations r
    LEFT JOIN classes c ON r.class_id = c.id
    WHERE 1=1
";

$params = [];

if ($class_filter > 0) {
    $query .= " AND r.class_id = ?";
    $params[] = $class_filter;
}

$query .= " ORDER BY r.registration_date DESC";

// Get registrations
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$registrations = $stmt->fetchAll();

// Get total count
$total_count = count($registrations);
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Registrations</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="dropdown me-2">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Filter by Class
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item <?php echo $class_filter === 0 ? 'active' : ''; ?>" 
                       href="?class=0">
                        All Classes (<?php echo $total_count; ?>)
                    </a>
                </li>
                <?php foreach ($classes as $class): ?>
                <li>
                    <a class="dropdown-item <?php echo $class_filter === $class['id'] ? 'active' : ''; ?>" 
                       href="?class=<?php echo $class['id']; ?>">
                        <?php echo htmlspecialchars($class['title']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php if (empty($registrations)): ?>
<div class="alert alert-info">
    No registrations found matching the selected filters.
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Info</th>
                <th>Class</th>
                <th>Instructor</th>
                <th>Parent Info</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrations as $index => $registration): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($registration['student_name']); ?></strong><br>
                    <small class="text-muted">Age: <?php echo htmlspecialchars($registration['student_age']); ?></small>
                </td>
                <td><?php echo htmlspecialchars($registration['class_title']); ?></td>
                <td><?php echo htmlspecialchars($registration['instructor_name'] ?? 'Not Assigned'); ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($registration['parent_name']); ?></strong><br>
                    <small>
                        <a href="mailto:<?php echo htmlspecialchars($registration['parent_email']); ?>">
                            <?php echo htmlspecialchars($registration['parent_email']); ?>
                        </a>
                        <?php if ($registration['parent_phone']): ?>
                        <br><?php echo htmlspecialchars($registration['parent_phone']); ?>
                        <?php endif; ?>
                    </small>
                </td>
                <td>
                    <span title="<?php echo date('Y-m-d H:i:s', strtotime($registration['registration_date'])); ?>">
                        <?php echo date('M j, Y', strtotime($registration['registration_date'])); ?>
                    </span>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="view_registration.php?id=<?php echo $registration['id']; ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($registration['parent_email']); ?>" class="btn btn-sm btn-outline-secondary" title="Email Parent">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <a href="admin_actions.php?action=delete_registration&id=<?php echo $registration['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this registration?');" title="Delete">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include('../includes/admin_footer.php'); ?>