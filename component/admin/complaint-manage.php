<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: complaint-list.php");
    exit();
}

$complaint_id = (int)$_GET['id'];

// Get complaint details
$query = "
    SELECT c.*, 
           r.full_name as resident_name, 
           r.email as resident_email,
           r.apartment_no,
           cat.name as category_name,
           s.status_name,
           s.priority,
           s.assigned_to,
           s.noted as notes,
           s.updated_date
    FROM complaint c
    LEFT JOIN resident r ON c.residentID = r.residentID
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
    WHERE c.complaintID = $complaint_id
";

$result = mysqli_query($conn, $query);
$complaint = mysqli_fetch_assoc($result);

if (!$complaint) {
    header("Location: complaint-list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaint</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <a href="complaint-list.php" class="back-link">← Back to Complaints</a>
        
        <div class="manage-header">
            <h1 class="page-title" style="margin-bottom:0;"><?php echo e($complaint['name']); ?></h1>
            <div class="header-badges">
                <span class="badge <?php echo getStatusBadgeClass($complaint['status_name']); ?>">
                    <?php echo e($complaint['status_name']); ?>
                </span>
                <span class="badge <?php echo getPriorityBadgeClass($complaint['priority']); ?>">
                    <?php echo e($complaint['priority']); ?>
                </span>
            </div>
        </div>

        <div class="manage-grid">
            <div class="details-column">
                <div class="manage-card">
                    <h3 class="card-title">Complaint Details</h3>
                    
                    <div class="meta-grid">
                        <div class="meta-item">
                            <label>Complaint ID</label>
                            <p>C<?php echo str_pad($complaint['complaintID'], 3, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div class="meta-item">
                            <label>Category</label>
                            <p><?php echo e($complaint['category_name']); ?></p>
                        </div>
                        <div class="meta-item">
                            <label>Resident</label>
                            <p><?php echo e($complaint['resident_name']); ?></p>
                        </div>
                        <div class="meta-item">
                            <label>Apartment</label>
                            <p><?php echo e($complaint['apartment_no']); ?></p>
                        </div>
                        <div class="meta-item">
                            <label>Created</label>
                            <p><?php echo date('M d, Y H:i', strtotime($complaint['created_date'])); ?></p>
                        </div>
                        <div class="meta-item">
                            <label>Last Updated</label>
                            <p><?php echo date('M d, Y H:i', strtotime($complaint['updated_date'])); ?></p>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4>Description</h4>
                        <p><?php echo nl2br(e($complaint['description'])); ?></p>
                    </div>

                    <?php if (!empty($complaint['notes'])): ?>
                        <div class="detail-section">
                            <h4>Notes & Updates</h4>
                            <div class="notes-container">
                                <?php 
                                $notes = explode("\n", $complaint['notes']);
                                foreach ($notes as $note):
                                    if (trim($note) === '') continue;
                                ?>
                                    <div class="note-box">
                                        <p class="note-text"><?php echo nl2br(e($note)); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="update-column">
                <div class="manage-card">
                    <h3 class="card-title">Update Complaint</h3>
                    
                    <form action="../../utils/controller.php" method="POST">
                        <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaintID']; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="Pending" <?php echo $complaint['status_name'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="In Progress" <?php echo $complaint['status_name'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Resolved" <?php echo $complaint['status_name'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <select class="form-control" name="priority">
                                <option value="Low" <?php echo $complaint['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                                <option value="Medium" <?php echo $complaint['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="High" <?php echo $complaint['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Assigned To</label>
                            <input class="form-control" type="text" name="assigned_to" value="<?php echo e($complaint['assigned_to']); ?>" placeholder="Enter handler name">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Add Note</label>
                            <textarea class="form-control" name="update_note" rows="3" placeholder="Type an update note here..."></textarea>
                        </div>

                        <button type="submit" name="update_complaint" class="btn-primary" style="width:100%;">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>