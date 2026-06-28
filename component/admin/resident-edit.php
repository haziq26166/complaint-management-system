<?php
session_start();
require_once '../../utils/db.php';

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('No resident ID provided.'); window.location.href='resident-list.php';</script>";
    exit();
}

$resident_id = intval($_GET['id']);
$message = '';

if (isset($_POST['update_resident'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $apartment_no = trim($_POST['apartment_no']);
    $new_password = $_POST['new_password'] ?? '';

    if ($full_name === '' || $email === '' || $apartment_no === '') {
        $message = 'Full name, email, and apartment number are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } else {
        $safe_email = mysqli_real_escape_string($conn, $email);
        $duplicate = mysqli_query($conn, "
            SELECT residentID
            FROM resident
            WHERE email = '$safe_email'
            AND residentID != $resident_id
            LIMIT 1
        ");

        if ($duplicate && mysqli_num_rows($duplicate) > 0) {
            $message = 'This email is already used by another resident.';
        } else {
            $safe_name = mysqli_real_escape_string($conn, $full_name);
            $safe_phone = mysqli_real_escape_string($conn, $phone_number);
            $safe_apartment = mysqli_real_escape_string($conn, $apartment_no);

            $password_sql = '';
            if ($new_password !== '') {
                if (strlen($new_password) < 8) {
                    $message = 'New password must be at least 8 characters.';
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $safe_password = mysqli_real_escape_string($conn, $hashed_password);
                    $password_sql = ", password = '$safe_password'";
                }
            }

            if ($message === '') {
                $update_sql = "
                    UPDATE resident
                    SET
                        full_name = '$safe_name',
                        email = '$safe_email',
                        phone_number = '$safe_phone',
                        apartment_no = '$safe_apartment'
                        $password_sql
                    WHERE residentID = $resident_id
                ";

                if (mysqli_query($conn, $update_sql)) {
                    echo "<script>alert('Resident updated successfully!'); window.location.href='resident-list.php';</script>";
                    exit();
                } else {
                    $message = 'Unable to update resident details.';
                }
            }
        }
    }
}

$query = mysqli_query($conn, "SELECT * FROM resident WHERE residentID = $resident_id LIMIT 1");
$resident = $query ? mysqli_fetch_assoc($query) : null;

if (!$resident) {
    echo "<script>alert('Resident record not found.'); window.location.href='resident-list.php';</script>";
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
    <style>
        .message-box {
            background: #fff5f5;
            color: #9b2c2c;
            border: 1px solid #fed7d7;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="admin-main-layout">

    <?php include_once '../navbar-admin.php'; ?>

    <div class="admin-wrapper">
        <a href="resident-list.php" class="back-navigation-link">← Back to Resident List</a>
        <h1 class="page-title" style="margin-bottom:25px;">Edit Resident</h1>

        <?php if ($message !== ''): ?>
            <div class="message-box"><?php echo e($message); ?></div>
        <?php endif; ?>

        <div class="manage-card" style="max-width:700px; padding:30px;">
            <form method="POST" action="resident-edit.php?id=<?php echo $resident_id; ?>">

                <div class="form-field-group">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="full_name"
                        value="<?php echo e($resident['full_name']); ?>"
                        required>
                </div>

                <div class="form-field-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="<?php echo e($resident['email']); ?>"
                        required>
                </div>

                <div class="form-field-group">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone_number"
                        value="<?php echo e($resident['phone_number']); ?>">
                </div>

                <div class="form-field-group">
                    <label>Apartment / Unit Number</label>
                    <input
                        type="text"
                        name="apartment_no"
                        value="<?php echo e($resident['apartment_no']); ?>"
                        required>
                </div>

                <div class="form-field-group">
                    <label>New Password</label>
                    <input
                        type="password"
                        name="new_password"
                        placeholder="Leave blank if you do not want to change password">
                </div>

                <div class="form-action-row">
                    <a href="resident-list.php" class="cancel-btn">Cancel</a>
                    <button type="submit" name="update_resident" class="submit-blue-btn">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>

