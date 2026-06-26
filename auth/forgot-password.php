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

<form
action="../utils/controller.php"
method="POST">

<label>Email Address</label>

<input
type="email"
name="reset_email"
id="reset_email"
placeholder="Enter your registered email"
required>

<small id="emailError"></small>

<button
type="submit"
name="forgot_password">

Send Reset Link

</button>

<p class="link-text">

Remember your password?

<a href="login.html">

Back to Login

</a>

</p>

</form>

</div>

<script src="../utils/script.js"></script>

</body>

</html>