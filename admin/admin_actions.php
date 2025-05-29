<?php
session_start();
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Function to show notification and redirect
function showNoticeAndRedirect($message, $type, $redirect_url) {
    $_SESSION['notification'] = [
        'message' => $message,
        'type' => $type
    ];
    header("Location: $redirect_url");
    exit;
}

// Get the action type from request
$action = $_REQUEST['action'] ?? '';

// Handle different actions
switch ($action) {
    case 'delete_registration':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Registration deleted successfully!", "success", "manage_registrations.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting registration: " . $e->getMessage(), "error", "manage_registrations.php");
            }
        }
        break;

    case 'toggle_admin':
        if (isset($_GET['id'])) {
            try {
                // Get current status
                $stmt = $pdo->prepare("SELECT is_active FROM admin_users WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $current_status = $stmt->fetchColumn();
                
                // Toggle status
                $new_status = $current_status ? 0 : 1;
                $stmt = $pdo->prepare("UPDATE admin_users SET is_active = ? WHERE id = ?");
                $stmt->execute([$new_status, $_GET['id']]);
                
                showNoticeAndRedirect("Admin status updated successfully!", "success", "manage_admins.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating admin status: " . $e->getMessage(), "error", "manage_admins.php");
            }
        }
        break;

    // Admin Management
    case 'add_admin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['username'], $password_hash, $_POST['email']]);
                showNoticeAndRedirect("Admin added successfully!", "success", "manage_admins.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error adding admin: " . $e->getMessage(), "error", "manage_admins.php");
            }
        }
        break;

    case 'edit_admin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                if (!empty($_POST['password'])) {
                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ?, email = ? WHERE id = ?");
                    $stmt->execute([$_POST['username'], $password_hash, $_POST['email'], $_POST['id']]);
                } else {
                    $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
                    $stmt->execute([$_POST['username'], $_POST['email'], $_POST['id']]);
                }
                showNoticeAndRedirect("Admin updated successfully!", "success", "manage_admins.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating admin: " . $e->getMessage(), "error", "manage_admins.php");
            }
        }
        break;

    case 'delete_admin':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Admin deleted successfully!", "success", "manage_admins.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting admin: " . $e->getMessage(), "error", "manage_admins.php");
            }
        }
        break;

    // Professor Management
    case 'add_professor':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $pdo->prepare("INSERT INTO professors (name, email, specialization, bio) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['email'], $_POST['specialization'], $_POST['bio']]);
                showNoticeAndRedirect("Professor added successfully!", "success", "manage_professors.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error adding professor: " . $e->getMessage(), "error", "manage_professors.php");
            }
        }
        break;

    case 'edit_professor':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $stmt = $pdo->prepare("UPDATE professors SET name = ?, email = ?, specialization = ?, bio = ? WHERE id = ?");
                $stmt->execute([$_POST['name'], $_POST['email'], $_POST['specialization'], $_POST['bio'], $_POST['id']]);
                showNoticeAndRedirect("Professor updated successfully!", "success", "manage_professors.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating professor: " . $e->getMessage(), "error", "manage_professors.php");
            }
        }
        break;

    case 'delete_professor':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM professors WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Professor deleted successfully!", "success", "manage_professors.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting professor: " . $e->getMessage(), "error", "manage_professors.php");
            }
        }
        break;

    // Message Management
    case 'delete_message':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Message deleted successfully!", "success", "manage_messages.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting message: " . $e->getMessage(), "error", "manage_messages.php");
            }
        }
        break;

    case 'mark_message_read':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("UPDATE messages SET status = 'read' WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Message marked as read!", "success", "manage_messages.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating message: " . $e->getMessage(), "error", "manage_messages.php");
            }
        }
        break;

    // Service Order Management
    case 'move_service_up':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT display_order FROM services WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $current_order = $stmt->fetchColumn();
                
                if ($current_order > 1) {
                    $pdo->beginTransaction();
                    $pdo->prepare("UPDATE services SET display_order = display_order + 1 WHERE display_order = ?")->execute([$current_order - 1]);
                    $pdo->prepare("UPDATE services SET display_order = display_order - 1 WHERE id = ?")->execute([$_GET['id']]);
                    $pdo->commit();
                    showNoticeAndRedirect("Service order updated!", "success", "manage_services.php");
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                showNoticeAndRedirect("Error updating order: " . $e->getMessage(), "error", "manage_services.php");
            }
        }
        break;

    case 'move_service_down':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT display_order FROM services WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $current_order = $stmt->fetchColumn();
                
                $stmt = $pdo->query("SELECT MAX(display_order) FROM services");
                $max_order = $stmt->fetchColumn();
                
                if ($current_order < $max_order) {
                    $pdo->beginTransaction();
                    $pdo->prepare("UPDATE services SET display_order = display_order - 1 WHERE display_order = ?")->execute([$current_order + 1]);
                    $pdo->prepare("UPDATE services SET display_order = display_order + 1 WHERE id = ?")->execute([$_GET['id']]);
                    $pdo->commit();
                    showNoticeAndRedirect("Service order updated!", "success", "manage_services.php");
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                showNoticeAndRedirect("Error updating order: " . $e->getMessage(), "error", "manage_services.php");
            }
        }
        break;

    case 'add_service':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $pdo->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['icon']
                ]);
                showNoticeAndRedirect("Service added successfully!", "success", "manage_services.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error adding service: " . $e->getMessage(), "error", "manage_services.php");
            }
        }
        break;

    case 'edit_service':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['icon'],
                    $_POST['id']
                ]);
                showNoticeAndRedirect("Service updated successfully!", "success", "manage_services.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating service: " . $e->getMessage(), "error", "manage_services.php");
            }
        }
        break;

    case 'delete_service':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Service deleted successfully!", "success", "manage_services.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting service: " . $e->getMessage(), "error", "manage_services.php");
            }
        }
        break;

    case 'edit_class':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE classes 
                    SET title = ?, description = ?, instructor = ?, 
                        capacity = ?, age_group = ?, category = ?,
                        schedule = ?, image_url = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['instructor'],
                    $_POST['capacity'],
                    $_POST['age_group'],
                    $_POST['category'],
                    $_POST['schedule'],
                    $_POST['image_url'],
                    $_POST['id']
                ]);
                showNoticeAndRedirect("Class updated successfully!", "success", "manage_classes.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error updating class: " . $e->getMessage(), "error", "manage_classes.php");
            }
        }
        break;

    case 'delete_class':
        if (isset($_GET['id'])) {
            try {
                // First check if class exists
                $stmt = $pdo->prepare("SELECT id FROM classes WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                if (!$stmt->fetch()) {
                    showNoticeAndRedirect("Class not found!", "error", "manage_classes.php");
                }

                // Delete the class
                $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                showNoticeAndRedirect("Class deleted successfully!", "success", "manage_classes.php");
            } catch (PDOException $e) {
                showNoticeAndRedirect("Error deleting class: " . $e->getMessage(), "error", "manage_classes.php");
            }
        }
        break;
    }
?>