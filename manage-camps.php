<?php
require_once 'includes/db.php';
include 'includes/header.php';

$action = $_POST['action'] ?? '';
$message = '';

if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = $_POST['location'] ?? '';
    $district = $_POST['district'] ?? '';
    $organizer = $_POST['organizer'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    try {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO camps (name, date, time, location, district, organizer, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$name, $date, $time, $location, $district, $organizer, $contact_number])) {
            $message = '<div class="alert alert--success">Camp added successfully!</div>';
        } else {
            $message = '<div class="alert alert--danger">Failed to add camp.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
    }
}

if ($action === 'delete' && isset($_POST['camp_id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM camps WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$_POST['camp_id']])) {
            $message = '<div class="alert alert--success">Camp deleted successfully!</div>';
        } else {
            $message = '<div class="alert alert--danger">Failed to delete camp.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch all camps
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM camps ORDER BY date DESC");
    $camps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $camps = [];
    $message = '<div class="alert alert--danger">Error fetching camps: ' . $e->getMessage() . '</div>';
}
?>

<div class="container">
    <h1 class="page-title">Manage Blood Camps</h1>
    <p class="page-subtitle">Add, edit, and manage blood donation camps</p>

    <?= $message ?>

    <!-- Add New Camp -->
    <div class="card">
        <h2 class="card__title">Add New Camp</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Camp Name</label>
                    <input type="text" class="form-control" name="name" id="name" required 
                           placeholder="Blood Donation Drive">
                </div>
                <div class="form-group">
                    <label class="form-label" for="organizer">Organizer</label>
                    <input type="text" class="form-control" name="organizer" id="organizer" required 
                           placeholder="Red Cross Society">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" required 
                           min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="time">Time</label>
                    <input type="time" class="form-control" name="time" id="time" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="location">Location</label>
                    <input type="text" class="form-control" name="location" id="location" required 
                           placeholder="Community Center, Main Street">
                </div>
                <div class="form-group">
                    <label class="form-label" for="district">District</label>
                    <select class="form-control" name="district" id="district" required>
                        <option value="">Select District</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Galle">Galle</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Batticaloa">Batticaloa</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_number">Contact Number</label>
                <input type="tel" class="form-control" name="contact_number" id="contact_number" required 
                       placeholder="+94 71 234 5678">
            </div>

            <button type="submit" class="btn btn--primary">Add Camp</button>
        </form>
    </div>

    <!-- Existing Camps -->
    <div class="card">
        <h2 class="card__title">Existing Camps</h2>
        
        <?php if (empty($camps)): ?>
            <p class="text-center">No camps found. Add your first camp above.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>District</th>
                            <th>Organizer</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($camps as $camp): ?>
                            <tr>
                                <td><?= htmlspecialchars($camp['name']) ?></td>
                                <td><?= date('M j, Y', strtotime($camp['date'])) ?></td>
                                <td><?= date('g:i A', strtotime($camp['time'])) ?></td>
                                <td><?= htmlspecialchars($camp['location']) ?></td>
                                <td><?= htmlspecialchars($camp['district']) ?></td>
                                <td><?= htmlspecialchars($camp['organizer']) ?></td>
                                <td><?= htmlspecialchars($camp['contact_number']) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this camp?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="camp_id" value="<?= $camp['id'] ?>">
                                        <button type="submit" class="btn btn--secondary btn--sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Instructions -->
    <div class="card">
        <h2 class="card__title">Camp Management Guidelines</h2>
        <div class="form-row">
            <div>
                <h3>📝 Adding Camps</h3>
                <ul>
                    <li>Ensure all required fields are filled</li>
                    <li>Set realistic dates and times</li>
                    <li>Provide clear location details</li>
                    <li>Include working contact number</li>
                </ul>
            </div>
            <div>
                <h3>🎯 Best Practices</h3>
                <ul>
                    <li>Schedule camps at accessible locations</li>
                    <li>Allow 4-6 hours for camp duration</li>
                    <li>Coordinate with local health authorities</li>
                    <li>Promote camps 2-3 weeks in advance</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
