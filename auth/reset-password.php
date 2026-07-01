<?php
session_start();
require_once '../utils/db.php';

if (!isset($_GET['token'])) {
    die("Invalid reset link.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$query = mysqli_query($conn, "SELECT * FROM resident WHERE reset_token='$token'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("This reset link is invalid or has expired.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="auth-body">
    <div class="form-container">
        <h2>Reset Password</h2>
        <p style="text-align:center; color:#718096; margin-bottom:20px; font-size:14px;">
            Create a new password for your account.
        </p>

        <form action="../utils/controller.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label class="form-label" for="new_password">New Password</label>
                <input class="form-control" type="password" name="new_password" id="new_password" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input class="form-control" type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button type="submit" name="reset_password" class="btn-primary" style="width:100%;">Reset Password</button>
        </form>
    </div>

    <script src="../utils/script.js"></script>
</body>
</html>