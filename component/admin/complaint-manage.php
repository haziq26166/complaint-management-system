<?php
require_once '../../utils/controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaint - <?php echo htmlspecialchars($complaint['complaint_title']); ?></title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

    <div class="admin-main-layout">
        
        <?php include_once '../navbar-admin.php'; ?>

        <div class="admin-wrapper">
            
            <a href="complaint-list.php" class="back-link">← Back to Complaints</a>
            
            <div class="manage-header">
                <h1 class="page-title" style="margin-bottom: 0;"><?php echo htmlspecialchars($complaint['complaint_title']); ?></h1>
                <div class="header-badges">
                    <span class="badge status-<?php echo str_replace(' ', '', strtolower($complaint['status_name'])); ?>">
                        <?php echo htmlspecialchars($complaint['status_name']); ?>
                    </span>
                    <span class="badge priority-<?php echo strtolower($complaint['priority']); ?>">
                        <?php echo htmlspecialchars($complaint['priority']); ?>
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
                                <p><?php echo htmlspecialchars($complaint['category_name']); ?></p>
                            </div>
                            <div class="meta-item">
                                <label>Resident</label>
                                <p><?php echo htmlspecialchars($complaint['resident_name']); ?></p>
                            </div>
                            <div class="meta-item">
                                <label>Apartment</label>
                                <p><?php echo htmlspecialchars($complaint['apartment_no']); ?></p>
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
                            <p><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                        </div>

                        <div class="detail-section" style="margin-bottom: 0;">
                            <h4>Updates & Notes</h4>
                            <div class="notes-container">
                                <?php foreach ($notes as $note): ?>
                                    <div class="note-box">
                                        <div class="note-header">
                                            <span class="note-author"><?php echo htmlspecialchars($note['author']); ?></span>
                                            <span class="note-date"><?php echo htmlspecialchars($note['date']); ?></span>
                                        </div>
                                        <p class="note-text"><?php echo nl2br(htmlspecialchars($note['text'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="update-column">
                    <div class="manage-card">
                        <h3 class="card-title">Update Complaint</h3>
                        
                        <form method="POST" action="../../utils/controller.php">
                            <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaintID']; ?>">
                            
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="Pending" <?php echo $complaint['status_name'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="In Progress" <?php echo $complaint['status_name'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="Resolved" <?php echo $complaint['status_name'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Priority</label>
                                <select name="priority">
                                    <option value="Low" <?php echo $complaint['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="Medium" <?php echo $complaint['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="High" <?php echo $complaint['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Assign To</label>
                                <input type="text" name="assigned_to" value="<?php echo htmlspecialchars($complaint['assigned_to']); ?>" placeholder="Enter handler name">
                            </div>

                            <div class="form-group">
                                <label>Add Note</label>
                                <textarea name="update_note" placeholder="Type an update note here..."></textarea>
                            </div>

                            <button type="submit" name="update_complaint" class="submit-btn">Save Changes</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>