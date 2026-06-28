<?php
session_start();
require_once '../../utils/db.php';

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$message = '';

if (isset($_POST['delete_resident'])) {
    $resident_id = intval($_POST['resident_id']);

    $check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaint WHERE residentID = $resident_id");
    $check_row = mysqli_fetch_assoc($check);

    if (($check_row['total'] ?? 0) > 0) {
        $message = "This resident cannot be deleted because they already have complaint records.";
    } else {
        $delete = mysqli_query($conn, "DELETE FROM resident WHERE residentID = $resident_id");
        $message = $delete ? "Resident deleted successfully." : "Unable to delete resident.";
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_sql = '';

if ($search !== '') {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $where_sql = "
        WHERE r.full_name LIKE '%$safe_search%'
        OR r.email LIKE '%$safe_search%'
        OR r.phone_number LIKE '%$safe_search%'
        OR r.apartment_no LIKE '%$safe_search%'
    ";
}

$residents = [];
$sql = "
    SELECT
        r.residentID,
        r.full_name,
        r.email,
        r.phone_number,
        r.apartment_no,
        COUNT(c.complaintID) AS total_complaints
    FROM resident r
    LEFT JOIN complaint c ON r.residentID = c.residentID
    $where_sql
    GROUP BY r.residentID, r.full_name, r.email, r.phone_number, r.apartment_no
    ORDER BY r.residentID DESC
";

$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $residents[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident List</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <style>
        .search-row {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-row input {
            flex: 1;
            min-width: 260px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .search-row input:focus {
            border-color: #1a73e8;
        }

        .message-box {
            background: #e6fffa;
            color: #234e52;
            border: 1px solid #b2f5ea;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .resident-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="admin-main-layout">

    <?php include_once '../navbar-admin.php'; ?>

    <div class="admin-wrapper">
        <div class="page-header-row">
            <h1 class="page-title" style="margin-bottom:0;">Resident List</h1>
            <a href="dashboard-admin.php" class="add-btn">Back to Dashboard</a>
        </div>

        <?php if ($message !== ''): ?>
            <div class="message-box"><?php echo e($message); ?></div>
        <?php endif; ?>

        <form method="GET" action="resident-list.php" class="search-row">
            <input
                type="text"
                name="search"
                value="<?php echo e($search); ?>"
                placeholder="Search by name, email, phone number, or apartment no...">
            <button type="submit" class="add-btn">Search</button>
            <a href="resident-list.php" class="cancel-btn">Reset</a>
        </form>

        <div class="table-container">
            <table class="complaint-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Apartment No</th>
                        <th>Total Complaints</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($residents)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; color:#a0aec0; padding:30px;">
                                No resident records found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($residents as $row): ?>
                            <tr>
                                <td><strong>R<?php echo str_pad($row['residentID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><strong><?php echo e($row['full_name']); ?></strong></td>
                                <td><?php echo e($row['email']); ?></td>
                                <td><?php echo e($row['phone_number']); ?></td>
                                <td><?php echo e($row['apartment_no']); ?></td>
                                <td><?php echo (int)$row['total_complaints']; ?></td>
                                <td style="text-align:center;">
                                    <div class="resident-actions" style="justify-content:center;">
                                        <a href="resident-edit.php?id=<?php echo $row['residentID']; ?>" class="action-link" title="Edit Resident">
                                            <button class="action-btn">📝</button>
                                        </a>

                                        <form method="POST" action="resident-list.php" onsubmit="return confirm('Are you sure you want to delete this resident?');">
                                            <input type="hidden" name="resident_id" value="<?php echo $row['residentID']; ?>">
                                            <button type="submit" name="delete_resident" class="action-btn" title="Delete Resident">🗑️</button>
                                        </form>
                                    </div>
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
