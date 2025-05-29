<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Manage Services";

// Display notification if exists
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    echo "<div class='alert alert-" . ($notification['type'] === 'success' ? 'success' : 'danger') . "' role='alert'>";
    echo htmlspecialchars($notification['message']);
    echo "</div>";
    unset($_SESSION['notification']);
}

// Get all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY display_order");
$services = $stmt->fetchAll();

// Get the maximum display order
$max_order = !empty($services) ? max(array_column($services, 'display_order')) : 0;
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Services</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add_service.php" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Add New Service
        </a>
    </div>
</div>

<?php if (empty($services)): ?>
<div class="alert alert-info">
    No services found. Click the "Add New Service" button to create one.
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Age Range</th>
                <th>Format</th>
                <th>Duration</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $index => $service): ?>
            <tr>
                <td><?php echo $service['id']; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($service['name']); ?></strong>
                </td>
                <td>
                    <div style="max-width: 300px;">
                        <?php echo htmlspecialchars(substr($service['description'], 0, 100)) . (strlen($service['description']) > 100 ? '...' : ''); ?>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($service['age_range']); ?></td>
                <td><?php echo htmlspecialchars($service['format']); ?></td>
                <td><?php echo htmlspecialchars($service['duration']); ?></td>
                <td>
                    <div class="btn-group-vertical">
                        <a href="admin_actions.php?action=move_service_up&id=<?php echo $service['id']; ?>" 
                           class="btn btn-sm btn-link p-0 mb-1" <?php echo $service['display_order'] <= 1 ? 'disabled' : ''; ?>>
                            <i class="bi bi-arrow-up"></i>
                        </a>
                        <span class="badge bg-secondary"><?php echo $service['display_order']; ?></span>
                        <a href="admin_actions.php?action=move_service_down&id=<?php echo $service['id']; ?>" 
                           class="btn btn-sm btn-link p-0 mt-1" <?php echo $service['display_order'] >= $max_order ? 'disabled' : ''; ?>>
                            <i class="bi bi-arrow-down"></i>
                        </a>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="admin_actions.php?action=delete_service&id=<?php echo $service['id']; ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this service?');">
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