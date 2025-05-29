<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Configuration - Customize these for each management page
$page_config = [
    'title' => 'Manage Items',              // Page title
    'table' => 'table_name',                // Database table name
    'id_field' => 'id',                     // Primary key field
    'name_field' => 'name',                 // Main display field
    'order_by' => 'id DESC',               // Default ordering
    'status_field' => 'is_active',          // Status field (if applicable)
    'can_delete' => true,                   // Whether deletion is allowed
    'can_edit' => true,                     // Whether editing is allowed
    'can_add' => true,                      // Whether adding new items is allowed
    'can_bulk_actions' => true,             // Whether bulk actions are allowed
    'display_fields' => [                   // Fields to display in the table
        'name' => 'Name',
        'email' => 'Email',
        'created_at' => 'Created'
    ]
];

// Set page title
$page_title = $page_config['title'];

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if (isset($_POST['item_ids']) && !empty($_POST['item_ids'])) {
        $item_ids = array_map('intval', $_POST['item_ids']);
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
        
        try {
            if ($action === 'delete' && $page_config['can_delete']) {
                $stmt = $pdo->prepare("DELETE FROM {$page_config['table']} WHERE {$page_config['id_field']} IN ($placeholders)");
                $stmt->execute($item_ids);
                $_SESSION['success'] = "Selected items have been deleted successfully.";
            } elseif ($action === 'toggle_status' && isset($page_config['status_field'])) {
                $stmt = $pdo->prepare("UPDATE {$page_config['table']} SET {$page_config['status_field']} = NOT {$page_config['status_field']} WHERE {$page_config['id_field']} IN ($placeholders)");
                $stmt->execute($item_ids);
                $_SESSION['success'] = "Status updated successfully.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Operation failed. Please try again.";
        }
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle single item actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['single_action'], $_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    $action = $_POST['single_action'];
    
    try {
        if ($action === 'delete' && $page_config['can_delete']) {
            $stmt = $pdo->prepare("DELETE FROM {$page_config['table']} WHERE {$page_config['id_field']} = ?");
            $stmt->execute([$item_id]);
            $_SESSION['success'] = "Item deleted successfully.";
        } elseif ($action === 'toggle_status' && isset($page_config['status_field'])) {
            $stmt = $pdo->prepare("UPDATE {$page_config['table']} SET {$page_config['status_field']} = NOT {$page_config['status_field']} WHERE {$page_config['id_field']} = ?");
            $stmt->execute([$item_id]);
            $_SESSION['success'] = "Status updated successfully.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Operation failed. Please try again.";
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Get status filter if status field exists
$status_filter = 'all';
if (isset($page_config['status_field'])) {
    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'all';
}

// Build query based on filters
$query = "SELECT * FROM {$page_config['table']}";
if ($status_filter !== 'all' && isset($page_config['status_field'])) {
    $filter_value = $status_filter === 'active' ? 1 : 0;
    $query .= " WHERE {$page_config['status_field']} = " . $filter_value;
}
$query .= " ORDER BY {$page_config['order_by']}";

// Get items
$stmt = $pdo->query($query);
$items = $stmt->fetchAll();

// Get counts if status field exists
$total_count = count($items);
$active_count = 0;
$inactive_count = 0;

if (isset($page_config['status_field'])) {
    $active_count = count(array_filter($items, function($item) use ($page_config) {
        return $item[$page_config['status_field']];
    }));
    $inactive_count = $total_count - $active_count;
}
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo htmlspecialchars($page_title); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if (isset($page_config['status_field'])): ?>
        <div class="btn-group me-2">
            <a href="?status_filter=all" class="btn btn-sm btn-outline-secondary <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                All (<?php echo $total_count; ?>)
            </a>
            <a href="?status_filter=active" class="btn btn-sm btn-outline-success <?php echo $status_filter === 'active' ? 'active' : ''; ?>">
                Active (<?php echo $active_count; ?>)
            </a>
            <a href="?status_filter=inactive" class="btn btn-sm btn-outline-danger <?php echo $status_filter === 'inactive' ? 'active' : ''; ?>">
                Inactive (<?php echo $inactive_count; ?>)
            </a>
        </div>
        <?php endif; ?>
        
        <?php if ($page_config['can_add']): ?>
        <a href="add_<?php echo strtolower(str_replace('manage_', '', basename($_SERVER['PHP_SELF']))); ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Add New
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php 
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (empty($items)): ?>
<div class="alert alert-info">
    No items found<?php echo $status_filter !== 'all' ? ' matching the selected filter' : ''; ?>.
</div>
<?php else: ?>

<form method="post" id="itemsForm">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <?php if ($page_config['can_bulk_actions']): ?>
                    <th>
                        <input type="checkbox" class="form-check-input" id="selectAll">
                    </th>
                    <?php endif; ?>
                    <th>#</th>
                    <?php foreach ($page_config['display_fields'] as $field => $label): ?>
                    <th><?php echo htmlspecialchars($label); ?></th>
                    <?php endforeach; ?>
                    <?php if (isset($page_config['status_field'])): ?>
                    <th>Status</th>
                    <?php endif; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <?php if ($page_config['can_bulk_actions']): ?>
                    <td>
                        <input type="checkbox" class="form-check-input item-checkbox" 
                               name="item_ids[]" value="<?php echo $item[$page_config['id_field']]; ?>">
                    </td>
                    <?php endif; ?>
                    <td><?php echo $index + 1; ?></td>
                    <?php foreach ($page_config['display_fields'] as $field => $label): ?>
                    <td>
                        <?php if ($field === 'email'): ?>
                        <a href="mailto:<?php echo htmlspecialchars($item[$field]); ?>">
                            <?php echo htmlspecialchars($item[$field]); ?>
                        </a>
                        <?php elseif ($field === 'created_at'): ?>
                        <?php echo date('M j, Y', strtotime($item[$field])); ?>
                        <?php else: ?>
                        <?php echo htmlspecialchars($item[$field]); ?>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                    
                    <?php if (isset($page_config['status_field'])): ?>
                    <td>
                        <span class="badge bg-<?php echo $item[$page_config['status_field']] ? 'success' : 'danger'; ?>">
                            <?php echo $item[$page_config['status_field']] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <?php endif; ?>
                    
                    <td>
                        <div class="btn-group">
                            <?php if ($page_config['can_edit']): ?>
                            <a href="edit_<?php echo strtolower(str_replace('manage_', '', basename($_SERVER['PHP_SELF']))); ?>?id=<?php echo $item[$page_config['id_field']]; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (isset($page_config['status_field'])): ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item[$page_config['id_field']]; ?>">
                                <input type="hidden" name="single_action" value="toggle_status">
                                <button type="submit" class="btn btn-sm btn-outline-<?php echo $item[$page_config['status_field']] ? 'warning' : 'success'; ?>">
                                    <i class="bi bi-<?php echo $item[$page_config['status_field']] ? 'pause-circle' : 'play-circle'; ?>"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if ($page_config['can_delete']): ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item[$page_config['id_field']]; ?>">
                                <input type="hidden" name="single_action" value="delete">
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to delete this item?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($page_config['can_bulk_actions'] && !empty($items)): ?>
    <div class="mt-3">
        <div class="btn-group">
            <?php if (isset($page_config['status_field'])): ?>
            <button type="submit" name="action" value="toggle_status" class="btn btn-outline-primary" disabled id="bulkToggleBtn">
                Toggle Status
            </button>
            <?php endif; ?>
            <?php if ($page_config['can_delete']): ?>
            <button type="submit" name="action" value="delete" class="btn btn-outline-danger" disabled id="bulkDeleteBtn"
                    onclick="return confirm('Are you sure you want to delete all selected items?');">
                Delete Selected
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkToggleBtn = document.getElementById('bulkToggleBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkButtons();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });

    function updateBulkButtons() {
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        if (bulkToggleBtn) bulkToggleBtn.disabled = checkedCount === 0;
        if (bulkDeleteBtn) bulkDeleteBtn.disabled = checkedCount === 0;
    }
});
</script>
<?php endif; ?>

<?php include('../includes/admin_footer.php'); ?>
