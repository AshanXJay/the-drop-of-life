<?php
// filter.php

require_once 'db.php'; // Make sure the path is correct

// Get filter values
$district = $_GET['district'] ?? '';
$filter_date = $_GET['date'] ?? '';
$organizer = $_GET['organizer'] ?? '';

// SQL with placeholders
$sql = "SELECT * FROM camps 
        WHERE date >= CURDATE()
        AND (district = ? OR ? = '')
        AND (date = ? OR ? = '')
        AND (organizer = ? OR ? = '')
        ORDER BY date ASC, time ASC";

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$district, $district, $filter_date, $filter_date, $organizer, $organizer]);
    $result = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error fetching camps: " . $e->getMessage());
    $result = [];
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Blood Donation Camps</title>
    <link rel="stylesheet" href="your_styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        
        min-height: 100vh;
        padding: 20px;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .header-section {
        background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .header-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .header-section p {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .content-section {
        padding: 40px 30px;
    }

    .filter-form {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 40px;
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        overflow: hidden;
    }

    .filter-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #c0392b, #e74c3c);
    }

    .filter-title {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .filter-title i {
        color: #c0392b;
        font-size: 1.2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group label i {
        color: #c0392b;
        width: 16px;
    }

    .form-group input {
        padding: 12px 15px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .form-group input:focus {
        outline: none;
        border-color: #c0392b;
        box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
        transform: translateY(-2px);
    }

    .search-btn {
        padding: 12px 30px;
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(192, 57, 43, 0.3);
    }

    .search-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(192, 57, 43, 0.4);
    }

    .camps-list {
        display: grid;
        gap: 25px;
    }

    .camp-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid #e1e5e9;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .camp-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #c0392b, #e74c3c);
    }

    .camp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border-color: #c0392b;
    }

    .camp-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .camp-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(192, 57, 43, 0.3);
    }

    .camp-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .camp-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
    }

    .detail-item i {
        color: #c0392b;
        width: 20px;
        font-size: 1rem;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .detail-value {
        color: #333;
        font-weight: 500;
    }

    .no-camps {
        text-align: center;
        padding: 60px 20px;
        color: #666;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .no-camps i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .no-camps h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #333;
    }

    .no-camps p {
        color: #888;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .header-section h1 {
            font-size: 2rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .camp-details {
            grid-template-columns: 1fr;
        }

        .camp-header {
            flex-direction: column;
            text-align: center;
        }
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .loading i {
        font-size: 2rem;
        color: #c0392b;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h1><i class="fas fa-heartbeat"></i> Upcoming Blood Donation Camps</h1>
        <p>Find and join life-saving blood donation camps in your area</p>
    </div>

    <div class="content-section">
        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <div class="filter-title">
                <i class="fas fa-search"></i>
                Search Camps
            </div>
            
            <div class="filter-grid">
                <div class="form-group">
                    <label for="district">
                        <i class="fas fa-map-marker-alt"></i>
                        District
                    </label>
                    <input type="text" id="district" name="district" value="<?= htmlspecialchars($district) ?>" placeholder="Enter district name">
                </div>

                <div class="form-group">
                    <label for="date">
                        <i class="fas fa-calendar-alt"></i>
                        Date
                    </label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($filter_date) ?>">
                </div>

                <div class="form-group">
                    <label for="organizer">
                        <i class="fas fa-building"></i>
                        Organizer
                    </label>
                    <input type="text" id="organizer" name="organizer" value="<?= htmlspecialchars($organizer) ?>" placeholder="Enter organizer name">
                </div>

                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    Search Camps
                </button>
            </div>
        </form>

        <!-- Camp Listings -->
        <div class="camps-list">
        <?php
        if (count($result) > 0) {
            foreach ($result as $camp) {
                ?>
                <div class="camp-card">
                    <div class="camp-header">
                        <div class="camp-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h2 class="camp-title"><?= htmlspecialchars($camp['name']) ?></h2>
                    </div>
                    
                    <div class="camp-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <div class="detail-label">Date:</div>
                                <div class="detail-value"><?= htmlspecialchars($camp['date']) ?></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <div class="detail-label">Time:</div>
                                <div class="detail-value"><?= htmlspecialchars($camp['time']) ?></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <div class="detail-label">Location:</div>
                                <div class="detail-value"><?= htmlspecialchars($camp['location']) ?></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-globe"></i>
                            <div>
                                <div class="detail-label">District:</div>
                                <div class="detail-value"><?= htmlspecialchars($camp['district']) ?></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-building"></i>
                            <div>
                                <div class="detail-label">Organizer:</div>
                                <div class="detail-value"><?= htmlspecialchars($camp['organizer']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="no-camps">
                <i class="fas fa-search"></i>
                <h3>No Camps Found</h3>
                <p>Try adjusting your search criteria or check back later for new camps.</p>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div>

</body>
</html>
<?php include 'includes/footer.php'; ?>