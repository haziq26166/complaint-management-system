<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';

if ($search !== '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE r.full_name LIKE '%$safe%' OR r.email LIKE '%$safe%' OR r.phone_number LIKE '%$safe%' OR r.apartment_no LIKE '%$safe%'";
}

$residents = mysqli_query($conn, "
    SELECT r.*, COUNT(c.complaintID) as complaint_count
    FROM resident r
    LEFT JOIN complaint c ON r.residentID = c.residentID
    $where
    GROUP BY r.residentID
    ORDER BY r.residentID DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident List</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <div class="page-header-row">
            <h1 class="page-title" style="margin-bottom:0;">Resident List</h1>
        </div>

        <form method="GET" action="" class="search-form">
            <div style="display:flex; gap:12px; margin-bottom:20px;">
                <input class="form-control" type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search residents..." style="max-width:400px;">
                <button type="submit" class="btn-primary">Search</button>
                <a href="resident-list.php" class="btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Apartment</th>
                        <th>Status</th>
                        <th>Complaints</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($residents) === 0): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; color:#a0aec0; padding:40px;">
                                No residents found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($residents)): ?>
                            <tr>
                                <td><strong>R<?php echo str_pad($row['residentID'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><strong><?php echo e($row['full_name']); ?></strong></td>
                                <td><?php echo e($row['email']); ?></td>
                                <td><?php echo e($row['phone_number']); ?></td>
                                <td><?php echo e($row['apartment_no']); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['resident_status'] === 'active' ? 'badge-resolved' : 'badge-pending'; ?>">
                                        <?php echo ucfirst($row['resident_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['complaint_count']; ?></td>
                                <td style="text-align:center;">
                                    <a href="resident-edit.php?id=<?php echo $row['residentID']; ?>" class="btn-sm">Edit</a>
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