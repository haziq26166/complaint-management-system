<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];

$query = "
    SELECT
        c.complaintID,
        c.name as complaint_title,
        c.description,
        c.created_date,
        cat.name AS category,
        s.status_name,
        s.priority,
        s.assigned_to,
        s.updated_date
    FROM complaint c
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
    WHERE c.residentID = $residentID
    ORDER BY c.created_date DESC
";

$result = mysqli_query($conn, $query);
$complaints = [];
while ($row = mysqli_fetch_assoc($result)) {
    $complaints[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint History</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <a href="dashboard-resident.php" class="back-link">← Back to Dashboard</a>
        <h1 class="page-title">My Complaint History</h1>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($complaints)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; color:#a0aec0; padding:40px;">
                                You haven't submitted any complaints yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($complaints as $row): ?>
                            <tr>
                                <td><strong>C<?php echo str_pad($row['complaintID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo e($row['complaint_title']); ?></td>
                                <td><?php echo e($row['category']); ?></td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($row['status_name']); ?>">
                                        <?php echo e($row['status_name'] ?? 'Pending'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo getPriorityBadgeClass($row['priority']); ?>">
                                        <?php echo e($row['priority'] ?? 'Medium'); ?>
                                    </span>
                                </td>
                                <td><?php echo e($row['assigned_to'] ?? 'Unassigned'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_date'])); ?></td>
                                <td>
                                    <a href="complaint-details.php?id=<?php echo $row['complaintID']; ?>" class="btn-sm">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>