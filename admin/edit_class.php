<?php
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_classes.php');
    exit;
}

$class_id = $_GET['id'];

// Get class data
$stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->execute([$class_id]);
$class = $stmt->fetch();

if (!$class) {
    header('Location: manage_classes.php');
    exit;
}

// Get all professors for dropdown
$professors = $pdo->query("SELECT id, name FROM professors ORDER BY name")->fetchAll();

// Set page title
$page_title = "Edit Class: " . htmlspecialchars($class['title']);

// Display notification if exists
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    echo "<div class='alert alert-" . ($notification['type'] === 'success' ? 'success' : 'danger') . "' role='alert'>";
    echo htmlspecialchars($notification['message']);
    echo "</div>";
    unset($_SESSION['notification']);
}
?>

<?php include '../includes/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Class: <?php echo htmlspecialchars($class['title']); ?></h1>
    <a href="manage_classes.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Classes
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="admin_actions.php" method="POST">
    <input type="hidden" name="action" value="edit_class">
    <input type="hidden" name="id" value="<?php echo $class_id; ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Title *</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($class['title']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($class['description']); ?></textarea>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="category" class="form-label">Category *</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($class['category']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="age_group" class="form-label">Age Group *</label>
            <input type="text" class="form-control" id="age_group" name="age_group" value="<?php echo htmlspecialchars($class['age_group']); ?>" required>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="schedule" class="form-label">Schedule</label>
            <input type="text" class="form-control" id="schedule" name="schedule" value="<?php echo htmlspecialchars($class['schedule']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="instructor" class="form-label">Instructor</label>
            <select class="form-select" id="instructor" name="instructor">
                <option value="">Select Instructor</option>
                <?php foreach ($professors as $professor): ?>
                    <option value="<?php echo htmlspecialchars($professor['name']); ?>" <?php echo ($professor['name'] === $class['instructor']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($professor['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="image_url" class="form-label">Image URL</label>
        <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($class['image_url']); ?>">
    </div>
    
    <button type="submit" class="btn btn-primary">Update Class</button>
</form>

<?php include '../includes/admin_footer.php'; ?>