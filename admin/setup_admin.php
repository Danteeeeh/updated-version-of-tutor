<?php
require_once '../includes/config.php';
require_once '../includes/db_config.php';

try {
    // Create admin_users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        role ENUM('superadmin', 'admin', 'editor') NOT NULL DEFAULT 'editor',
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        last_login DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Check if we already have a superadmin
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE role = 'superadmin'");
    $superadmin_count = $stmt->fetchColumn();
    
    if ($superadmin_count === 0) {
        // Create default superadmin user
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $full_name = 'System Administrator';
        $email = 'admin@example.com';
        
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, full_name, email, role) VALUES (?, ?, ?, ?, 'superadmin')");
        $stmt->execute([$username, $password, $full_name, $email]);
        
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Setup - " . SITE_NAME . "</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container py-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow-sm'>
                    <div class='card-body p-4'>
                        <h2 class='card-title text-success mb-4'><i class='bi bi-check-circle-fill'></i> Setup Completed Successfully</h2>
                        <p class='card-text'>The admin_users table has been created and a default superadmin user has been set up.</p>
                        
                        <div class='alert alert-info mb-4'>
                            <h5 class='alert-heading'><i class='bi bi-key'></i> Default Login Credentials</h5>
                            <p class='mb-0'>
                                <strong>Username:</strong> admin<br>
                                <strong>Password:</strong> admin123
                            </p>
                        </div>
                        
                        <div class='alert alert-warning'>
                            <h5 class='alert-heading'><i class='bi bi-exclamation-triangle-fill'></i> Important Security Notice</h5>
                            <p class='mb-0'>Please change these credentials immediately after logging in!</p>
                        </div>
                        
                        <div class='text-center mt-4'>
                            <a href='login.php' class='btn btn-primary btn-lg'>
                                <i class='bi bi-box-arrow-in-right me-2'></i>Go to Login Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    } else {
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Setup - " . SITE_NAME . "</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container py-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow-sm'>
                    <div class='card-body p-4'>
                        <h2 class='card-title text-info mb-4'><i class='bi bi-info-circle-fill'></i> Setup Already Complete</h2>
                        <p class='card-text'>The admin_users table already exists and has a superadmin user.</p>
                        
                        <div class='text-center mt-4'>
                            <a href='login.php' class='btn btn-primary btn-lg'>
                                <i class='bi bi-box-arrow-in-right me-2'></i>Go to Login Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    }
} catch (PDOException $e) {
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Setup Error - " . SITE_NAME . "</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container py-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow-sm'>
                    <div class='card-body p-4'>
                        <h2 class='card-title text-danger mb-4'><i class='bi bi-x-circle-fill'></i> Setup Error</h2>
                        <div class='alert alert-danger'>
                            <p class='mb-0'>Error setting up admin: " . htmlspecialchars($e->getMessage()) . "</p>
                        </div>
                        
                        <div class='text-center mt-4'>
                            <button onclick='window.location.reload()' class='btn btn-primary btn-lg'>
                                <i class='bi bi-arrow-clockwise me-2'></i>Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
}
