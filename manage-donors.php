<?php
session_start();

require_once 'includes/db.php';

$action = $_POST['action'] ?? '';
$message = '';
$messageType = '';

// Handle success messages from redirects
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $message = 'Donor has been successfully added to our database.';
            $messageType = 'success';
            break;
        case 'updated':
            $message = 'Donor information has been successfully updated.';
            $messageType = 'success';
            break;
        case 'deleted':
            $message = 'Donor has been successfully removed from our database.';
            $messageType = 'success';
            break;
    }
}

// Handle error messages
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'validation':
            $message = 'Please correct the highlighted errors and try again.';
            $messageType = 'warning';
            break;
        case 'duplicate':
            $message = 'This email address is already registered in our system.';
            $messageType = 'warning';
            break;
        case 'database':
            $message = 'We encountered a technical problem. Please try again later.';
            $messageType = 'danger';
            break;
    }
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $donorData = [
        'name' => Database::sanitizeInput($_POST['name'] ?? ''),
        'email' => Database::sanitizeInput($_POST['email'] ?? ''),
        'phone' => Database::sanitizeInput($_POST['phone'] ?? ''),
        'blood_type' => Database::sanitizeInput($_POST['blood_type'] ?? ''),
        'district' => Database::sanitizeInput($_POST['district'] ?? '')
    ];
    
    // Validate input data
    $validation = Database::validateDonorData($donorData);
    
    if (!$validation['valid']) {
        // Store validation errors in session
        $_SESSION['validation_errors'] = $validation['errors'];
        $_SESSION['form_data'] = $donorData;
        header('Location: manage-donors.php?error=validation');
        exit();
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO donors (name, email, phone, blood_type, district) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([$donorData['name'], $donorData['email'], $donorData['phone'], $donorData['blood_type'], $donorData['district']])) {
            // Clear any stored form data on success
            unset($_SESSION['validation_errors'], $_SESSION['form_data']);
            header('Location: manage-donors.php?success=added');
            exit();
        } else {
            throw new Exception("Failed to insert donor record");
        }
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            header('Location: manage-donors.php?error=duplicate');
        } else {
            error_log("Donor creation failed: " . $e->getMessage());
            header('Location: manage-donors.php?error=database');
        }
        exit();
    } catch (Exception $e) {
        error_log("Donor creation error: " . $e->getMessage());
        header('Location: manage-donors.php?error=database');
        exit();
    }
}

if ($action === 'update' && isset($_POST['donor_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_id = filter_var($_POST['donor_id'], FILTER_VALIDATE_INT);
    if (!$donor_id) {
        header('Location: manage-donors.php?error=validation');
        exit();
    }
    
    // Sanitize input data
    $donorData = [
        'name' => Database::sanitizeInput($_POST['name'] ?? ''),
        'email' => Database::sanitizeInput($_POST['email'] ?? ''),
        'phone' => Database::sanitizeInput($_POST['phone'] ?? ''),
        'blood_type' => Database::sanitizeInput($_POST['blood_type'] ?? ''),
        'district' => Database::sanitizeInput($_POST['district'] ?? '')
    ];
    
    // Validate input data
    $validation = Database::validateDonorData($donorData);
    
    if (!$validation['valid']) {
        $_SESSION['validation_errors'] = $validation['errors'];
        $_SESSION['form_data'] = $donorData;
        header('Location: manage-donors.php?edit=' . $donor_id . '&error=validation');
        exit();
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE donors SET name = ?, email = ?, phone = ?, blood_type = ?, district = ? WHERE donor_id = ?";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([$donorData['name'], $donorData['email'], $donorData['phone'], $donorData['blood_type'], $donorData['district'], $donor_id])) {
            unset($_SESSION['validation_errors'], $_SESSION['form_data']);
            header('Location: manage-donors.php?success=updated');
            exit();
        } else {
            throw new Exception("Failed to update donor record");
        }
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            header('Location: manage-donors.php?edit=' . $donor_id . '&error=duplicate');
        } else {
            error_log("Donor update failed: " . $e->getMessage());
            header('Location: manage-donors.php?edit=' . $donor_id . '&error=database');
        }
        exit();
    } catch (Exception $e) {
        error_log("Donor update error: " . $e->getMessage());
        header('Location: manage-donors.php?edit=' . $donor_id . '&error=database');
        exit();
    }
}

if ($action === 'delete' && isset($_POST['donor_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_id = filter_var($_POST['donor_id'], FILTER_VALIDATE_INT);
    if (!$donor_id) {
        header('Location: manage-donors.php?error=validation');
        exit();
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM donors WHERE donor_id = ?";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([$donor_id])) {
            header('Location: manage-donors.php?success=deleted');
            exit();
        } else {
            throw new Exception("Failed to delete donor record");
        }
    } catch (Exception $e) {
        error_log("Donor deletion error: " . $e->getMessage());
        header('Location: manage-donors.php?error=database');
        exit();
    }
}

// Fetch donor for editing
$edit_donor = null;
if (isset($_GET['edit'])) {
    $edit_id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($edit_id) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM donors WHERE donor_id = ?");
            $stmt->execute([$edit_id]);
            $edit_donor = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$edit_donor) {
                header('Location: manage-donors.php?error=validation');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error fetching donor for edit: " . $e->getMessage());
            header('Location: manage-donors.php?error=database');
            exit();
        }
    }
}

