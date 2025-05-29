<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="../index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'classes/') !== false ? 'active' : ''; ?>" href="../classes/manage_classes.php">
                    <i class="bi bi-book"></i> Classes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'professors/') !== false ? 'active' : ''; ?>" href="../professors/manage_professors.php">
                    <i class="bi bi-people"></i> Professors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'registrations/') !== false ? 'active' : ''; ?>" href="../registrations/manage_registrations.php">
                    <i class="bi bi-clipboard-check"></i> Registrations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'messages/') !== false ? 'active' : ''; ?>" href="../messages/manage_messages.php">
                    <i class="bi bi-envelope"></i> Messages
                    <?php 
                    $unread = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
                    if ($unread > 0): ?>
                        <span class="badge bg-danger rounded-pill"><?php echo $unread; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'services/') !== false ? 'active' : ''; ?>" href="../services/manage_services.php">
                    <i class="bi bi-list-check"></i> Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'subscribers/') !== false ? 'active' : ''; ?>" href="../subscribers/manage_subscribers.php">
                    <i class="bi bi-mailbox"></i> Subscribers
                </a>
            </li>
            <?php if (IS_SUPERADMIN): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'users/') !== false ? 'active' : ''; ?>" href="../users/manage_admins.php">
                    <i class="bi bi-shield-lock"></i> Admin Users
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>