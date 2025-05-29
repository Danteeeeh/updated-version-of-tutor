<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin/login.php');
    exit;
}

// Include database configuration
require_once __DIR__ . '/db_config.php';

// Check if user still exists in database
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ? AND is_active = 1");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

if (!$admin) {
    session_unset();
    session_destroy();
    header('Location: ../admin/login.php?error=session_expired');
    exit;
}

// Update session data
$_SESSION['admin_role'] = $admin['role'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['last_activity'] = time();

// Session timeout after 30 minutes of inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: ../admin/login.php?error=session_timeout');
    exit;
}

// Define admin permissions based on role
define('IS_SUPERADMIN', ($_SESSION['admin_role'] === 'superadmin'));
define('IS_ADMIN', (IS_SUPERADMIN || $_SESSION['admin_role'] === 'admin'));
define('IS_EDITOR', (IS_ADMIN || $_SESSION['admin_role'] === 'editor'));
?>