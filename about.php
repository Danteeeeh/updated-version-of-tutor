<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$page_title = "About Us | " . SITE_NAME;

// Fetch team members from database
try {
    $stmt = $pdo->query("SELECT * FROM professors ORDER BY name");
    $team_members = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "We're having trouble loading our team information.";
}

require_once 'includes/header.php';
?>

<section class="about-section py-5">
    <div class="container">
        <div class="section-header">
            <h2>About Teacher Anna's Learning Center</h2>
            <p>Discover our story, mission, and the team behind our success</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="about-content mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="assets/images/about-us.jpg" alt="Tutor's Lounge Team" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-3">Our Story</h3>
                    <p>Teacher Anna's Learning Center was founded in 2015 with a simple mission: to make quality education accessible to all students. What started as a small tutoring center has grown into a comprehensive learning hub serving hundreds of students annually.</p>
                    
                    <h3 class="mt-4 mb-3">Our Mission</h3>
                    <p>We believe every student deserves personalized attention and engaging learning experiences. Our approach combines academic excellence with creative teaching methods to inspire a lifelong love of learning.</p>
                    
                    <div class="stats mt-4">
                        <div class="row">
                            <div class="col-6 col-md-3 text-center">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Students</div>
                            </div>
                            <div class="col-6 col-md-3 text-center">
                                <div class="stat-number">20+</div>
                                <div class="stat-label">Courses</div>
                            </div>
                            <div class="col-6 col-md-3 text-center">
                                <div class="stat-number">98%</div>
                                <div class="stat-label">Satisfaction</div>
                            </div>
                            <div class="col-6 col-md-3 text-center">
                                <div class="stat-number">10+</div>
                                <div class="stat-label">Educators</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="team-section mt-5">
            <div class="section-header">
                <h2>Meet Our Team</h2>
                <p>Our dedicated educators are passionate about helping students succeed</p>
            </div>

            <?php if(!empty($team_members)): ?>
                <div class="row">
                    <?php foreach($team_members as $member): ?>
                        <div class="col-md-4 mb-4">
                            <div class="team-card">
                                <div class="team-img-container">
                                    <img src="<?php echo $member['image_url'] ?: 'assets/images/default-professor.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                         class="team-img">
                                </div>
                                <div class="team-info">
                                    <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                                    <p class="specialization"><?php echo htmlspecialchars($member['specialization']); ?></p>
                                    <p class="bio"><?php echo htmlspecialchars(substr($member['bio'], 0, 120)); ?>...</p>
                                    <a href="professor.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-primary">View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Our team information is currently unavailable.</div>
            <?php endif; ?>
        </div>

        <div class="testimonials mt-5">
            <div class="section-header">
                <h2>What Parents Say</h2>
                <p>Hear from families who have experienced our programs</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "My daughter's reading skills improved dramatically after just one summer with Tutor's Lounge. The teachers are amazing!"
                        </div>
                        <div class="testimonial-author">
                            <strong>Maria G.</strong>
                            <div class="author-role">Parent of 3rd grader</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "The math program helped my son gain confidence and actually enjoy learning. We'll be back next summer!"
                        </div>
                        <div class="testimonial-author">
                            <strong>James L.</strong>
                            <div class="author-role">Parent of 5th grader</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "As an educator myself, I'm impressed with the quality of instruction and care at Teacher Anna's Learning Center."
                        </div>
                        <div class="testimonial-author">
                            <strong>Dr. Susan K.</strong>
                            <div class="author-role">Parent & School Principal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>