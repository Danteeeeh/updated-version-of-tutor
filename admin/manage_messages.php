<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Manage Messages";

// All message actions are now handled by admin_actions.php

// Get filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Prepare the query based on filter
$query = "SELECT * FROM messages";
if ($filter === 'unread') {
    $query .= " WHERE is_read = 0";
}
$query .= " ORDER BY created_at DESC";

// Get messages
$stmt = $pdo->query($query);
$messages = $stmt->fetchAll();

// Mark messages as read when page loads (unless viewing unread only)
if ($filter !== 'unread' && !empty($messages)) {
    $message_ids = array_column($messages, 'id');
    $placeholders = implode(',', array_fill(0, count($message_ids), '?'));
    
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id IN ($placeholders)")->execute($message_ids);
}
?>
<?php include('../includes/admin_header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Messages</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="?filter=all" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'all' ? 'active' : ''; ?>">
                All Messages
            </a>
            <a href="?filter=unread" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'unread' ? 'active' : ''; ?>">
                Unread Only
            </a>
        </div>
    </div>
</div>

<?php if (empty($messages)): ?>
<div class="alert alert-info">
    <?php echo $filter === 'unread' ? 'No unread messages found.' : 'No messages found.'; ?>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $index => $message): ?>
            <tr <?php echo $message['is_read'] ? '' : 'class="table-warning"'; ?>>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($message['name']); ?></td>
                <td>
                    <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                        <?php echo htmlspecialchars($message['email']); ?>
                    </a>
                </td>
                <td>
                    <?php if (!empty($message['phone'])): ?>
                    <a href="tel:<?php echo htmlspecialchars($message['phone']); ?>">
                        <?php echo htmlspecialchars($message['phone']); ?>
                    </a>
                    <?php else: ?>
                    <span class="text-muted">Not provided</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars(substr($message['message'], 0, 50)) . (strlen($message['message']) > 50 ? '...' : ''); ?></td>
                <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                <td>
                    <?php if ($message['is_read']): ?>
                    <span class="badge bg-secondary">Read</span>
                    <?php else: ?>
                    <span class="badge bg-warning">Unread</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="view_message.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="reply_message.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-reply"></i> Reply
                        </a>
                        <a href="admin_actions.php?action=delete_message&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this message?');">
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