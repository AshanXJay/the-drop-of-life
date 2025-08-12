<?php 
require_once 'includes/db.php';
include 'includes/header.php';

// Get live statistics from database
try {
    $db = Database::getInstance()->getConnection();
    
    // Count registered donors
    $donors_stmt = $db->query("SELECT COUNT(*) as count FROM donors");
    $donors_count = $donors_stmt->fetch()['count'];
    
    // Count active blood camps (future and today)
    $camps_stmt = $db->query("SELECT COUNT(*) as count FROM camps WHERE date >= CURDATE()");
    $camps_count = $camps_stmt->fetch()['count'];
    
    // Count emergency requests this month
    $emergency_stmt = $db->query("SELECT COUNT(*) as count FROM emergency_requests WHERE MONTH(requested_at) = MONTH(CURDATE()) AND YEAR(requested_at) = YEAR(CURDATE())");
    $emergency_count = $emergency_stmt->fetch()['count'];
    
    // Get recent upcoming camps
    $recent_camps_stmt = $db->query("SELECT * FROM camps WHERE date >= CURDATE() ORDER BY date ASC, time ASC LIMIT 3");
    $recent_camps = $recent_camps_stmt->fetchAll();
    
} catch (Exception $e) {
    // Fallback to static data if database fails
    $donors_count = "500+";
    $camps_count = "25";
    $emergency_count = "150";
    $recent_camps = [];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="hero__title">Donate Blood, Save Lives</h1>
        <p class="hero__subtitle">Join our mission to help those in need. Every donation can save up to three lives.</p>
        <div class="hero__actions">
            <a href="become_a_doner.php" class="btn btn--white btn--lg">Become A Donor</a>
            <a href="emergency.php" class="btn btn--secondary btn--lg">Emergency Request</a>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container">
    <!-- Live Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $donors_count; ?></div>
            <div class="stat-card__label">Registered Donors</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $camps_count; ?></div>
            <div class="stat-card__label">Active Blood Camps</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $emergency_count; ?></div>
            <div class="stat-card__label">Emergency Requests This Month</div>
        </div>
    </div>

    <?php if (!empty($recent_camps)): ?>
    <!-- Upcoming Camps -->
    <div class="card">
        <h2 class="card__title">🗓️ Upcoming Blood Donation Camps</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Camp Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>District</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_camps as $camp): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($camp['name']); ?></strong></td>
                        <td><?php echo date('M j, Y', strtotime($camp['date'])); ?></td>
                        <td><?php echo date('g:i A', strtotime($camp['time'])); ?></td>
                        <td><?php echo htmlspecialchars($camp['location']); ?></td>
                        <td><?php echo htmlspecialchars($camp['district']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="camps.php" class="btn btn--primary">View All Camps</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Features -->
    <div class="card">
        <h2 class="card__title">How We Help Save Lives</h2>
        <div class="form-row">
            <div>
                <h3>🩸 Easy Donation Process</h3>
                <p>Register as a donor in minutes and help save lives. Our streamlined process makes donating blood simple and convenient.</p>
            </div>
            <div>
                <h3>📍 Find Nearby Camps</h3>
                <p>Locate blood donation camps in your area. We maintain an updated schedule of all donation drives.</p>
            </div>
        </div>
        <div class="form-row">
            <div>
                <h3>⚡ Emergency Response</h3>
                <p>Quick response system for urgent blood needs. Get immediate assistance during medical emergencies.</p>
            </div>
            <div>
                <h3>🏥 Hospital Network</h3>
                <p>Connected with major hospitals to ensure blood reaches where it's needed most.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="card text-center">
        <h2 class="card__title">Ready to Save Lives?</h2>
        <p class="mb-3">Join thousands of donors who are making a difference in their communities.</p>
        <a href="become_a_doner.php" class="btn btn--primary btn--lg">Get Started Today</a>
    </div>

    <!-- Blood Types -->
    <div class="card">
        <h2 class="card__title">Blood Type Compatibility</h2>
        <p class="mb-3">Understanding blood types helps us match donors with recipients effectively:</p>
        <div class="form-row">
            <div>
                <span class="blood-type blood-type-o-neg">O-</span> Universal Donor - Can donate to all
            </div>
            <div>
                <span class="blood-type blood-type-ab-pos">AB+</span> Universal Recipient - Can receive from all
            </div>
        </div>
        <div class="form-row">
            <div>
                <span class="blood-type blood-type-o-pos">O+</span> Most Common - Can donate to positive types
            </div>
            <div>
                <span class="blood-type blood-type-a-pos">A+</span> Can donate to A+ and AB+
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
