<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];

if (!isset($_GET['id'])) {
    die("Complaint not found.");
}

$complaint_id = (int)$_GET['id'];

$query = "
    SELECT
        c.*,
        cat.name AS category_name,
        s.status_name,
        s.priority,
        s.assigned_to,
        s.noted as notes,
        s.updated_date
    FROM complaint c
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
    WHERE c.complaintID = $complaint_id AND c.residentID = $residentID
";

$result = mysqli_query($conn, $query);
$complaint = mysqli_fetch_assoc($result);

if (!$complaint) {
    die("Complaint not found or you don't have permission to view it.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <a href="complaint-history.php" class="back-link">← Back to My Complaints</a>
        
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

        <div class="details-card">
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
                    <label>Assigned To</label>
                    <p><?php echo e($complaint['assigned_to'] ?? 'Unassigned'); ?></p>
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
                    <h4>Staff Notes</h4>
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
</div>

</body>
</html>