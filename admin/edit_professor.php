<?php
require_once '../includes/db_config.php';
require_once '../includes/auth_check.php';

// Set page title
$page_title = "Edit Professor";

// Get professor ID from URL
$professor_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch professor details
$stmt = $pdo->prepare("SELECT * FROM professors WHERE id = ?");
$stmt->execute([$professor_id]);
$professor = $stmt->fetch();

// If professor not found, redirect to manage page with error
if (!$professor) {
    $_SESSION['error'] = "Professor not found.";
    header("Location: manage_professors.php");
    exit;
}
?>

<?php include '../includes/admin_header.php'; ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Professor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="manage_professors.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="admin_actions.php" method="POST">
                        <input type="hidden" name="action" value="edit_professor">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($professor['id']); ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                value="<?php echo htmlspecialchars($professor['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" id="specialization" name="specialization"
                                value="<?php echo htmlspecialchars($professor['specialization']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" required><?php echo htmlspecialchars($professor['bio']); ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="manage_professors.php" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
