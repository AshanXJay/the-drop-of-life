<?php
// manage-camps.php - Complete CRUD interface for camp management
include 'includes/header.php';
include 'includes/db_connection.php';

// Initialize variables
$message = '';
$messageType = '';
$editMode = false;
$editCamp = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $result = addCamp($_POST);
            break;
        case 'update':
            $result = updateCamp($_POST);
            break;
        case 'delete':
            $result = deleteCamp($_POST['camp_id']);
            break;
        case 'edit':
            $editMode = true;
            $editCamp = getCampById($_POST['camp_id']);
            break;
    }
    
    if (isset($result)) {
        $message = $result['message'];
        $messageType = $result['type'];
    }
}

// Function to add a new camp
function addCamp($data) {
    global $pdo;
    
    try {
        // Validate required fields
        $required = ['name', 'location', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['message' => 'Please fill in all required fields.', 'type' => 'error'];
            }
        }
        
        // Validate date logic
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            return ['message' => 'Start date cannot be later than end date.', 'type' => 'error'];
        }
        
        $sql = "INSERT INTO camps (name, location, start_date, end_date, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            htmlspecialchars($data['name']),
            htmlspecialchars($data['location']),
            $data['start_date'],
            $data['end_date'],
            htmlspecialchars($data['description'])
        ]);
        
        return ['message' => 'Camp added successfully!', 'type' => 'success'];
    } catch (PDOException $e) {
        return ['message' => 'Error adding camp: ' . $e->getMessage(), 'type' => 'error'];
    }
}

// Function to update a camp
function updateCamp($data) {
    global $pdo;
    
    try {
        // Validate required fields
        $required = ['camp_id', 'name', 'location', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['message' => 'Please fill in all required fields.', 'type' => 'error'];
            }
        }
        
        // Validate date logic
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            return ['message' => 'Start date cannot be later than end date.', 'type' => 'error'];
        }
        
        $sql = "UPDATE camps SET name = ?, location = ?, start_date = ?, end_date = ?, description = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            htmlspecialchars($data['name']),
            htmlspecialchars($data['location']),
            $data['start_date'],
            $data['end_date'],
            htmlspecialchars($data['description']),
            $data['camp_id']
        ]);
        
        return ['message' => 'Camp updated successfully!', 'type' => 'success'];
    } catch (PDOException $e) {
        return ['message' => 'Error updating camp: ' . $e->getMessage(), 'type' => 'error'];
    }
}

// Function to delete a camp
function deleteCamp($campId) {
    global $pdo;
    
    try {
        $sql = "DELETE FROM camps WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$campId]);
        
        if ($stmt->rowCount() > 0) {
            return ['message' => 'Camp deleted successfully!', 'type' => 'success'];
        } else {
            return ['message' => 'Camp not found.', 'type' => 'error'];
        }
    } catch (PDOException $e) {
        return ['message' => 'Error deleting camp: ' . $e->getMessage(), 'type' => 'error'];
    }
}

// Function to get camp by ID
function getCampById($campId) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM camps WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$campId]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

// Function to get all camps
function getAllCamps() {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM camps ORDER BY start_date ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

$camps = getAllCamps();
?>

<link rel="stylesheet" href="assets/css/manage-camps.css">

<div class="container">
    <div class="page-header">
        <h2><?php echo $editMode ? 'Edit Camp' : 'Manage Camps'; ?></h2>
        <p>Create, view, update, and delete blood donation camps</p>
    </div>

    <!-- Display Messages -->
    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>" id="message">
            <?php echo htmlspecialchars($message); ?>
            <span class="close-message" onclick="closeMessage()">&times;</span>
        </div>
    <?php endif; ?>

    <!-- Camp Form -->
    <div class="form-section">
        <h3><?php echo $editMode ? 'Edit Camp Details' : 'Add New Camp'; ?></h3>
        <form id="campForm" method="POST" action="" novalidate>
            <?php if ($editMode && $editCamp): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="camp_id" value="<?php echo $editCamp['id']; ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="add">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Camp Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    maxlength="255"
                    value="<?php echo $editMode && $editCamp ? htmlspecialchars($editCamp['name']) : ''; ?>"
                    placeholder="Enter camp name"
                >
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input 
                    type="text" 
                    id="location" 
                    name="location" 
                    required 
                    maxlength="255"
                    value="<?php echo $editMode && $editCamp ? htmlspecialchars($editCamp['location']) : ''; ?>"
                    placeholder="Enter camp location"
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input 
                        type="date" 
                        id="start_date" 
                        name="start_date" 
                        required
                        value="<?php echo $editMode && $editCamp ? $editCamp['start_date'] : ''; ?>"
                        min="<?php echo date('Y-m-d'); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input 
                        type="date" 
                        id="end_date" 
                        name="end_date" 
                        required
                        value="<?php echo $editMode && $editCamp ? $editCamp['end_date'] : ''; ?>"
                        min="<?php echo date('Y-m-d'); ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4" 
                    maxlength="1000"
                    placeholder="Enter camp description (optional)"
                ><?php echo $editMode && $editCamp ? htmlspecialchars($editCamp['description']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <?php if ($editMode): ?>
                    <button type="submit" class="btn btn-primary">Update Camp</button>
                    <a href="manage-camps.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">Add Camp</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Camps List -->
    <div class="list-section">
        <h3>Existing Camps</h3>
        
        <?php if (empty($camps)): ?>
            <div class="no-data">
                <p>No camps found. Add your first camp using the form above.</p>
            </div>
        <?php else: ?>
            <div class="camps-grid">
                <?php foreach ($camps as $camp): ?>
                    <div class="camp-card">
                        <div class="camp-header">
                            <h4><?php echo htmlspecialchars($camp['name']); ?></h4>
                            <div class="camp-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="camp_id" value="<?php echo $camp['id']; ?>">
                                    <button type="submit" class="btn btn-edit" title="Edit Camp">
                                        ✏️ Edit
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirmDelete('<?php echo htmlspecialchars($camp['name']); ?>')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="camp_id" value="<?php echo $camp['id']; ?>">
                                    <button type="submit" class="btn btn-delete" title="Delete Camp">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="camp-details">
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($camp['location']); ?></p>
                            <p><strong>Duration:</strong> 
                                <?php 
                                    $startDate = date('M j, Y', strtotime($camp['start_date']));
                                    $endDate = date('M j, Y', strtotime($camp['end_date']));
                                    echo $startDate;
                                    if ($startDate !== $endDate) {
                                        echo ' - ' . $endDate;
                                    }
                                ?>
                            </p>
                            <?php if ($camp['description']): ?>
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($camp['description']); ?></p>
                            <?php endif; ?>
                            <p class="camp-meta">
                                <small>Created: <?php echo date('M j, Y', strtotime($camp['created_at'])); ?></small>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="assets/js/manage-camps.js"></script>

<?php include 'includes/footer.php'; ?>
