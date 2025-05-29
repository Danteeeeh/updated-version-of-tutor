<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db_config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        try {
            // Check credentials
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                
                // Set session variables
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Update last login
                $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } catch(PDOException $e) {
            $error = "An error occurred. Please try again later.";
            error_log("Login error: " . $e->getMessage());
        }
    }
}

// Set page title and header
$page_title = "Admin Login";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            padding: 0.6rem 0;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="text-center mb-4">
                        <h1 class="h3"><?php echo SITE_NAME; ?></h1>
                        <p class="text-muted">Admin Panel</p>
                    </div>
                    
                    <div class="card">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4">Login</h2>
                            
                            <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-warning">
                                <?php
                                $error_msg = match($_GET['error']) {
                                    'session_expired' => 'Your session has expired. Please login again.',
                                    'session_timeout' => 'You have been logged out due to inactivity.',
                                    default => 'Please login to continue.'
                                };
                                echo $error_msg;
                                ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                            <?php endif; ?>
                            
                            <form method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                                               required autofocus>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>