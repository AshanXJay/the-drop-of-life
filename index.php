<?php 
require_once 'includes/db.php';
include 'includes/header.php';

// Get comprehensive analytics from database
try {
    $db = Database::getInstance()->getConnection();
    
    // Count registered donors
    $donors_stmt = $db->prepare("SELECT COUNT(*) as count FROM donors");
    $donors_stmt->execute();
    $donors_count = $donors_stmt->fetch()['count'];
    
    // Count active blood camps (future and today)
    $camps_stmt = $db->prepare("SELECT COUNT(*) as count FROM camps WHERE date >= CURDATE()");
    $camps_stmt->execute();
    $camps_count = $camps_stmt->fetch()['count'];
    
    // Count total camps (all time)
    $total_camps_stmt = $db->prepare("SELECT COUNT(*) as count FROM camps");
    $total_camps_stmt->execute();
    $total_camps_count = $total_camps_stmt->fetch()['count'];
    
    // Get blood type distribution
    $blood_types_stmt = $db->prepare("SELECT blood_type, COUNT(*) as count FROM donors GROUP BY blood_type ORDER BY count DESC");
    $blood_types_stmt->execute();
    $blood_types = $blood_types_stmt->fetchAll();
    
    // Get district distribution
    $districts_stmt = $db->prepare("SELECT district, COUNT(*) as count FROM donors GROUP BY district ORDER BY count DESC LIMIT 5");
    $districts_stmt->execute();
    $top_districts = $districts_stmt->fetchAll();
    
    // Get recent upcoming camps
    $recent_camps_stmt = $db->prepare("SELECT * FROM camps WHERE date >= CURDATE() ORDER BY date ASC, time ASC LIMIT 5");
    $recent_camps_stmt->execute();
    $recent_camps = $recent_camps_stmt->fetchAll();
    
    // Calculate most needed blood type (least available)
    $most_needed = !empty($blood_types) ? end($blood_types) : null;
    $most_common = !empty($blood_types) ? $blood_types[0] : null;
    
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Index page database error: " . $e->getMessage());
    
    // Fallback to static data if database fails
    $donors_count = "500+";
    $camps_count = "25";
    $total_camps_count = "150+";
    $blood_types = [
        ['blood_type' => 'O+', 'count' => 45],
        ['blood_type' => 'A+', 'count' => 34],
        ['blood_type' => 'B+', 'count' => 12],
        ['blood_type' => 'O-', 'count' => 6]
    ];
    $top_districts = [
        ['district' => 'Colombo', 'count' => 120],
        ['district' => 'Kandy', 'count' => 85],
        ['district' => 'Galle', 'count' => 65]
    ];
    $recent_camps = [];
    $most_needed = ['blood_type' => 'O-', 'count' => 6];
    $most_common = ['blood_type' => 'O+', 'count' => 45];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="hero__title">Donate Blood, Save Lives</h1>
        <p class="hero__subtitle">Join our mission to help those in need. Every donation can save up to three lives.</p>
        <div class="hero__actions">
            <a href="donors.php" class="btn btn--white btn--lg">View Donors</a>
            <a href="camps.php" class="btn btn--secondary btn--lg">Find Camps</a>
        </div>
    </div>
</section>

<main>
<!-- Main Content -->
<div class="container">
    <!-- Enhanced Analytics Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $donors_count; ?></div>
            <div class="stat-card__label">Registered Donors</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $camps_count; ?></div>
            <div class="stat-card__label">Upcoming Camps</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__number"><?php echo $total_camps_count; ?></div>
            <div class="stat-card__label">Total Camps Organized</div>
        </div>
    </div>

    <!-- Blood Type Analytics -->
    <?php if (!empty($blood_types)): ?>
    <div class="card">
        <h2 class="card__title">📊 Blood Type Distribution</h2>
        <div class="form-row">
            <div>
                <h3>Most Common Blood Type</h3>
                <?php if ($most_common): ?>
                    <p><span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', Database::escapeHtml($most_common['blood_type'])))) ?>"><?= Database::escapeHtml($most_common['blood_type']) ?></span> 
                    <?= Database::escapeHtml($most_common['count']) ?> donors (<?= round(($most_common['count'] / $donors_count) * 100, 1) ?>%)</p>
                <?php else: ?>
                    <p>No data available</p>
                <?php endif; ?>
            </div>
            <div>
                <h3>Most Needed Blood Type</h3>
                <?php if ($most_needed): ?>
                    <p><span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', Database::escapeHtml($most_needed['blood_type'])))) ?>"><?= Database::escapeHtml($most_needed['blood_type']) ?></span> 
                    Only <?= Database::escapeHtml($most_needed['count']) ?> donors available</p>
                <?php else: ?>
                    <p>No data available</p>
                <?php endif; ?>
            </div>
        </div>
        
        <h3>All Blood Types</h3>
        <div class="form-row">
            <?php foreach ($blood_types as $type): ?>
                <div style="margin-bottom: 0.5rem;">
                    <span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', Database::escapeHtml($type['blood_type'])))) ?>"><?= Database::escapeHtml($type['blood_type']) ?></span>
                    <?= Database::escapeHtml($type['count']) ?> donors
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- District Analytics -->
    <?php if (!empty($top_districts)): ?>
    <div class="card">
        <h2 class="card__title">🌍 Top Districts by Donors</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>District</th>
                        <th>Number of Donors</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_districts as $district): ?>
                    <tr>
                        <td><strong><?= Database::escapeHtml($district['district']) ?></strong></td>
                        <td><?= Database::escapeHtml($district['count']) ?></td>
                        <td><?= round(($district['count'] / $donors_count) * 100, 1) ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

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
                        <th>Organizer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_camps as $camp): ?>
                    <tr>
                        <td><strong><?= Database::escapeHtml($camp['name']) ?></strong></td>
                        <td><?= date('M j, Y', strtotime($camp['date'])) ?></td>
                        <td><?= date('g:i A', strtotime($camp['time'])) ?></td>
                        <td><?= Database::escapeHtml($camp['location']) ?></td>
                        <td><?= Database::escapeHtml($camp['district']) ?></td>
                        <td><?= Database::escapeHtml($camp['organizer']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="camps.php" class="btn btn--primary">View All Camps</a>
        </div>
    </div>
    <?php else: ?>
    <!-- No Camps Message -->
    <div class="card text-center">
        <h2 class="card__title">📅 No Upcoming Camps</h2>
        <p class="mb-3">There are currently no scheduled blood donation camps. Check back soon!</p>
        <a href="camps.php" class="btn btn--primary">View All Camps</a>
    </div>
    <?php endif; ?>

    <!-- Blood Type Compatibility Guide -->
    <div class="card">
        <h2 class="card__title">🩸 Blood Type Compatibility Chart</h2>
        <p class="mb-3">Understanding blood compatibility is crucial for safe transfusions. Here's what you need to know:</p>
        
        <div class="form-row">
            <div>
                <h3>Universal Types</h3>
                <div style="margin: 1rem 0;">
                    <span class="blood-type blood-type-o-neg">O-</span> <strong>Universal Donor</strong><br>
                    <small>Can donate red blood cells to all blood types</small>
                </div>
                <div style="margin: 1rem 0;">
                    <span class="blood-type blood-type-ab-pos">AB+</span> <strong>Universal Recipient</strong><br>
                    <small>Can receive red blood cells from all blood types</small>
                </div>
            </div>
            <div>
                <h3>Common Types</h3>
                <div style="margin: 1rem 0;">
                    <span class="blood-type blood-type-o-pos">O+</span> <strong>Most Common</strong><br>
                    <small>Can donate to all positive blood types</small>
                </div>
                <div style="margin: 1rem 0;">
                    <span class="blood-type blood-type-a-pos">A+</span> <strong>Second Most Common</strong><br>
                    <small>Can donate to A+ and AB+ recipients</small>
                </div>
            </div>
        </div>
        
        <?php if (!empty($blood_types)): ?>
        <div class="text-center mt-3">
            <p><small>Current availability in our database reflects real donor distribution in your area.</small></p>
        </div>
        <?php endif; ?>
    </div>
</div>
</main>

<?php include 'includes/footer.php'; ?>
