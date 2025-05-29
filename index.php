<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Home | " . SITE_NAME;

// Get featured classes
try {
    $stmt = $pdo->query("SELECT * FROM classes ORDER BY RAND() LIMIT 3");
    $featured_classes = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading featured classes.";
}

require_once 'includes/header.php';
?>

<style>
.hero {
    background: #0d6efd;
    min-height: 600px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero p {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 600px;
}

.hero-image {
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.explore-btn {
    background: white;
    color: #0d6efd;
    border: none;
    padding: 0.8rem 2rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 4px;
    transition: transform 0.2s;
}

.explore-btn:hover {
    transform: translateY(-2px);
}

.explore-btn i {
    font-size: 1.2rem;
}
</style>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-up">
                <h1 class="text-white">Monthly Enrichment Programs for Academic Excellence</h1>
                <p class="text-white">Join our expertly designed summer programs that combine learning with fun. Explore our exciting online class offerings and MAY schedule below.</p>
                <a href="classes.php" class="explore-btn">
                    <i class="fas fa-th-large"></i>
                    Explore Classes
                </a>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="assests/image/hero-image.jpg" alt="Happy students learning" class="img-fluid hero-image w-100">
            </div>
        </div>
    </div>
</section>

<section class="featured-classes py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary-subtle text-primary mb-3">Popular Programs</span>
            <h2 class="display-5 fw-bold text-primary mb-3">Featured Classes</h2>
            <p class="lead text-muted">Check out some of our most popular summer programs</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <?php foreach($featured_classes as $class): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $key * 100; ?>">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="position-relative">
                            <img src="assests/image/hero-image.jpg" class="card-img-top rounded-top-4" alt="<?php echo htmlspecialchars($class['title']); ?>">
                            <span class="badge bg-primary position-absolute top-0 end-0 m-3 rounded-pill px-3">
                                <?php echo $class['age_group']; ?>
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title h4 text-primary mb-3"><?php echo htmlspecialchars($class['title']); ?></h5>
                            <p class="card-text text-muted mb-4"><?php echo substr(htmlspecialchars($class['description']), 0, 100); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <span class="text-muted">Limited Slots</span>
                                </div>
                                <a href="class_detail.php?id=<?php echo $class['id']; ?>" class="btn btn-primary rounded-pill px-4">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="classes.php" class="btn btn-primary btn-lg rounded-pill px-5">
                <i class="fas fa-th-large me-2"></i>View All Classes
            </a>
        </div>
    </div>
</section>

<style>
.feature-card {
    position: relative;
    overflow: visible;
    background: transparent;
    padding: 0;
    margin: 0;
}

.feature-icon {
    position: relative;
    width: 100%;
    max-width: 200px;
    margin: 0 auto 1.5rem;
}

.feature-icon svg {
    width: 100%;
    height: auto;
}

.feature-icon i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) translateY(-10px);
    color: white;
    font-size: 1.2rem;
}

.feature-card h3 {
    color: #0d6efd;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.feature-card p {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0 auto;
    max-width: 240px;
}

.curved-line {
    fill: none;
    stroke: #0d6efd;
    stroke-width: 1.5;
    opacity: 0.5;
}
</style>

<section class="why-choose-us py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4" data-aos="fade-up">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <svg viewBox="0 0 200 50" class="w-100">
                            <path class="curved-line" d="M10,35 Q100,15 190,35"/>
                            <circle cx="100" cy="15" r="15" fill="#0d6efd"/>
                            <g transform="translate(92,9)">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </g>
                        </svg>
                    </div>
                    <h3 class="h4 mb-3">Expert Instructors</h3>
                    <p class="text-muted">Learn from experienced and certified teachers who are passionate about education and dedicated to student success.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <svg viewBox="0 0 200 50" class="w-100">
                            <path class="curved-line" d="M10,35 Q100,15 190,35"/>
                            <circle cx="100" cy="15" r="15" fill="#0d6efd"/>
                            <g transform="translate(92,9)">
                                <i class="fas fa-users"></i>
                            </g>
                        </svg>
                    </div>
                    <h3 class="h4 mb-3">Small Class Sizes</h3>
                    <p class="text-muted">Personalized attention with limited class sizes for better learning.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <svg viewBox="0 0 200 50" class="w-100">
                            <path class="curved-line" d="M10,35 Q100,15 190,35"/>
                            <circle cx="100" cy="15" r="15" fill="#0d6efd"/>
                            <g transform="translate(92,9)">
                                <i class="fas fa-smile-beam"></i>
                            </g>
                        </svg>
                    </div>
                    <h3 class="h4 mb-3">Fun Learning</h3>
                    <p class="text-muted">Engaging activities that make learning enjoyable for students.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>