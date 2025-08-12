<?php 
require_once 'includes/db.php';
include 'includes/header.php';

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $blood_type = $_POST['blood_type'] ?? '';
    $district = $_POST['district'] ?? '';
    $age = $_POST['age'] ?? '';
    
    if (empty($name) || empty($email) || empty($phone) || empty($blood_type) || empty($district) || empty($age)) {
        $message = '<div class="alert alert--danger">All fields are required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert--danger">Please enter a valid email address.</div>';
    } elseif ($age < 18 || $age > 65) {
        $message = '<div class="alert alert--danger">Age must be between 18 and 65 years.</div>';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "INSERT INTO donors (name, email, phone, blood_type, district, age, registration_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);
            if ($stmt->execute([$name, $email, $phone, $blood_type, $district, $age])) {
                $message = '<div class="alert alert--success">Registration successful! Welcome to our donor community.</div>';
            } else {
                $message = '<div class="alert alert--danger">Registration failed. Please try again.</div>';
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = '<div class="alert alert--warning">This email is already registered. Please use a different email.</div>';
            } else {
                $message = '<div class="alert alert--danger">Error: ' . $e->getMessage() . '</div>';
            }
        }
    }
}
?>

<div class="container">
    <h1 class="page-title">Become A Blood Donor</h1>
    <p class="page-subtitle">Join our community of heroes and help save lives</p>

    <?= $message ?>

    <!-- Registration Form -->
    <div class="card">
        <h2 class="card__title">Donor Registration Form</h2>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name *</label>
                    <input type="text" class="form-control" name="name" id="name" required 
                           placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address *</label>
                    <input type="email" class="form-control" name="email" id="email" required 
                           placeholder="your.email@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number *</label>
                    <input type="tel" class="form-control" name="phone" id="phone" required 
                           placeholder="+94 71 234 5678">
                </div>
                <div class="form-group">
                    <label class="form-label" for="age">Age *</label>
                    <input type="number" class="form-control" name="age" id="age" required 
                           min="18" max="65" placeholder="25">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="blood_type">Blood Type *</label>
                    <select class="form-control" name="blood_type" id="blood_type" required>
                        <option value="">Select Your Blood Type</option>
                        <option value="A+">A+ (A Positive)</option>
                        <option value="A-">A- (A Negative)</option>
                        <option value="B+">B+ (B Positive)</option>
                        <option value="B-">B- (B Negative)</option>
                        <option value="AB+">AB+ (AB Positive)</option>
                        <option value="AB-">AB- (AB Negative)</option>
                        <option value="O+">O+ (O Positive)</option>
                        <option value="O-">O- (O Negative)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="district">District *</label>
                    <select class="form-control" name="district" id="district" required>
                        <option value="">Select Your District</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Galle">Galle</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Batticaloa">Batticaloa</option>
                        <option value="Anuradhapura">Anuradhapura</option>
                        <option value="Polonnaruwa">Polonnaruwa</option>
                        <option value="Kurunegala">Kurunegala</option>
                        <option value="Puttalam">Puttalam</option>
                        <option value="Ratnapura">Ratnapura</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn--primary btn--lg">Register as Donor</button>
        </form>
    </div>

    <!-- Eligibility Information -->
    <div class="card">
        <h2 class="card__title">Donor Eligibility Requirements</h2>
        <div class="form-row">
            <div>
                <h3>✅ You CAN donate if you:</h3>
                <ul>
                    <li>Are between 18-65 years old</li>
                    <li>Weigh at least 50kg (110lbs)</li>
                    <li>Are in good general health</li>
                    <li>Have not donated blood in the last 3 months</li>
                    <li>Have had adequate sleep (6+ hours)</li>
                    <li>Have eaten within the last 4 hours</li>
                </ul>
            </div>
            <div>
                <h3>❌ You CANNOT donate if you:</h3>
                <ul>
                    <li>Have a cold, flu, or fever</li>
                    <li>Are pregnant or breastfeeding</li>
                    <li>Have taken antibiotics in the last 24 hours</li>
                    <li>Have traveled to malaria areas recently</li>
                    <li>Have certain medical conditions</li>
                    <li>Have had a tattoo/piercing in last 6 months</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Benefits -->
    <div class="card">
        <h2 class="card__title">Benefits of Being a Donor</h2>
        <div class="form-row">
            <div class="text-center">
                <h3>❤️ Save Lives</h3>
                <p>One donation can save up to 3 lives</p>
            </div>
            <div class="text-center">
                <h3>🏥 Free Health Check</h3>
                <p>Get basic health screening before every donation</p>
            </div>
        </div>
        <div class="form-row">
            <div class="text-center">
                <h3>🎯 Stay Healthy</h3>
                <p>Regular donation may reduce risk of heart disease</p>
            </div>
            <div class="text-center">
                <h3>🤝 Community Impact</h3>
                <p>Be part of a life-saving community network</p>
            </div>
        </div>
    </div>

    <!-- Blood Type Information -->
    <div class="card">
        <h2 class="card__title">Know Your Impact</h2>
        <p class="mb-3">Different blood types help different people:</p>
        <div class="form-row">
            <div>
                <span class="blood-type blood-type-o-neg">O-</span> <strong>Universal Donor</strong><br>
                <small>Can donate red blood cells to anyone</small>
            </div>
            <div>
                <span class="blood-type blood-type-ab-pos">AB+</span> <strong>Universal Plasma Donor</strong><br>
                <small>Can donate plasma to anyone</small>
            </div>
        </div>
        <div class="form-row">
            <div>
                <span class="blood-type blood-type-o-pos">O+</span> <strong>Most Needed</strong><br>
                <small>Most common blood type needed</small>
            </div>
            <div>
                <span class="blood-type blood-type-a-neg">A-</span> <strong>Rare & Valuable</strong><br>
                <small>Needed for cancer patients</small>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="card text-center">
        <h2 class="card__title">What Happens Next?</h2>
        <div class="form-row">
            <div>
                <h3>1️⃣ Registration</h3>
                <p>Complete the form above to register</p>
            </div>
            <div>
                <h3>2️⃣ Find Camps</h3>
                <p>Browse available donation camps</p>
            </div>
        </div>
        <div class="form-row">
            <div>
                <h3>3️⃣ Health Check</h3>
                <p>Get screened at the donation site</p>
            </div>
            <div>
                <h3>4️⃣ Donate</h3>
                <p>Make your life-saving donation</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="camps.php" class="btn btn--secondary btn--lg">Find Donation Camps</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
