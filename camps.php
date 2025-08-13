<?php
require_once 'includes/db.php';
// Removed header include for this page

// Get and sanitize filter values
$district = Database::sanitizeInput($_GET['district'] ?? '');
$filter_date = Database::sanitizeInput($_GET['date'] ?? '');
$organizer = Database::sanitizeInput($_GET['organizer'] ?? '');

// Validate inputs
$error_message = '';

if (!empty($district) && !Database::validateDistrict($district)) {
    $district = '';
    $error_message = 'Invalid district filter selected.';
}

if (!empty($filter_date) && !DateTime::createFromFormat('Y-m-d', $filter_date)) {
    $filter_date = '';
    $error_message = 'Invalid date format.';
}

// Build SQL query with proper filtering
$sql = "SELECT * FROM camps WHERE date >= CURDATE()";
$params = [];

if (!empty($district)) {
    $sql .= " AND district = ?";
    $params[] = $district;
}

if (!empty($filter_date)) {
    $sql .= " AND date = ?";
    $params[] = $filter_date;
}

if (!empty($organizer)) {
    // Limit organizer search length
    $organizer = substr($organizer, 0, 50);
    $sql .= " AND organizer LIKE ?";
    $params[] = "%$organizer%";
}

$sql .= " ORDER BY date ASC, time ASC";

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $camps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Camps page database error: " . $e->getMessage());
    $camps = [];
    $error_message = "Unable to load camp information. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Donation Camps - The Drop of Life</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find blood donation camps near you">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="page-header" style="padding-top: 2rem;">
            <a href="index.php" class="btn btn--outline">← Back</a>
            <div class="page-header__content">
                <h1>Blood Donation Camps</h1>
                <p class="page-subtitle">Find and register for blood donation camps near you</p>
            </div>
            <a href="manage-camps.php" class="btn btn--primary">Manage Camps</a>
        </div>

        <div class="container">

    <!-- Filter Form -->
    <div class="card">
        <h2 class="card__title">Filter Camps</h2>
        <form method="GET" action="">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="district">District</label>
                    <select class="form-control" name="district" id="district">
                        <option value="">All Districts</option>
                        <option value="Colombo" <?= $district === 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                        <option value="Kandy" <?= $district === 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                        <option value="Galle" <?= $district === 'Galle' ? 'selected' : '' ?>>Galle</option>
                        <option value="Jaffna" <?= $district === 'Jaffna' ? 'selected' : '' ?>>Jaffna</option>
                        <option value="Batticaloa" <?= $district === 'Batticaloa' ? 'selected' : '' ?>>Batticaloa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" value="<?= Database::escapeHtml($filter_date) ?>" max="2030-12-31">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="organizer">Organizer</label>
                    <input type="text" class="form-control" name="organizer" id="organizer" 
                           placeholder="Search by organizer..." value="<?= Database::escapeHtml($organizer) ?>" maxlength="50">
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn--primary">Search Camps</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Error Message -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert--danger">
            <?= Database::escapeHtml($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Results -->
    <?php if (empty($camps)): ?>
        <div class="card text-center">
            <h3>No camps found</h3>
            <p>No blood donation camps match your search criteria. Try adjusting your filters.</p>
        </div>
    <?php else: ?>
        <div class="card">
            <h2 class="card__title">Available Camps (<?= count($camps) ?> found)</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Camp Name</th>
                            <th>Location</th>
                            <th>District</th>
                            <th>Organizer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($camps as $camp): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($camp['date'])) ?></td>
                                <td><?= date('g:i A', strtotime($camp['time'])) ?></td>
                                <td><strong><?= Database::escapeHtml($camp['name']) ?></strong></td>
                                <td><?= Database::escapeHtml($camp['location']) ?></td>
                                <td><?= Database::escapeHtml($camp['district']) ?></td>
                                <td><?= Database::escapeHtml($camp['organizer']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
