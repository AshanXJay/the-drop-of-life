<?php
// camps.php - Public listing of all active camps
include 'includes/header.php';
include 'includes/db_connection.php';

// Function to get all active camps (future or ongoing)
function getActiveCamps() {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM camps WHERE end_date >= CURDATE() ORDER BY start_date ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

$camps = getActiveCamps();
?>

<link rel="stylesheet" href="assets/css/camps.css">

<div class="container">
    <div class="page-header">
        <h2>Blood Donation Camps</h2>
        <p>Find blood donation camps near you and help save lives</p>
    </div>

    <?php if (empty($camps)): ?>
        <div class="no-camps">
            <div class="no-camps-content">
                <h3>No Active Camps</h3>
                <p>There are currently no active blood donation camps scheduled.</p>
                <p>Please check back later or <a href="become_a_doner.php">register as a donor</a> to be notified of new camps.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="camps-container">
            <?php foreach ($camps as $camp): ?>
                <?php
                    $startDate = new DateTime($camp['start_date']);
                    $endDate = new DateTime($camp['end_date']);
                    $today = new DateTime();
                    
                    // Determine camp status
                    $status = '';
                    $statusClass = '';
                    if ($startDate > $today) {
                        $status = 'Upcoming';
                        $statusClass = 'upcoming';
                    } elseif ($startDate <= $today && $endDate >= $today) {
                        $status = 'Active Now';
                        $statusClass = 'active';
                    } elseif ($endDate < $today) {
                        $status = 'Completed';
                        $statusClass = 'completed';
                    }
                ?>
                
                <div class="camp-card">
                    <div class="camp-status <?php echo $statusClass; ?>">
                        <?php echo $status; ?>
                    </div>
                    
                    <div class="camp-content">
                        <h3 class="camp-name"><?php echo htmlspecialchars($camp['name']); ?></h3>
                        
                        <div class="camp-details">
                            <div class="detail-item">
                                <span class="icon">📍</span>
                                <span class="label">Location:</span>
                                <span class="value"><?php echo htmlspecialchars($camp['location']); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="icon">📅</span>
                                <span class="label">Date:</span>
                                <span class="value">
                                    <?php 
                                        if ($camp['start_date'] === $camp['end_date']) {
                                            echo $startDate->format('M j, Y');
                                        } else {
                                            echo $startDate->format('M j') . ' - ' . $endDate->format('M j, Y');
                                        }
                                    ?>
                                </span>
                            </div>
                            
                            <?php if ($camp['description']): ?>
                                <div class="detail-item description">
                                    <span class="icon">📝</span>
                                    <span class="label">Description:</span>
                                    <span class="value"><?php echo htmlspecialchars($camp['description']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($status === 'Upcoming' || $status === 'Active Now'): ?>
                            <div class="camp-actions">
                                <a href="become_a_doner.php" class="btn btn-primary">Register to Donate</a>
                                <button class="btn btn-secondary" onclick="shareCamp('<?php echo htmlspecialchars($camp['name']); ?>', '<?php echo htmlspecialchars($camp['location']); ?>')">
                                    Share
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="call-to-action">
            <h3>Want to Help Save Lives?</h3>
            <p>Register as a donor and get notified about new blood donation camps in your area.</p>
            <a href="become_a_doner.php" class="btn btn-large">Become a Donor Today</a>
        </div>
    <?php endif; ?>
</div>

<script>
function shareCamp(campName, location) {
    if (navigator.share) {
        navigator.share({
            title: `Blood Donation Camp: ${campName}`,
            text: `Join the blood donation camp at ${location}. Help save lives!`,
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback for browsers that don't support Web Share API
        const text = `Blood Donation Camp: ${campName} at ${location}. Help save lives! ${window.location.href}`;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Camp details copied to clipboard!');
            });
        } else {
            prompt('Copy this text to share:', text);
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>
