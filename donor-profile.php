<?php
require_once 'includes/db.php';
include 'includes/header.php';

// Start session for donor authentication (demo mode)
session_start();

// Demo donor ID - in production, use proper login/session
if (!isset($_SESSION['donor_id'])) {
    $_SESSION['donor_id'] = 1;
}
$donor_id = (int) $_SESSION['donor_id'];

$message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $blood_type = $_POST['blood_type'] ?? '';
    $district = $_POST['district'] ?? '';
    
    if (empty($name) || empty($email) || empty($phone) || empty($blood_type) || empty($district)) {
        $message = '<div class="alert alert--danger">All fields are required.</div>';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "UPDATE donors SET name=?, email=?, phone=?, blood_type=?, district=? WHERE donor_id=?";
            $stmt = $db->prepare($sql);
            if ($stmt->execute([$name, $email, $phone, $blood_type, $district, $donor_id])) {
                $message = '<div class="alert alert--success">Profile updated successfully!</div>';
            } else {
                $message = '<div class="alert alert--danger">Update failed. Please try again.</div>';
            }
        } catch (Exception $e) {
            $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch donor details
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM donors WHERE donor_id = ?");
    $stmt->execute([$donor_id]);
    $donor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$donor) {
        // Create demo donor if not exists
        $sql = "INSERT INTO donors (donor_id, name, email, phone, blood_type, district, registration_date) 
                VALUES (?, 'Demo User', 'demo@example.com', '+94 71 234 5678', 'O+', 'Colombo', NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([$donor_id]);
        
        // Fetch again
        $stmt = $db->prepare("SELECT * FROM donors WHERE donor_id = ?");
        $stmt->execute([$donor_id]);
        $donor = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $donor = [
        'name' => 'Demo User',
        'email' => 'demo@example.com',
        'phone' => '+94 71 234 5678',
        'blood_type' => 'O+',
        'district' => 'Colombo',
        'registration_date' => date('Y-m-d')
    ];
}
?>

<div class="container">
    <h1 class="page-title">Donor Profile</h1>
    <p class="page-subtitle">Manage your donor information and view donation history</p>

    <?= $message ?>

    <!-- Profile Summary -->
    <div class="card">
        <h2 class="card__title">Profile Summary</h2>
        <div class="form-row">
            <div>
                <strong>Name:</strong> <?= htmlspecialchars($donor['name']) ?><br>
                <strong>Email:</strong> <?= htmlspecialchars($donor['email']) ?><br>
                <strong>Phone:</strong> <?= htmlspecialchars($donor['phone']) ?>
            </div>
            <div>
                <strong>Blood Type:</strong> 
                <span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', $donor['blood_type']))) ?>">
                    <?= htmlspecialchars($donor['blood_type']) ?>
                </span><br>
                <strong>District:</strong> <?= htmlspecialchars($donor['district']) ?><br>
                <strong>Member Since:</strong> <?= date('M Y', strtotime($donor['registration_date'])) ?>
            </div>
        </div>
    </div>

    <!-- Update Profile -->
    <div class="card">
        <h2 class="card__title">Update Profile Information</h2>
        <form method="POST" action="">
            <input type="hidden" name="update_profile" value="1">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" required 
                           value="<?= htmlspecialchars($donor['name']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" required 
                           value="<?= htmlspecialchars($donor['email']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" class="form-control" name="phone" id="phone" required 
                           value="<?= htmlspecialchars($donor['phone']) ?>" placeholder="+94 71 234 5678">
                </div>
                <div class="form-group">
                    <label class="form-label" for="blood_type">Blood Type</label>
                    <select class="form-control" name="blood_type" id="blood_type" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+" <?= $donor['blood_type'] === 'A+' ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= $donor['blood_type'] === 'A-' ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= $donor['blood_type'] === 'B+' ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= $donor['blood_type'] === 'B-' ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= $donor['blood_type'] === 'AB+' ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= $donor['blood_type'] === 'AB-' ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= $donor['blood_type'] === 'O+' ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= $donor['blood_type'] === 'O-' ? 'selected' : '' ?>>O-</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="district">District</label>
                <select class="form-control" name="district" id="district" required>
                    <option value="">Select District</option>
                    <option value="Colombo" <?= $donor['district'] === 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                    <option value="Kandy" <?= $donor['district'] === 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                    <option value="Galle" <?= $donor['district'] === 'Galle' ? 'selected' : '' ?>>Galle</option>
                    <option value="Jaffna" <?= $donor['district'] === 'Jaffna' ? 'selected' : '' ?>>Jaffna</option>
                    <option value="Batticaloa" <?= $donor['district'] === 'Batticaloa' ? 'selected' : '' ?>>Batticaloa</option>
                </select>
            </div>

            <button type="submit" class="btn btn--primary">Update Profile</button>
        </form>
    </div>

    <!-- Donation History -->
    <div class="card">
        <h2 class="card__title">Donation History</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Camp/Hospital</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jan 15, 2025</td>
                        <td>Colombo General Hospital</td>
                        <td>Emergency Donation</td>
                        <td><span class="blood-type blood-type-a-pos">Completed</span></td>
                    </tr>
                    <tr>
                        <td>Dec 10, 2024</td>
                        <td>Red Cross Center</td>
                        <td>Monthly Blood Drive</td>
                        <td><span class="blood-type blood-type-a-pos">Completed</span></td>
                    </tr>
                    <tr>
                        <td>Nov 05, 2024</td>
                        <td>Community Center</td>
                        <td>Local Blood Camp</td>
                        <td><span class="blood-type blood-type-a-pos">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-center mt-2">
            <small>Total donations: 3 | Last donation: 28 days ago</small>
        </p>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h2 class="card__title">Quick Actions</h2>
        <div class="form-row">
            <div class="text-center">
                <h3>📍 Find Camps</h3>
                <p>Locate upcoming blood donation camps near you</p>
                <a href="camps.php" class="btn btn--primary">Browse Camps</a>
            </div>
            <div class="text-center">
                <h3>🚨 Emergency</h3>
                <p>Respond to urgent blood requests in your area</p>
                <a href="emergency.php" class="btn btn--secondary">Emergency Requests</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
