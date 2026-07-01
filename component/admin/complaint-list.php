<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

// Get filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'All';
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'All';
$priority_filter = isset($_GET['priority']) ? $_GET['priority'] : 'All';

// Build WHERE clause
$where = [];
if ($status_filter !== 'All') {
    $where[] = "s.status_name = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}
if ($category_filter !== 'All') {
    $where[] = "c.categoryID = " . (int)$category_filter;
}
if ($priority_filter !== 'All') {
    $where[] = "s.priority = '" . mysqli_real_escape_string($conn, $priority_filter) . "'";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Get complaints
$complaints_query = mysqli_query($conn, "
    SELECT c.*, 
           r.full_name as resident_name, 
           r.apartment_no,
           cat.name as category_name,
           s.status_name,
           s.priority,
           s.assigned_to
    FROM complaint c
    LEFT JOIN resident r ON c.residentID = r.residentID
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
    $where_sql
    ORDER BY c.created_date DESC
");

// Get categories for filter
$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Complaints</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <h1 class="page-title">All Complaints</h1>

        <form method="GET" action="" class="filter-form">
            <div class="filter-group">
                <div class="filter-box">
                    <label>Status</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="All" <?php echo $status_filter === 'All' ? 'selected' : ''; ?>>All</option>
                        <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $status_filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Resolved" <?php echo $status_filter === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                    </select>
                </div>

                <div class="filter-box">
                    <label>Category</label>
                    <select name="category" onchange="this.form.submit()">
                        <option value="All" <?php echo $category_filter === 'All' ? 'selected' : ''; ?>>All</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo $cat['categoryID']; ?>" <?php echo $category_filter == $cat['categoryID'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="filter-box">
                    <label>Priority</label>
                    <select name="priority" onchange="this.form.submit()">
                        <option value="All" <?php echo $priority_filter === 'All' ? 'selected' : ''; ?>>All</option>
                        <option value="High" <?php echo $priority_filter === 'High' ? 'selected' : ''; ?>>High</option>
                        <option value="Medium" <?php echo $priority_filter === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="Low" <?php echo $priority_filter === 'Low' ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Resident</th>
                        <th>Apartment</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($complaints_query) === 0): ?>
                        <tr>
                            <td colspan="10" style="text-align:center; color:#a0aec0; padding:40px;">
                                No complaints found matching your filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($complaints_query)): ?>
                            <tr>
                                <td><strong>C<?php echo str_pad($row['complaintID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><strong><?php echo e($row['name']); ?></strong></td>
                                <td><?php echo e($row['resident_name']); ?></td>
                                <td><?php echo e($row['apartment_no']); ?></td>
                                <td><?php echo e($row['category_name']); ?></td>
                                <td>
                                    <span class="badge <?php echo getPriorityBadgeClass($row['priority']); ?>">
                                        <?php echo e($row['priority'] ?? 'Low'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($row['status_name']); ?>">
                                        <?php echo e($row['status_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo e($row['assigned_to'] ?? 'Unassigned'); ?></td>
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