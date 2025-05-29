<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "Our Services | " . SITE_NAME;

// Fetch all services from database
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY display_order");
    $services = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading our services information.";
}

require_once 'includes/header.php';
?>

<section class="services-section py-5">
    <div class="container">
        <div class="section-header">
            <h2>Our Educational Services</h2>
            <p>Comprehensive learning programs tailored to your child's needs</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="services-tabs mb-5">
            <ul class="nav nav-tabs" id="servicesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="summer-tab" data-bs-toggle="tab" data-bs-target="#summer-programs" type="button" role="tab">
                        Monthly Enrichment Programs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="yearround-tab" data-bs-toggle="tab" data-bs-target="#yearround-programs" type="button" role="tab">
                        Year-Round Tutoring
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="special-tab" data-bs-toggle="tab" data-bs-target="#special-programs" type="button" role="tab">
                        Special Programs
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="servicesTabContent">
                <div class="tab-pane fade show active" id="summer-programs" role="tabpanel">
                    <h3 class="mb-4">Monthly Enrichment Programs</h3>
                    <p>Our summer programs are designed to prevent learning loss while keeping students engaged with fun, interactive lessons. Choose from a variety of subjects and age groups.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> Small group classes (max 8 students)</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Certified, experienced instructors</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Hands-on, project-based learning</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> Flexible scheduling options</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Progress reports and assessments</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Fun enrichment activities</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="yearround-programs" role="tabpanel">
                    <h3 class="mb-4">Year-Round Tutoring</h3>
                    <p>Personalized one-on-one tutoring to help students excel in their regular coursework or get ahead in challenging subjects.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> Individualized learning plans</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Homework help and test prep</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Flexible scheduling</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> All grade levels and subjects</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Regular progress updates</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Online or in-person options</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="special-programs" role="tabpanel">
                    <h3 class="mb-4">Special Programs</h3>
                    <p>Unique learning experiences beyond traditional academics to develop well-rounded skills.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> STEM workshops</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Creative writing clubs</li>
                                <li><i class="fas fa-check-circle text-primary"></i> College prep seminars</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="service-features">
                                <li><i class="fas fa-check-circle text-primary"></i> Study skills bootcamps</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Language immersion</li>
                                <li><i class="fas fa-check-circle text-primary"></i> Arts enrichment</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="all-services">
            <div class="section-header">
                <h2>All Our Services</h2>
                <p>Browse our complete range of educational offerings</p>
            </div>

            <?php if(!empty($services)): ?>
                <div class="row">
                    <?php foreach($services as $service): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i>
                                </div>
                                <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                                <p><?php echo htmlspecialchars($service['description']); ?></p>
                                <ul class="service-details">
                                    <li><strong>Ages:</strong> <?php echo htmlspecialchars($service['age_range']); ?></li>
                                    <li><strong>Format:</strong> <?php echo htmlspecialchars($service['format']); ?></li>
                                    <li><strong>Duration:</strong> <?php echo htmlspecialchars($service['duration']); ?></li>
                                </ul>
                                <?php if($service['has_class_link']): ?>
                                    <a href="classes.php?service=<?php echo urlencode($service['name']); ?>" class="btn btn-primary mt-2">View Classes</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Our services information is currently being updated. Please check back soon.</div>
            <?php endif; ?>
        </div>

        <div class="cta-section mt-5 text-center">
            <h3 class="mb-3">Ready to find the perfect program for your child?</h3>
            <p class="mb-4">Our education specialists can help match your student with the ideal learning experience.</p>
            <div class="cta-buttons">
                <a href="classes.php" class="btn btn-primary btn-lg me-3">Browse Classes</a>
                <a href="contact.php" class="btn btn-secondary btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>