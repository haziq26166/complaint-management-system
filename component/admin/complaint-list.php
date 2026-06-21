<?php
require_once '../../utils/controller.php';
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

            <form method="GET" action="complaint-list.php" id="filterForm">
                <div class="filter-group">
                    <div class="filter-box">
                        <label>Status</label>
                        <select name="status" onchange="document.getElementById('filterForm').submit();">
                            <option value="All" <?php echo $status_filter == 'All' ? 'selected' : ''; ?>>All</option>
                            <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo $status_filter == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Resolved" <?php echo $status_filter == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                        </select>
                    </div>

                    <div class="filter-box">
                        <label>Category</label>
                        <select name="category" onchange="document.getElementById('filterForm').submit();">
                            <option value="All" <?php echo $category_filter == 'All' ? 'selected' : ''; ?>>All</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['categoryID']; ?>" <?php echo $category_filter == $cat['categoryID'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-box">
                        <label>Priority</label>
                        <select name="priority" onchange="document.getElementById('filterForm').submit();">
                            <option value="All" <?php echo $priority_filter == 'All' ? 'selected' : ''; ?>>All</option>
                            <option value="High" <?php echo $priority_filter == 'High' ? 'selected' : ''; ?>>High</option>
                            <option value="Medium" <?php echo $priority_filter == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="Low" <?php echo $priority_filter == 'Low' ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-container">
                <table class="complaint-table">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($complaints)): ?>
                            <tr>
                                <td colspan="10" style="text-align: center; color: #a0aec0; padding: 30px;">No complaints found matching your filters.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($complaints as $row): ?>
                                <tr>
                                    <td><strong>C<?php echo str_pad($row['complaintID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($row['complaint_title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['resident_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['apartment_no']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td>
                                        <span class="badge priority-<?php echo strtolower($row['priority'] ?? 'low'); ?>">
                                            <?php echo htmlspecialchars($row['priority'] ?? 'None'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge status-<?php echo str_replace(' ', '', strtolower($row['status_name'] ?? 'pending')); ?>">
                                            <?php echo htmlspecialchars($row['status_name']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['assigned_to'] ?: 'Unassigned'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_date'])); ?></td>
                                    <td>
                                        <a href="complaint-manage.php?id=<?php echo $row['complaintID']; ?>" style="text-decoration: none;">
                                            <button class="action-btn" title="View Details">👁️</button>
                                        </a>
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