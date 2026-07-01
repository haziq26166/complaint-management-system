<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="auth-body">
    <div class="form-container">
        <h2>Forgot Password</h2>
        <p style="text-align:center; color:#718096; margin-bottom:20px; font-size:14px;">
            Enter your registered email to receive a password reset link.
        </p>

        <form action="../utils/controller.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="reset_email">Email Address</label>
                <input class="form-control" type="email" name="reset_email" id="reset_email" placeholder="Enter your registered email" required>
                <small id="emailError" style="display:block; margin-top:5px; color:#718096;">Enter your registered email address</small>
            </div>

            <button type="submit" name="forgot_password" class="btn-primary" style="width:100%;">Send Reset Link</button>

            <p class="link-text">
                Remember your password? <a href="login.html">Back to Login</a>
            </p>
        </form>
    </div>

    <script src="../utils/script.js"></script>
</body>
</html>