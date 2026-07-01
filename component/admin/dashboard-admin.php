<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

global $conn;

// Get statistics
$total_residents_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM resident");
$total_residents = $total_residents_result ? mysqli_fetch_assoc($total_residents_result)['count'] : 0;

$total_complaints_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM complaint");
$total_complaints = $total_complaints_result ? mysqli_fetch_assoc($total_complaints_result)['count'] : 0;

$total_categories_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM category");
$total_categories = $total_categories_result ? mysqli_fetch_assoc($total_categories_result)['count'] : 0;

$pending_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM status_complaint WHERE status_name='Pending'");
$pending = $pending_result ? mysqli_fetch_assoc($pending_result)['count'] : 0;

$in_progress_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM status_complaint WHERE status_name='In Progress'");
$in_progress = $in_progress_result ? mysqli_fetch_assoc($in_progress_result)['count'] : 0;

$resolved_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM status_complaint WHERE status_name='Resolved'");
$resolved = $resolved_result ? mysqli_fetch_assoc($resolved_result)['count'] : 0;

// Get recent complaints
$recent = mysqli_query($conn, "
    SELECT c.complaintID, c.name as title, c.created_date, 
           r.full_name as resident, r.apartment_no,
           cat.name as category, s.status_name
    FROM complaint c
    LEFT JOIN resident r ON c.residentID = r.residentID
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
    ORDER BY c.created_date DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <h1 class="page-title">Admin Dashboard</h1>

        <div class="dashboard-grid">
            <div class="stat-card">
                <span class="stat-label">Total Residents</span>
                <div class="stat-number"><?php echo $total_residents; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #4299e1;">
                <span class="stat-label">Total Complaints</span>
                <div class="stat-number" style="color:#4299e1;"><?php echo $total_complaints; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #ed8936;">
                <span class="stat-label">Pending</span>
                <div class="stat-number" style="color:#ed8936;"><?php echo $pending; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #4299e1;">
                <span class="stat-label">In Progress</span>
                <div class="stat-number" style="color:#4299e1;"><?php echo $in_progress; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #48bb78;">
                <span class="stat-label">Resolved</span>
                <div class="stat-number" style="color:#48bb78;"><?php echo $resolved; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #9f7aea;">
                <span class="stat-label">Categories</span>
                <div class="stat-number" style="color:#9f7aea;"><?php echo $total_categories; ?></div>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin:20px 0 30px; flex-wrap:wrap;">
            <a href="complaint-list.php" class="btn-primary">View All Complaints</a>
            <a href="resident-list.php" class="btn-secondary">Manage Residents</a>
            <a href="categories-list.php" class="btn-secondary">Manage Categories</a>
        </div>

        <h2 style="color:#2d3748; margin-bottom:15px;">Recent Complaints</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Resident</th>
                        <th>Apartment</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$recent || mysqli_num_rows($recent) === 0): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; color:#a0aec0; padding:40px;">No complaints yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                            <tr>
                                <td><strong>C<?php echo str_pad($row['complaintID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo e($row['title']); ?></td>
                                <td><?php echo e($row['resident']); ?></td>
                                <td><?php echo e($row['apartment_no']); ?></td>
                                <td><?php echo e($row['category']); ?></td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($row['status_name']); ?>">
                                        <?php echo e($row['status_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['created_date'])); ?></td>
                                <td>
                                    <a href="complaint-manage.php?id=<?php echo $row['complaintID']; ?>" class="btn-sm">Manage</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>