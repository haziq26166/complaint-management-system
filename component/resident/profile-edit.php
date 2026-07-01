<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];
$message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $apartment = mysqli_real_escape_string($conn, trim($_POST['apartment_no']));
    
    // Validate full name (only letters, spaces, hyphens, apostrophes)
    if (!preg_match("/^[A-Za-z\s\-']+$/", $full_name)) {
        $message = "Full name can only contain letters, spaces, hyphens, and apostrophes.";
    } 
    // Validate phone number (only digits, 10-15 characters)
    elseif (!preg_match("/^\d{10,15}$/", $phone)) {
        $message = "Phone number must be 10-15 digits only.";
    }
    else {
        $sql = "UPDATE resident SET full_name='$full_name', phone_number='$phone', apartment_no='$apartment' WHERE residentID=$residentID";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['full_name'] = $full_name;
            echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
            exit();
        } else {
            $message = "Failed to update profile.";
        }
    }
}

// Fetch user data
$query = mysqli_query($conn, "SELECT * FROM resident WHERE residentID = $residentID");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <a href="profile.php" class="back-link">← Back to Profile</a>
        <h1 class="page-title">Edit Profile</h1>

        <?php if ($message): ?>
            <div class="alert alert-error"><?php echo e($message); ?></div>
        <?php endif; ?>

        <div class="form-card" style="max-width:600px;">
            <form method="POST" onsubmit="return validateProfileEdit()">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input class="form-control" type="text" name="full_name" id="profile_full_name" value="<?php echo e($user['full_name']); ?>" required>
                    <small id="profileNameError" style="display:block; margin-top:5px; color:#718096;"></small>
                </div>

                <!-- Email is READ-ONLY (user cannot change it) -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input class="form-control" type="email" name="email" value="<?php echo e($user['email']); ?>" readonly disabled>
                    <small style="color:#718096; display:block; margin-top:4px;">Email cannot be changed.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input class="form-control" type="text" name="phone_number" id="profile_phone_number" value="<?php echo e($user['phone_number']); ?>" required>
                    <small id="profilePhoneError" style="display:block; margin-top:5px; color:#718096;"></small>
                </div>

                <div class="form-group">
                    <label class="form-label">Apartment / Unit Number</label>
                    <input class="form-control" type="text" name="apartment_no" value="<?php echo e($user['apartment_no']); ?>">
                </div>

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <a href="profile.php" class="btn-secondary">Cancel</a>
                    <button type="submit" name="update_profile" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../utils/script.js"></script>
</body>
</html>