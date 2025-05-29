<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Search Results | " . SITE_NAME;
$search_query = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';
$category = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';

// Get all unique categories for filter
$categories_stmt = $pdo->query("SELECT DISTINCT category FROM classes ORDER BY category");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);

require_once 'includes/header.php';
?>

<section class="search-section py-5 bg-light">
    <div class="container">
        <!-- Search Form -->
        <div class="search-form bg-white p-4 rounded shadow-sm mb-4">
            <form action="search.php" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control" placeholder="Search for classes..." 
                               value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="all">All Categories</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" 
                                <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div class="search-results">
            <?php if(!empty($search_query)): ?>
                <h2 class="mb-4">
                    Search Results 
                    <small class="text-muted">for "<?php echo htmlspecialchars($search_query); ?>"</small>
                    <?php if($category !== 'all'): ?>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($category); ?></span>
                    <?php endif; ?>
                </h2>
                
                <?php
                try {
                    $where_conditions = [];
                    $params = [];
                    
                    // Search in title, description, and professor name
                    $where_conditions[] = "(c.title LIKE :query OR c.description LIKE :query)";
                    $params['query'] = "%$search_query%";
                    
                    // Category filter
                    if($category !== 'all') {
                        $where_conditions[] = "c.category = :category";
                        $params['category'] = $category;
                    }
                    
                    $where_clause = implode(' AND ', $where_conditions);
                    
                    $stmt = $pdo->prepare("
                        SELECT c.*, p.name as professor_name, p.specialization 
                        FROM classes c
                        LEFT JOIN professors p ON c.professor_id = p.id
                        WHERE $where_clause
                        ORDER BY c.title
                    ");
                    $stmt->execute($params);
                    $results = $stmt->fetchAll();
                    
                    if(count($results) > 0): ?>
                        <p class="text-muted mb-4"><?php echo count($results); ?> results found</p>
                        <div class="row g-4">
                            <?php foreach($results as $class): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm hover-card">
                                        <?php if(!empty($class['image_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($class['image_url']); ?>" 
                                                 class="card-img-top" alt="<?php echo htmlspecialchars($class['title']); ?>"
                                                 onerror="this.src='assets/images/default-class.jpg'">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($class['title']); ?></h5>
                                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($class['category']); ?></span>
                                            </div>
                                            <?php if(!empty($class['professor_name'])): ?>
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-person-video3"></i>
                                                    <?php echo htmlspecialchars($class['professor_name']); ?>
                                                    <?php if(!empty($class['specialization'])): ?>
                                                        <span class="text-muted">| <?php echo htmlspecialchars($class['specialization']); ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                            <p class="card-text text-muted"><?php echo substr(htmlspecialchars($class['description']), 0, 100) . '...'; ?></p>
                                            <a href="class_detail.php?id=<?php echo $class['id']; ?>" class="btn btn-outline-primary stretched-link">
                                                View Details <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No classes found matching your search criteria.
                            <a href="search.php" class="alert-link">Clear search</a>
                        </div>
                    <?php endif;
                    
                } catch(PDOException $e) {
                    echo display_error("An error occurred while searching. Please try again.");
                    error_log("Search error: " . $e->getMessage());
                }
                ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                    <h3>Start Your Search</h3>
                    <p class="text-muted">Enter a keyword to search for classes</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
}
.search-section {
    min-height: calc(100vh - 200px);
}
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}
</style>
</section>

<?php require_once 'includes/footer.php'; ?>