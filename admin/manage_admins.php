<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Only superadmin can access this page
if (!IS_SUPERADMIN) {
    $_SESSION['error'] = "Access denied. You need superadmin privileges to access this page.";
    header('Location: index.php');
    exit;
}

// Set page title
$page_title = "Manage Admin Users";

// All admin actions are now handled by admin_actions.php

// Get filter parameters
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query based on filters
$query = "SELECT * FROM admin_users WHERE 1=1";
$params = [];

if ($role_filter !== 'all') {
    $query .= " AND role = ?";
    $params[] = $role_filter;
}

if ($status_filter !== 'all') {
    $query .= " AND is_active = ?";
    $params[] = ($status_filter === 'active' ? 1 : 0);
}

$query .= " ORDER BY role = 'superadmin' DESC, role, username";

// Get admins
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$admins = $stmt->fetchAll();

// Get counts
$count_stmt = $pdo->query("
    SELECT 
        role,
        COUNT(*) as total,
        SUM(is_active) as active,
        COUNT(*) - SUM(is_active) as inactive
    FROM admin_users 
    GROUP BY role
");
$role_counts = [];
while ($row = $count_stmt->fetch()) {
    $role_counts[$row['role']] = $row;
}

$total_active = array_sum(array_column($role_counts, 'active'));
$total_inactive = array_sum(array_column($role_counts, 'inactive'));
$total_admins = $total_active + $total_inactive;
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Admin Users</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="?role=all&status=<?php echo $status_filter; ?>" class="btn btn-sm btn-outline-secondary <?php echo $role_filter === 'all' ? 'active' : ''; ?>">
                All Roles (<?php echo $total_admins; ?>)
            </a>
            <a href="?role=superadmin&status=<?php echo $status_filter; ?>" class="btn btn-sm btn-outline-danger <?php echo $role_filter === 'superadmin' ? 'active' : ''; ?>">
                Superadmins (<?php echo ($role_counts['superadmin']['total'] ?? 0); ?>)
            </a>
            <a href="?role=admin&status=<?php echo $status_filter; ?>" class="btn btn-sm btn-outline-primary <?php echo $role_filter === 'admin' ? 'active' : ''; ?>">
                Admins (<?php echo ($role_counts['admin']['total'] ?? 0); ?>)
            </a>
        </div>
        
        <div class="btn-group me-2">
            <a href="?role=<?php echo $role_filter; ?>&status=all" class="btn btn-sm btn-outline-secondary <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                All Status
            </a>
            <a href="?role=<?php echo $role_filter; ?>&status=active" class="btn btn-sm btn-outline-success <?php echo $status_filter === 'active' ? 'active' : ''; ?>">
                Active (<?php echo $total_active; ?>)
            </a>
            <a href="?role=<?php echo $role_filter; ?>&status=inactive" class="btn btn-sm btn-outline-secondary <?php echo $status_filter === 'inactive' ? 'active' : ''; ?>">
                Inactive (<?php echo $total_inactive; ?>)
            </a>
        </div>
        
        <a href="add_admin.php" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Add New Admin
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (empty($admins)): ?>
<div class="alert alert-info">
    No admin users found matching the selected filters.
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $index => $admin): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
                    <?php if ($admin['id'] === $_SESSION['admin_id']): ?>
                    <span class="badge bg-info">You</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                <td>
                    <a href="mailto:<?php echo htmlspecialchars($admin['email']); ?>">
                        <?php echo htmlspecialchars($admin['email']); ?>
                    </a>
                </td>
                <td>
                    <span class="badge bg-<?php 
                        echo $admin['role'] === 'superadmin' ? 'danger' : 
                             ($admin['role'] === 'admin' ? 'primary' : 'secondary'); 
                    ?>">
                        <?php echo ucfirst($admin['role']); ?>
                    </span>
                </td>
                <td>
                    <?php if ($admin['is_active']): ?>
                    <span class="badge bg-success">Active</span>
                    <?php else: ?>
                    <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($admin['last_login']): ?>
                    <span title="<?php echo date('Y-m-d H:i:s', strtotime($admin['last_login'])); ?>">
                        <?php echo date('M j, Y H:i', strtotime($admin['last_login'])); ?>
                    </span>
                    <?php else: ?>
                    <span class="text-muted">Never</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="edit_admin.php?id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                            <?php if ($admin['is_active']): ?>
                            <a href="admin_actions.php?action=toggle_admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-warning" onclick="return confirm('Are you sure you want to deactivate this admin user?');">
                                <i class="bi bi-pause-circle"></i>
                            </a>
                            <?php else: ?>
                            <a href="admin_actions.php?action=toggle_admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-play-circle"></i>
                            </a>
                            <?php endif; ?>
                            
                            <a href="admin_actions.php?action=delete_admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this admin user? This action cannot be undone.');">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include('../includes/admin_footer.php'); ?>