<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_config.php';

// Set default title if not defined
if (!isset($page_title)) {
    $page_title = "Admin - " . SITE_NAME;
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
</head>
<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?php echo BASE_URL; ?>/admin/dashboard.php">
            <i class="bi bi-gear-fill"></i> <?php echo SITE_NAME; ?> Admin
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="w-100"></div>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="<?php echo BASE_URL; ?>/admin/logout.php">Sign out</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/dashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'manage_classes.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/manage_classes.php">
                                <i class="bi bi-book"></i> Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'manage_registrations.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/manage_registrations.php">
                                <i class="bi bi-clipboard-check"></i> Registrations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'manage_messages.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/manage_messages.php">
                                <i class="bi bi-envelope"></i> Messages
                            </a>
                        </li>
                        <?php if (IS_ADMIN): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'manage_admins.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/manage_admins.php">
                                <i class="bi bi-person-badge"></i> Admin Users
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php if (isset($page_header)): ?>
                    <h1 class="mb-4"><?php echo htmlspecialchars($page_header); ?></h1>
                <?php endif; ?>
