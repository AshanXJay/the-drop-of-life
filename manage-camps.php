<?php
require_once 'includes/db.php';
// Removed header include for this page

$action = $_POST['action'] ?? '';
$message = '';

// Handle success messages from redirects
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $message = '<div class="alert alert--success">Camp added successfully!</div>';
            break;
        case 'updated':
            $message = '<div class="alert alert--success">Camp updated successfully!</div>';
            break;
        case 'deleted':
            $message = '<div class="alert alert--success">Camp deleted successfully!</div>';
            break;
    }
}

if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = $_POST['location'] ?? '';
    $district = $_POST['district'] ?? '';
    $organizer = $_POST['organizer'] ?? '';

    try {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO camps (name, date, time, location, district, organizer) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$name, $date, $time, $location, $district, $organizer])) {
            header('Location: manage-camps.php?success=added');
            exit();
        } else {
            $message = '<div class="alert alert--danger">Failed to add camp.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
    }
}

if ($action === 'update' && isset($_POST['camp_id'])) {
    $camp_id = $_POST['camp_id'];
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = $_POST['location'] ?? '';
    $district = $_POST['district'] ?? '';
    $organizer = $_POST['organizer'] ?? '';

    try {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE camps SET name = ?, date = ?, time = ?, location = ?, district = ?, organizer = ? WHERE camp_id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$name, $date, $time, $location, $district, $organizer, $camp_id])) {
            header('Location: manage-camps.php?success=updated');
            exit();
        } else {
            $message = '<div class="alert alert--danger">Failed to update camp.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
    }
}

if ($action === 'delete' && isset($_POST['camp_id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM camps WHERE camp_id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$_POST['camp_id']])) {
            header('Location: manage-camps.php?success=deleted');
            exit();
        } else {
            $message = '<div class="alert alert--danger">Failed to delete camp.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch all camps
$edit_camp = null;
if (isset($_GET['edit'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM camps WHERE camp_id = ?");
        $stmt->execute([$_GET['edit']]);
        $edit_camp = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $message = '<div class="alert alert--danger">Error fetching camp: ' . $e->getMessage() . '</div>';
    }
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM camps ORDER BY date DESC");
    $camps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $camps = [];
    $message = '<div class="alert alert--danger">Error fetching camps: ' . $e->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blood Camps - The Drop of Life</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage blood donation camps">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="page-header" style="padding-top: 2rem;">
            <a href="camps.php" class="btn btn--outline">← Back</a>
            <div class="page-header__content">
                <h1>Manage Blood Camps</h1>
                <p class="page-subtitle">Add, edit, and manage blood donation camps</p>
            </div>
            <div style="width: 70px; height: 44px; visibility: hidden;"></div>
        </div>

        <div class="container">

    <?= $message ?>

    <!-- Add New Camp -->
    <div class="card">
        <h2 class="card__title"><?= $edit_camp ? 'Edit Camp' : 'Add New Camp' ?></h2>
        <?php if ($edit_camp): ?>
            <p style="margin-bottom: 1rem;"><a href="manage-camps.php" class="btn btn--outline btn--sm">← Cancel Edit</a></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?= $edit_camp ? 'update' : 'add' ?>">
            <?php if ($edit_camp): ?>
                <input type="hidden" name="camp_id" value="<?= $edit_camp['camp_id'] ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Camp Name</label>
                    <input type="text" class="form-control" name="name" id="name" required 
                           placeholder="Blood Donation Drive" value="<?= $edit_camp ? htmlspecialchars($edit_camp['name']) : '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="organizer">Organizer</label>
                    <input type="text" class="form-control" name="organizer" id="organizer" required 
                           placeholder="Red Cross Society" value="<?= $edit_camp ? htmlspecialchars($edit_camp['organizer']) : '' ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" required 
                           min="<?= date('Y-m-d') ?>" value="<?= $edit_camp ? $edit_camp['date'] : '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="time">Time</label>
                    <input type="time" class="form-control" name="time" id="time" required
                           value="<?= $edit_camp ? $edit_camp['time'] : '' ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="location">Location</label>
                    <input type="text" class="form-control" name="location" id="location" required 
                           placeholder="Community Center, Main Street" value="<?= $edit_camp ? htmlspecialchars($edit_camp['location']) : '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="district">District</label>
                    <select class="form-control" name="district" id="district" required>
                        <option value="">Select District</option>
                        <option value="Colombo" <?= ($edit_camp && $edit_camp['district'] === 'Colombo') ? 'selected' : '' ?>>Colombo</option>
                        <option value="Kandy" <?= ($edit_camp && $edit_camp['district'] === 'Kandy') ? 'selected' : '' ?>>Kandy</option>
                        <option value="Galle" <?= ($edit_camp && $edit_camp['district'] === 'Galle') ? 'selected' : '' ?>>Galle</option>
                        <option value="Jaffna" <?= ($edit_camp && $edit_camp['district'] === 'Jaffna') ? 'selected' : '' ?>>Jaffna</option>
                        <option value="Batticaloa" <?= ($edit_camp && $edit_camp['district'] === 'Batticaloa') ? 'selected' : '' ?>>Batticaloa</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn--primary"><?= $edit_camp ? 'Update Camp' : 'Add Camp' ?></button>
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
                                <td>
                                    <a href="manage-camps.php?edit=<?= $camp['camp_id'] ?>" class="btn btn--outline btn--sm" style="margin-right: 0.5rem;">Update</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Delete this camp?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="camp_id" value="<?= $camp['camp_id'] ?>">
                                        <button type="submit" class="btn btn--outline btn--danger btn--sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
