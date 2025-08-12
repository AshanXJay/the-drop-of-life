

<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

$action = $_POST['action'] ?? '';
$message = '';

$fields = [
    'name' => $_POST['name'] ?? '',
    'date' => $_POST['date'] ?? '',
    'time' => $_POST['time'] ?? '',
    'location' => $_POST['location'] ?? '',
    'district' => $_POST['district'] ?? '',
    'organizer' => $_POST['organizer'] ?? ''
];

$pdo = Database::getConnection();

if ($action === 'add') {
    $sql = "INSERT INTO camps (name, date, time, location, district, organizer) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array_values($fields))) {
        $message = 'Camp added successfully!';
    } else {
        $message = 'Failed to add camp.';
    }
}

if ($action === 'edit' && isset($_POST['camp_id'])) {
    $sql = "UPDATE camps SET name=?, date=?, time=?, location=?, district=?, organizer=? WHERE camp_id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([...array_values($fields), $_POST['camp_id']])) {
        $message = 'Camp updated successfully!';
    } else {
        $message = 'Failed to update camp.';
    }
}

if ($action === 'delete' && isset($_POST['camp_id'])) {
    $sql = "DELETE FROM camps WHERE camp_id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$_POST['camp_id']])) {
        $message = 'Camp deleted successfully!';
    } else {
        $message = 'Failed to delete camp.';
    }
}

$stmt = $pdo->query("SELECT * FROM camps ORDER BY date DESC, time DESC");
$camps = $stmt->fetchAll();
?>

<link rel="stylesheet" href="home.css">
<main class="manage-camps-main" style="max-width:900px;margin:40px auto;">
    <h2 style="color:#d32f2f;margin-bottom:24px;">Manage Camps</h2>
    <?php if (isset($message) && $message): ?>
        <div style="padding:10px 0;color:#d32f2f;font-weight:600;"> <?= htmlspecialchars($message) ?> </div>
    <?php endif; ?>
    <form method="POST" class="filter-form" style="margin-bottom:32px;">
        <input type="hidden" name="action" value="add">
        <div class="filter-grid">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" required placeholder="Camp Name" pattern=".{3,}" title="At least 3 characters">
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="text" name="time" required placeholder="HH:MM" pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" title="Format HH:MM">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" required placeholder="Location" pattern=".{3,}" title="At least 3 characters">
            </div>
            <div class="form-group">
                <label for="district">District</label>
                <input type="text" name="district" required placeholder="District" pattern=".{3,}" title="At least 3 characters">
            </div>
            <div class="form-group">
                <label for="organizer">Organizer</label>
                <input type="text" name="organizer" required placeholder="Organizer" pattern=".{3,}" title="At least 3 characters">
            </div>
        </div>
        <button type="submit" class="search-btn" style="margin-top:18px;">Add Camp</button>
    </form>
    <h3 style="margin-bottom:18px;">Existing Camps</h3>
    <div style="overflow-x:auto;">
    <table class="manage-camps-table" style="width:100%;border-collapse:collapse;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.07);overflow:hidden;">
        <thead style="background:#d32f2f;color:#fff;">
            <tr>
                <th>Name</th><th>Date</th><th>Time</th><th>Location</th><th>District</th><th>Organizer</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($camps as $camp): ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="name" value="<?= htmlspecialchars($camp['name']) ?>" required pattern=".{3,}" title="At least 3 characters"></td>
                    <td><input type="date" name="date" value="<?= htmlspecialchars($camp['date']) ?>" required></td>
                    <td><input type="text" name="time" value="<?= htmlspecialchars($camp['time']) ?>" required pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" title="Format HH:MM"></td>
                    <td><input type="text" name="location" value="<?= htmlspecialchars($camp['location']) ?>" required pattern=".{3,}" title="At least 3 characters"></td>
                    <td><input type="text" name="district" value="<?= htmlspecialchars($camp['district']) ?>" required pattern=".{3,}" title="At least 3 characters"></td>
                    <td><input type="text" name="organizer" value="<?= htmlspecialchars($camp['organizer']) ?>" required pattern=".{3,}" title="At least 3 characters"></td>
                    <td style="white-space:nowrap;">
                        <input type="hidden" name="camp_id" value="<?= $camp['camp_id'] ?>">
                        <button type="submit" name="action" value="edit" class="search-btn" style="background:#1976d2;margin-right:6px;">Edit</button>
                        <button type="submit" name="action" value="delete" class="search-btn" style="background:#d32f2f;" onclick="return confirm('Delete this camp?')">Delete</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
