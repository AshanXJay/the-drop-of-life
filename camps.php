<?php
require_once 'includes/db.php';
include 'includes/header.php';

// Get filter values
$district = $_GET['district'] ?? '';
$filter_date = $_GET['date'] ?? '';
$organizer = $_GET['organizer'] ?? '';

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
    $camps = [];
    $error_message = "Error fetching camps: " . $e->getMessage();
}
?>

<div class="container">
    <h1 class="page-title">Blood Donation Camps</h1>
    <p class="page-subtitle">Find and register for blood donation camps near you</p>

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
                    <input type="date" class="form-control" name="date" id="date" value="<?= htmlspecialchars($filter_date) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="organizer">Organizer</label>
                    <input type="text" class="form-control" name="organizer" id="organizer" 
                           placeholder="Search by organizer..." value="<?= htmlspecialchars($organizer) ?>">
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
            <?= htmlspecialchars($error_message) ?>
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
                            <th>Location</th>
                            <th>District</th>
                            <th>Organizer</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($camps as $camp): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($camp['date'])) ?></td>
                                <td><?= date('g:i A', strtotime($camp['time'])) ?></td>
                                <td><?= htmlspecialchars($camp['location']) ?></td>
                                <td><?= htmlspecialchars($camp['district']) ?></td>
                                <td><?= htmlspecialchars($camp['organizer']) ?></td>
                                <td><?= htmlspecialchars($camp['contact_number']) ?></td>
                                <td>
                                    <a href="register-camp.php?camp_id=<?= $camp['id'] ?>" 
                                       class="btn btn--primary btn--sm">Register</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Additional Information -->
    <div class="card">
        <h2 class="card__title">Before You Donate</h2>
        <div class="form-row">
            <div>
                <h3>✅ Requirements</h3>
                <ul>
                    <li>Age: 18-65 years</li>
                    <li>Weight: Minimum 50kg</li>
                    <li>Good health condition</li>
                    <li>Valid ID required</li>
                </ul>
            </div>
            <div>
                <h3>⏰ Preparation</h3>
                <ul>
                    <li>Get good sleep (7-8 hours)</li>
                    <li>Eat iron-rich meal</li>
                    <li>Stay hydrated</li>
                    <li>Avoid alcohol 24hrs before</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
