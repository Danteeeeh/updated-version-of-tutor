<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Manage Classes";

// Display notification if exists
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    echo "<div class='alert alert-" . ($notification['type'] === 'success' ? 'success' : 'danger') . "' role='alert'>";
    echo htmlspecialchars($notification['message']);
    echo "</div>";
    unset($_SESSION['notification']);
}

// Get all classes
$stmt = $pdo->query("SELECT * FROM classes ORDER BY created_at DESC");
$classes = $stmt->fetchAll();
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Classes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add_class.php" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Add New Class
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Age Group</th>
                <th>Schedule</th>
                <th>Instructor</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $index => $class): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($class['title']); ?></td>
                <td><?php echo htmlspecialchars($class['category']); ?></td>
                <td><?php echo htmlspecialchars($class['age_group']); ?></td>
                <td><?php echo htmlspecialchars($class['schedule']); ?></td>
                <td><?php echo htmlspecialchars($class['instructor'] ?? 'Not Assigned'); ?></td>
                <td><?php echo date('M j, Y', strtotime($class['created_at'])); ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="admin_actions.php?action=delete_class&id=<?php echo $class['id']; ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this class?');">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/admin_footer.php'); ?>