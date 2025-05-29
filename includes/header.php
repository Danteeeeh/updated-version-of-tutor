<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Set default title if not defined
if (!isset($page_title)) {
    $page_title = SITE_NAME;
} else {
    $page_title = SITE_NAME . ' - ' . $page_title;
}

// Check for user authentication status
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : 'Quality education services'; ?>">
    
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Preconnect to CDNs for better performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>
    
    <header class="bg-white shadow-sm sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- Enhanced Logo Link - now the entire logo area is clickable -->
                <div class="d-flex align-items-center logo-container" onclick="window.location.href='index.php'" style="cursor: pointer;">
                    <img src="123.png" 
                         alt="<?php echo SITE_NAME; ?> Logo" 
                         width="50" 
                         height="50" 
                         class="d-inline-block align-top rounded-circle me-2"
                         loading="eager">
                    <span class="navbar-brand fw-bold"><?php echo SITE_NAME; ?></span>
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? ' active' : ''; ?>" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'classes.php' ? ' active' : ''; ?>" href="classes.php">Classes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'services.php' ? ' active' : ''; ?>" href="services.php">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? ' active' : ''; ?>" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'contact.php' ? ' active' : ''; ?>" href="contact.php">Contact</a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <form class="d-flex me-3" action="search.php" method="GET" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search classes..." name="q" aria-label="Search classes" required minlength="3">
                                <button class="btn btn-outline-primary" type="submit" aria-label="Submit search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <?php if ($is_logged_in): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    Account
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="btn-group" role="group">
                                <a href="register.php" class="btn btn-primary">Register</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="container mt-3">
                <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>