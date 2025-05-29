<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_config.php';

// Set default title if not defined
if (!isset($page_title)) {
    $page_title = "Admin - " . SITE_NAME;
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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="../index.php">
                    <span class="logo-icon">⚙️</span>
                    <span class="fw-bold"><?php echo SITE_NAME; ?> Admin</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="py-4">
        <div class="container"><?php if (isset($page_header)): ?>
            <h1 class="mb-4"><?php echo htmlspecialchars($page_header); ?></h1>
        <?php endif; ?>
