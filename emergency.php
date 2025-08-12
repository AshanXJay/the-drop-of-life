<?php 
require_once 'includes/db.php';
include 'includes/header.php';

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $_POST['blood_type'] ?? '';
    $location = $_POST['location'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $note = $_POST['note'] ?? '';
    $urgency = $_POST['urgency'] ?? 'medium';
    
    if (empty($blood_type) || empty($location) || empty($contact)) {
        $message = '<div class="alert alert--danger">Blood type, location, and contact are required.</div>';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "INSERT INTO emergency_requests (blood_type, location, contact, note, urgency, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);
            if ($stmt->execute([$blood_type, $location, $contact, $note, $urgency])) {
                $message = '<div class="alert alert--success">Emergency request submitted successfully! We will contact you soon.</div>';
            } else {
                $message = '<div class="alert alert--danger">Request submission failed. Please try again.</div>';
            }
        } catch (Exception $e) {
            $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch recent emergency requests
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM emergency_requests ORDER BY created_at DESC LIMIT 10");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $requests = [];
}
?>

<div class="container">
    <h1 class="page-title">Emergency Blood Request</h1>
    <p class="page-subtitle">Submit urgent blood requests for immediate assistance</p>

    <?= $message ?>

    <!-- Emergency Request Form -->
    <div class="card">
        <h2 class="card__title">🚨 Submit Emergency Request</h2>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="blood_type">Required Blood Type *</label>
                    <select class="form-control" name="blood_type" id="blood_type" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="urgency">Urgency Level</label>
                    <select class="form-control" name="urgency" id="urgency">
                        <option value="low">Low - Within 24 hours</option>
                        <option value="medium" selected>Medium - Within 12 hours</option>
                        <option value="high">High - Within 6 hours</option>
                        <option value="critical">Critical - Immediate</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="location">Hospital/Location *</label>
                    <input type="text" class="form-control" name="location" id="location" required 
                           placeholder="Hospital name and address">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact">Contact Number *</label>
                    <input type="tel" class="form-control" name="contact" id="contact" required 
                           placeholder="+94 71 234 5678">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Additional Information</label>
                <textarea class="form-control" name="note" id="note" rows="3" 
                          placeholder="Patient details, specific requirements, or other important information..."></textarea>
            </div>

            <button type="submit" class="btn btn--primary">Submit Emergency Request</button>
        </form>
    </div>

    <!-- Instructions -->
    <div class="card">
        <h2 class="card__title">Emergency Request Guidelines</h2>
        <div class="form-row">
            <div>
                <h3>📞 Immediate Action</h3>
                <ul>
                    <li>For life-threatening emergencies, call 1990</li>
                    <li>Contact hospital blood bank directly</li>
                    <li>Use this form for additional donor support</li>
                    <li>Provide accurate contact information</li>
                </ul>
            </div>
            <div>
                <h3>🩸 Blood Compatibility</h3>
                <ul>
                    <li><span class="blood-type blood-type-o-neg">O-</span> Can receive from O- only</li>
                    <li><span class="blood-type blood-type-o-pos">O+</span> Can receive from O-, O+</li>
                    <li><span class="blood-type blood-type-ab-pos">AB+</span> Universal recipient</li>
                    <li>Check with medical staff for exact requirements</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <?php if (!empty($requests)): ?>
    <div class="card">
        <h2 class="card__title">Recent Emergency Requests</h2>
        <p class="mb-3">Help others by responding to these urgent requests if you're a compatible donor:</p>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Blood Type</th>
                        <th>Location</th>
                        <th>Urgency</th>
                        <th>Contact</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td>
                                <span class="blood-type blood-type-<?= strtolower(str_replace('+', '-pos', str_replace('-', '-neg', $request['blood_type']))) ?>">
                                    <?= htmlspecialchars($request['blood_type']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($request['location']) ?></td>
                            <td>
                                <?php
                                $urgencyClass = [
                                    'low' => 'blood-type-a-pos',
                                    'medium' => 'blood-type-ab-pos', 
                                    'high' => 'blood-type-o-pos',
                                    'critical' => 'blood-type-o-neg'
                                ];
                                ?>
                                <span class="blood-type <?= $urgencyClass[$request['urgency']] ?? 'blood-type-a-pos' ?>">
                                    <?= ucfirst($request['urgency']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($request['contact']) ?></td>
                            <td><?= date('M j, g:i A', strtotime($request['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Emergency Contacts -->
    <div class="card">
        <h2 class="card__title">Emergency Contacts</h2>
        <div class="form-row">
            <div class="text-center">
                <h3>🏥 National Emergency</h3>
                <p><strong>1990</strong><br>24/7 Emergency Hotline</p>
            </div>
            <div class="text-center">
                <h3>🩸 Blood Bank Hotline</h3>
                <p><strong>+94 11 269 1111</strong><br>National Blood Transfusion Service</p>
            </div>
        </div>
        <div class="form-row">
            <div class="text-center">
                <h3>📱 WhatsApp Support</h3>
                <p><strong>+94 77 123 4567</strong><br>Quick donor coordination</p>
            </div>
            <div class="text-center">
                <h3>📧 Email Support</h3>
                <p><strong>emergency@thedropoflife.lk</strong><br>24-hour response</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
