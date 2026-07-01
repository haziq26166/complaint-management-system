<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: resident-list.php");
    exit();
}

$resident_id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM resident WHERE residentID = $resident_id");
$resident = mysqli_fetch_assoc($query);

if (!$resident) {
    header("Location: resident-list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resident</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <a href="resident-list.php" class="back-link">← Back to Residents</a>
        <h1 class="page-title">Edit Resident</h1>

        <div class="form-card" style="max-width:600px;">
            <form action="../../utils/controller.php" method="POST" onsubmit="return validateAdminResidentEdit()">
                <input type="hidden" name="resident_id" value="<?php echo $resident['residentID']; ?>">

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input class="form-control" type="text" name="full_name" id="admin_full_name" value="<?php echo e($resident['full_name']); ?>" required>
                    <small id="adminNameError" style="display:block; margin-top:5px; color:#718096;"></small>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input class="form-control" type="email" name="email" value="<?php echo e($resident['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input class="form-control" type="text" name="phone_number" id="admin_phone_number" value="<?php echo e($resident['phone_number']); ?>" required>
                    <small id="adminPhoneError" style="display:block; margin-top:5px; color:#718096;"></small>
                </div>

                <div class="form-group">
                    <label class="form-label">Apartment / Unit Number</label>
                    <input class="form-control" type="text" name="apartment_no" value="<?php echo e($resident['apartment_no']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Status</label>
                    <select class="form-control" name="resident_status" required>
                        <option value="active" <?php echo $resident['resident_status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $resident['resident_status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <small style="color:#718096; display:block; margin-top:4px;">
                        <strong>Note:</strong> Inactive users will not be able to login to the system.
                    </small>
                </div>

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <a href="resident-list.php" class="btn-secondary">Cancel</a>
                    <button type="submit" name="update_resident" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../utils/script.js"></script>
</body>
</html>