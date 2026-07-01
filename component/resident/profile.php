<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];
$message = '';
$error = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Get current user data
    $query = mysqli_query($conn, "SELECT password FROM resident WHERE residentID = $residentID");
    $user = mysqli_fetch_assoc($query);
    
    // Validate current password
    if (!password_verify($current_password, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($new_password === $current_password) {
        $error = "New password must be different from current password.";
    } elseif (strlen($new_password) < 8) {
        $error = "New password must be at least 8 characters.";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $error = "New password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $error = "New password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $error = "New password must contain at least one number.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $new_password)) {
        $error = "New password must contain at least one special character.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE resident SET password = '$hashed_password' WHERE residentID = $residentID");
        
        if ($update) {
            $message = "Password changed successfully!";
        } else {
            $error = "Failed to update password. Please try again.";
        }
    }
}

$query = mysqli_query($conn, "SELECT * FROM resident WHERE residentID = $residentID");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <a href="dashboard-resident.php" class="back-link">← Back to Dashboard</a>
        <h1 class="page-title">My Profile</h1>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo e($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Profile Information -->
            <div class="details-card">
                <h3 class="card-title">Profile Information</h3>
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div style="flex:1;">
                        <div class="meta-item" style="margin-bottom:16px;">
                            <label>Full Name</label>
                            <p style="font-size:18px; font-weight:600;"><?php echo e($user['full_name']); ?></p>
                        </div>
                        <div class="meta-item" style="margin-bottom:16px;">
                            <label>Email Address</label>
                            <p><?php echo e($user['email']); ?></p>
                        </div>
                        <div class="meta-item" style="margin-bottom:16px;">
                            <label>Phone Number</label>
                            <p><?php echo e($user['phone_number']); ?></p>
                        </div>
                        <div class="meta-item" style="margin-bottom:16px;">
                            <label>Apartment / Unit Number</label>
                            <p><?php echo e($user['apartment_no']); ?></p>
                        </div>
                        <div class="meta-item" style="margin-bottom:16px;">
                            <label>Account Status</label>
                            <p>
                                <span class="badge <?php echo $user['resident_status'] === 'active' ? 'badge-resolved' : 'badge-pending'; ?>">
                                    <?php echo ucfirst($user['resident_status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-top:20px; border-top:1px solid #edf2f7; padding-top:20px;">
                    <a href="profile-edit.php" class="btn-primary">Edit Profile</a>
                </div>
            </div>

            <!-- Change Password -->
            <div class="details-card">
                <h3 class="card-title">Change Password</h3>
                <form action="" method="POST" onsubmit="return validatePasswordChange()">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <div class="password-container">
                            <input class="form-control" type="password" name="current_password" id="current_password" placeholder="Enter current password" required>
                            <span class="toggle-password" onclick="togglePassword('current_password')">👁️</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="password-container">
                            <input class="form-control" type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
                            <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
                        </div>
                        <div id="passwordRequirements" style="margin-top:8px;">
                            <small id="lengthCheck">❌ Minimum 8 characters</small>
                            <small id="upperCheck">❌ At least 1 uppercase letter</small>
                            <small id="lowerCheck">❌ At least 1 lowercase letter</small>
                            <small id="numberCheck">❌ At least 1 number</small>
                            <small id="specialCheck">❌ At least 1 special character</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <div class="password-container">
                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                        </div>
                        <small id="confirmPasswordError" style="display:block; margin-top:5px;"></small>
                    </div>

                    <button type="submit" name="change_password" class="btn-primary" style="width:100%;">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../utils/script.js"></script>
</body>
</html>