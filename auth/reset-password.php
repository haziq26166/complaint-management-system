<?php

session_start();
include '../utils/db.php';

if (!isset($_GET['token'])) {
    die("Invalid reset link.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$query = mysqli_query($conn,
    "SELECT * FROM resident
     WHERE reset_token='$token'");

if (mysqli_num_rows($query) != 1) {
    die("This reset link is invalid or has expired.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link rel="stylesheet"
          href="../styles/style.css">
</head>

<body class="auth-body">

<div class="form-container">

    <h2>Reset Password</h2>

    <form action="../utils/controller.php" method="POST">

        <input type="hidden"
               name="token"
               value="<?php echo htmlspecialchars($token); ?>">

        <label>New Password</label>

        <input
            type="password"
            name="new_password"
            id="new_password"
            required>

        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            required>

        <button
            type="submit"
            name="reset_password">

            Reset Password

        </button>

    </form>

</div>

<script src="../utils/script.js"></script>

</body>
</html>