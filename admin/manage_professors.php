<?php
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Manage Professors";

// All professor actions are now handled by admin_actions.php

// Get all professors
$stmt = $pdo->query("SELECT * FROM professors ORDER BY name");
$professors = $stmt->fetchAll();
?>
<?php include '../includes/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Professors</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add_professor.php" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Add New Professor
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Bio</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($professors as $index => $professor): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($professor['name']); ?></td>
                <td><?php echo htmlspecialchars($professor['specialization']); ?></td>
                <td><?php echo htmlspecialchars(substr($professor['bio'], 0, 50)) . (strlen($professor['bio']) > 50 ? '...' : ''); ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <form action="edit_professor.php" method="GET" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $professor['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </form>
                        <form action="admin_actions.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this professor?');">
                            <input type="hidden" name="action" value="delete_professor">
                            <input type="hidden" name="id" value="<?php echo $professor['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>