// Fetch all donors
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM donors ORDER BY created_at DESC");
    $stmt->execute();
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching donors: " . $e->getMessage());
    $donors = [];
    $message = 'Unable to load donor information. Please refresh the page.';
    $messageType = 'danger';
}

// Get validation errors and form data from session
$validationErrors = $_SESSION['validation_errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['validation_errors'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donors - The Drop of Life</title>
    <meta name="description" content="Manage blood donors">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <div class="page-header" style="padding-top: 2rem;">
            <a href="donors.php" class="btn btn--outline">← Back</a>
            <div class="page-header__content">
                <h1>Manage Donors</h1>
                <p class="page-subtitle">Add, edit, and manage blood donors</p>
            </div>
            <div style="width: 70px; height: 44px; visibility: hidden;"></div>
        </div>

        <div class="container">
            <?php if ($message): ?>
                <div class="alert alert--<?= Database::escapeHtml($messageType) ?>">
                    <?= Database::escapeHtml($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($validationErrors)): ?>
                <div class="alert alert--warning">
                    <strong>Please correct the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1rem;">
                        <?php foreach ($validationErrors as $error): ?>
                            <li><?= Database::escapeHtml($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Donor Form -->
            <div class="card">
                <h2 class="card__title"><?= $edit_donor ? 'Edit Donor' : 'Add New Donor' ?></h2>
                <?php if ($edit_donor): ?>
                    <p style="margin-bottom: 1rem;"><a href="manage-donors.php" class="btn btn--outline btn--sm">← Cancel Edit</a></p>
                <?php endif; ?>
                
                <form method="POST" action="" novalidate>
                    <input type="hidden" name="action" value="<?= $edit_donor ? 'update' : 'add' ?>">
                    <?php if ($edit_donor): ?>
                        <input type="hidden" name="donor_id" value="<?= Database::escapeHtml($edit_donor['donor_id']) ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name *</label>
                            <input type="text" class="form-control" name="name" id="name" 
                                   value="<?= Database::escapeHtml($edit_donor['name'] ?? $formData['name'] ?? '') ?>" 
                                   required maxlength="100" placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address *</label>
                            <input type="email" class="form-control" name="email" id="email" 
                                   value="<?= Database::escapeHtml($edit_donor['email'] ?? $formData['email'] ?? '') ?>" 
                                   required maxlength="100" placeholder="Enter email address">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" id="phone" 
                                   value="<?= Database::escapeHtml($edit_donor['phone'] ?? $formData['phone'] ?? '') ?>" 
                                   required pattern="[0-9]{10}" maxlength="10" placeholder="0771234567">
                            <small>Enter 10-digit phone number</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="blood_type">Blood Type *</label>
                            <select class="form-control" name="blood_type" id="blood_type" required>
                                <option value="">Select Blood Type</option>
                                <?php 
                                $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                $selectedBloodType = $edit_donor['blood_type'] ?? $formData['blood_type'] ?? '';
                                foreach ($bloodTypes as $type): 
                                ?>
                                    <option value="<?= Database::escapeHtml($type) ?>" 
                                            <?= ($selectedBloodType === $type) ? 'selected' : '' ?>>
                                        <?= Database::escapeHtml($type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="district">District *</label>
                            <select class="form-control" name="district" id="district" required>
                                <option value="">Select District</option>
                                <?php 
                                $districts = ['Colombo', 'Kandy', 'Galle', 'Jaffna', 'Batticaloa', 'Anuradhapura', 'Polonnaruwa', 'Kurunegala', 'Puttalam', 'Ratnapura'];
                                $selectedDistrict = $edit_donor['district'] ?? $formData['district'] ?? '';
                                foreach ($districts as $district): 
                                ?>
                                    <option value="<?= Database::escapeHtml($district) ?>" 
                                            <?= ($selectedDistrict === $district) ? 'selected' : '' ?>>
                                        <?= Database::escapeHtml($district) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <!-- Spacer for layout -->
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn--primary">
                        <?= $edit_donor ? 'Update Donor' : 'Add Donor' ?>
                    </button>
                </form>
            </div>

            <!-- Existing Donors -->
            <div class="card">
                <h2 class="card__title">Existing Donors (<?= count($donors) ?>)</h2>
                
                <?php if (empty($donors)): ?>
                    <div class="empty-state">
                        <h3>No donors found</h3>
                        <p>Add your first donor using the form above.</p>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Blood Type</th>
                                    <th>Contact</th>
                                    <th>District</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donors as $donor): ?>
                                    <tr>
                                        <td><strong><?= Database::escapeHtml($donor['name']) ?></strong></td>
                                        <td>
                                            <span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', $donor['blood_type']))) ?>">
                                                <?= Database::escapeHtml($donor['blood_type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= Database::escapeHtml($donor['phone']) ?><br>
                                            <small><?= Database::escapeHtml($donor['email']) ?></small>
                                        </td>
                                        <td><?= Database::escapeHtml($donor['district']) ?></td>
                                        <td><?= date('M d, Y', strtotime($donor['created_at'])) ?></td>
                                        <td>
                                            <a href="manage-donors.php?edit=<?= Database::escapeHtml($donor['donor_id']) ?>" 
                                               class="btn btn--outline btn--sm" style="margin-right: 0.5rem;">Update</a>
                                            <form method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this donor? This action cannot be undone.')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="donor_id" value="<?= Database::escapeHtml($donor['donor_id']) ?>">
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
