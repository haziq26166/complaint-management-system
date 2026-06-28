<?php
session_start();
require_once '../../utils/db.php';

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function columnExists($conn, $table, $column) {
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $result && mysqli_num_rows($result) > 0;
}

function getCount($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return 0;
    }
    $row = mysqli_fetch_assoc($result);
    return (int)($row['total'] ?? 0);
}

function getStatusCount($conn, $status) {
    $safe_status = mysqli_real_escape_string($conn, $status);

    if (columnExists($conn, 'complaint', 'statusID') && columnExists($conn, 'status_complaint', 'status_name')) {
        return getCount($conn, "
            SELECT COUNT(*) AS total
            FROM complaint c
            LEFT JOIN status_complaint sc ON c.statusID = sc.statusID
            WHERE sc.status_name = '$safe_status'
        ");
    }

    if (columnExists($conn, 'status_complaint', 'complaintID') && columnExists($conn, 'status_complaint', 'status')) {
        return getCount($conn, "
            SELECT COUNT(DISTINCT c.complaintID) AS total
            FROM complaint c
            LEFT JOIN status_complaint sc ON c.complaintID = sc.complaintID
            WHERE sc.status = '$safe_status'
        ");
    }

    return 0;
}

$complaint_title_sql = columnExists($conn, 'complaint', 'name')
    ? "c.name"
    : "LEFT(c.description, 70)";

if (columnExists($conn, 'complaint', 'statusID') && columnExists($conn, 'status_complaint', 'status_name')) {
    $status_join = "LEFT JOIN status_complaint sc ON c.statusID = sc.statusID";
    $status_sql = "COALESCE(sc.status_name, 'Pending')";
    $updated_sql = columnExists($conn, 'status_complaint', 'updated_date')
        ? "COALESCE(sc.updated_date, c.created_date)"
        : "c.created_date";
} else {
    $status_join = "LEFT JOIN status_complaint sc ON c.complaintID = sc.complaintID";
    $status_sql = columnExists($conn, 'status_complaint', 'status')
        ? "COALESCE(sc.status, 'Pending')"
        : "'Pending'";
    $updated_sql = columnExists($conn, 'status_complaint', 'update_date')
        ? "COALESCE(sc.update_date, c.created_date)"
        : "c.created_date";
}

$total_residents = getCount($conn, "SELECT COUNT(*) AS total FROM resident");
$total_complaints = getCount($conn, "SELECT COUNT(*) AS total FROM complaint");
$total_categories = getCount($conn, "SELECT COUNT(*) AS total FROM category");
$total_pending = getStatusCount($conn, 'Pending');
$total_progress = getStatusCount($conn, 'In Progress');
$total_resolved = getStatusCount($conn, 'Resolved');

$recent_complaints = [];
$recent_sql = "
    SELECT
        c.complaintID,
        $complaint_title_sql AS complaint_title,
        c.created_date,
        r.full_name AS resident_name,
        r.apartment_no,
        cat.name AS category_name,
        $status_sql AS status_name,
        $updated_sql AS updated_date
    FROM complaint c
    LEFT JOIN resident r ON c.residentID = r.residentID
    LEFT JOIN category cat ON c.categoryID = cat.categoryID
    $status_join
    GROUP BY c.complaintID
    ORDER BY c.created_date DESC
    LIMIT 5
";

$recent_result = mysqli_query($conn, $recent_sql);
if ($recent_result) {
    while ($row = mysqli_fetch_assoc($recent_result)) {
        $recent_complaints[] = $row;
    }
}

function statusClass($status) {
    return str_replace(' ', '', strtolower($status ?: 'pending'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .dashboard-card span {
            display: block;
            color: #718096;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .dashboard-card h2 {
            color: #233d5a;
            font-size: 34px;
            margin: 0;
        }

        .dashboard-section-title {
            color: #233d5a;
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .quick-action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="admin-main-layout">

    <?php include_once '../navbar-admin.php'; ?>

    <div class="admin-wrapper">
        <h1 class="page-title">Admin Dashboard</h1>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <span>Total Residents</span>
                <h2><?php echo $total_residents; ?></h2>
            </div>

            <div class="dashboard-card">
                <span>Total Complaints</span>
                <h2><?php echo $total_complaints; ?></h2>
            </div>

            <div class="dashboard-card">
                <span>Pending</span>
                <h2><?php echo $total_pending; ?></h2>
            </div>

            <div class="dashboard-card">
                <span>In Progress</span>
                <h2><?php echo $total_progress; ?></h2>
            </div>

            <div class="dashboard-card">
                <span>Resolved</span>
                <h2><?php echo $total_resolved; ?></h2>
            </div>

            <div class="dashboard-card">
                <span>Categories</span>
                <h2><?php echo $total_categories; ?></h2>
            </div>
        </div>

        <div class="quick-action-row">
            <a href="complaint-list.php" class="add-btn">View Complaints</a>
            <a href="resident-list.php" class="add-btn">Manage Residents</a>
            <a href="categories-list.php" class="add-btn">Manage Categories</a>
        </div>

        <h2 class="dashboard-section-title">Recent Complaints</h2>

        <div class="table-container">
            <table class="complaint-table">
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
                    <?php if (empty($recent_complaints)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; color:#a0aec0; padding:30px;">
                                No complaints have been submitted yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_complaints as $row): ?>
                            <tr>
                                <td><strong>C<?php echo str_pad($row['complaintID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><strong><?php echo e($row['complaint_title']); ?></strong></td>
                                <td><?php echo e($row['resident_name']); ?></td>
                                <td><?php echo e($row['apartment_no']); ?></td>
                                <td><?php echo e($row['category_name']); ?></td>
                                <td>
                                    <span class="badge status-<?php echo statusClass($row['status_name']); ?>">
                                        <?php echo e($row['status_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($row['created_date']) ? date('M d, Y', strtotime($row['created_date'])) : '-'; ?></td>
                                <td>
                                    <a href="complaint-manage.php?id=<?php echo $row['complaintID']; ?>" class="action-link">
                                        <button class="action-btn" title="Manage Complaint">👁️</button>
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
