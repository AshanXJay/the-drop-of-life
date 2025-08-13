<?php
require_once 'includes/db.php';
// Removed header include for this page

// Get and sanitize filter values
$district = Database::sanitizeInput($_GET['district'] ?? '');
$blood_type = Database::sanitizeInput($_GET['blood_type'] ?? '');
$name_search = Database::sanitizeInput($_GET['name'] ?? '');

// Validate filter inputs
$error_message = '';

if (!empty($district) && !Database::validateDistrict($district)) {
    $district = '';
    $error_message = 'Invalid district filter selected.';
}

if (!empty($blood_type) && !Database::validateBloodType($blood_type)) {
    $blood_type = '';
    $error_message = 'Invalid blood type filter selected.';
}

// Build SQL query with proper filtering
$sql = "SELECT * FROM donors WHERE 1=1";
$params = [];

if (!empty($district)) {
    $sql .= " AND district = ?";
    $params[] = $district;
}

if (!empty($blood_type)) {
    $sql .= " AND blood_type = ?";
    $params[] = $blood_type;
}

if (!empty($name_search)) {
    // Limit search term length for security
    $name_search = substr($name_search, 0, 50);
    $sql .= " AND name LIKE ?";
    $params[] = "%$name_search%";
}

$sql .= " ORDER BY created_at DESC, name ASC";

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Donors page database error: " . $e->getMessage());
    $donors = [];
    $error_message = "Unable to load donor information. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Donors - The Drop of Life</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View registered blood donors">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="page-header" style="padding-top: 2rem;">
            <a href="index.php" class="btn btn--outline">← Back</a>
            <div class="page-header__content">
                <h1>Blood Donors</h1>
                <p class="page-subtitle">View and search registered blood donors</p>
            </div>
            <a href="manage-donors.php" class="btn btn--primary">Manage Donors</a>
        </div>

        <div class="container">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert--danger"><?= Database::escapeHtml($error_message) ?></div>
            <?php endif; ?>

            <!-- Filter Form -->
            <div class="card">
                <h2 class="card__title">Filter Donors</h2>
                <form method="GET" action="" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Donor Name</label>
                            <input type="text" class="form-control" name="name" id="name" 
                                   value="<?= Database::escapeHtml($name_search) ?>" placeholder="Search by name" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="district">District</label>
                            <select class="form-control" name="district" id="district">
                                <option value="">All Districts</option>
                                <?php 
                                $districts = ['Colombo', 'Kandy', 'Galle', 'Jaffna', 'Batticaloa', 'Anuradhapura', 'Polonnaruwa', 'Kurunegala', 'Puttalam', 'Ratnapura'];
                                foreach ($districts as $d): 
                                ?>
                                    <option value="<?= Database::escapeHtml($d) ?>" <?= $district === $d ? 'selected' : '' ?>><?= Database::escapeHtml($d) ?></option>
                                <?php endforeach; ?>
                                <option value="Kandy" <?= $district === 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                                <option value="Galle" <?= $district === 'Galle' ? 'selected' : '' ?>>Galle</option>
                                <option value="Jaffna" <?= $district === 'Jaffna' ? 'selected' : '' ?>>Jaffna</option>
                                <option value="Batticaloa" <?= $district === 'Batticaloa' ? 'selected' : '' ?>>Batticaloa</option>
                                <option value="Anuradhapura" <?= $district === 'Anuradhapura' ? 'selected' : '' ?>>Anuradhapura</option>
                                <option value="Polonnaruwa" <?= $district === 'Polonnaruwa' ? 'selected' : '' ?>>Polonnaruwa</option>
                                <option value="Kurunegala" <?= $district === 'Kurunegala' ? 'selected' : '' ?>>Kurunegala</option>
                                <option value="Puttalam" <?= $district === 'Puttalam' ? 'selected' : '' ?>>Puttalam</option>
                                <option value="Ratnapura" <?= $district === 'Ratnapura' ? 'selected' : '' ?>>Ratnapura</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="blood_type">Blood Type</label>
                            <select class="form-control" name="blood_type" id="blood_type">
                                <option value="">All Blood Types</option>
                                <?php 
                                $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                foreach ($bloodTypes as $bt): 
                                ?>
                                    <option value="<?= Database::escapeHtml($bt) ?>" <?= $blood_type === $bt ? 'selected' : '' ?>><?= Database::escapeHtml($bt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary">Filter Donors</button>
                            <a href="donors.php" class="btn btn--outline">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Donors List -->
            <div class="card">
                <h2 class="card__title">Registered Blood Donors (<?= count($donors) ?>)</h2>
                
                <?php if (empty($donors)): ?>
                    <div class="empty-state">
                        <h3>No donors found</h3>
                        <p>No donors match your current filters.</p>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Blood Type</th>
                                    <th>District</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donors as $donor): ?>
                                    <tr>
                                        <td>
                                            <strong><?= Database::escapeHtml($donor['name']) ?></strong>
                                        </td>
                                        <td>
                                            <span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', Database::escapeHtml($donor['blood_type'])))) ?>">
                                                <?= Database::escapeHtml($donor['blood_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= Database::escapeHtml($donor['district']) ?></td>
                                        <td><?= Database::escapeHtml($donor['phone']) ?></td>
                                        <td><?= Database::escapeHtml($donor['email']) ?></td>
                                        <td><?= date('M d, Y', strtotime($donor['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Statistics -->
            <div class="card">
                <h2 class="card__title">Donor Statistics</h2>
                <div class="form-row">
                    <?php
                    // Calculate blood type statistics
                    $blood_type_counts = [];
                    foreach ($donors as $donor) {
                        $bt = $donor['blood_type'];
                        $blood_type_counts[$bt] = ($blood_type_counts[$bt] ?? 0) + 1;
                    }
                    ?>
                    <div class="text-center">
                        <h3>Total Donors</h3>
                        <p class="stat-number"><?= count($donors) ?></p>
                    </div>
                    <div class="text-center">
                        <h3>Most Common Type</h3>
                        <?php if (!empty($blood_type_counts)): ?>
                            <?php $most_common = array_keys($blood_type_counts, max($blood_type_counts))[0]; ?>
                            <p><span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', $most_common))) ?>"><?= $most_common ?></span></p>
                            <small><?= $blood_type_counts[$most_common] ?> donors</small>
                        <?php else: ?>
                            <p>-</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
