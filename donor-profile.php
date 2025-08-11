<?php
// donor-profile.php
include 'includes/header.php';

// ---------------- Database Connection ----------------
session_start();
$servername = "localhost";
$username   = "root";          // adjust as needed
$password   = "";
$dbname     = "the_drop_of_life";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ------------------------------------------------------
// Determine donor ID (demo defaults to 1). In production, use login/session.
if (!isset($_SESSION['donor_id'])) {
    $_SESSION['donor_id'] = 1; // demo donor
}
$donor_id = (int) $_SESSION['donor_id'];

// ---------------- Profile Picture Upload -------------
$profilePic = 'assets/img/default-profile.png';
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $targetFile = $targetDir . basename($_FILES["profile_pic"]["name"]);
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
        $profilePic = $targetFile;
        // OPTIONAL: save $profilePic to DB if you add a column for it
    }
}

// ---------------- Handle Update ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $stmt = $pdo->prepare("UPDATE donors SET name=:name, email=:email, phone=:phone, blood_type=:blood, district=:district WHERE donor_id=:id");
    $stmt->execute([
        ':name'     => $_POST['name'],
        ':email'    => $_POST['email'],
        ':phone'    => $_POST['phone'],
        ':blood'    => $_POST['blood'],
        ':district' => $_POST['address'],
        ':id'       => $donor_id
    ]);
}

// ---------------- Fetch Donor Details ----------------
$name = $email = $blood = $phone = $address = '';
$stmt = $pdo->prepare("SELECT * FROM donors WHERE donor_id = :id LIMIT 1");
$stmt->execute([':id' => $donor_id]);
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $name    = $row['name'];
    $email   = $row['email'];
    $blood   = $row['blood_type'];
    $phone   = $row['phone'];
    $address = $row['district'];
}

?>

<style>
    .profile-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 30px 40px;
        text-align: center;
    }

    .profile-pic {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #e74c3c;
        margin-bottom: 15px;
    }

    .profile-form input[type="text"],
    .profile-form input[type="email"] {
        width: 100%;
        padding: 10px 12px;
        margin: 8px 0 16px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .profile-form label {
        float: left;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .profile-form .profile-btn {
        background: #e74c3c;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .profile-form .profile-btn:hover {
        background: #c0392b;
    }

    .profile-form .upload-label {
        color: #e74c3c;
        cursor: pointer;
        font-weight: 500;
        margin-bottom: 10px;
        display: inline-block;
    }
</style>

<div class="profile-container">
    <h2>Donor Profile</h2>
    <form class="profile-form" method="post" enctype="multipart/form-data">
        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic"><br>
        <label class="upload-label">
            Change Photo
            <input type="file" name="profile_pic" accept="image/*" style="display:none" onchange="this.form.submit()">
        </label>
        <br><br>
        <label>Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <label>Blood Group</label>
        <input type="text" name="blood" value="<?php echo htmlspecialchars($blood); ?>" required>
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        <label>Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
        <br>
        <button type="submit" class="profile-btn">Save Changes</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>