<?php
session_start();
include '../../utils/db.php';

if(!isset($_SESSION['residentID'])){
    header("Location: ../../auth/login.html");
    exit();
}

$residentID = $_SESSION['residentID'];

$query = mysqli_query($conn,
"SELECT * FROM resident
 WHERE residentID='$residentID'");

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar-resident.php'; ?>

<div class="container mt-4">

<div class="card">

<div class="card-header">
<h3>My Profile</h3>
</div>

<div class="card-body">

<p><strong>Full Name:</strong>
<?= $user['full_name']; ?>
</p>

<p><strong>Email:</strong>
<?= $user['email']; ?>
</p>

<p><strong>Phone Number:</strong>
<?= $user['phone_number']; ?>
</p>

<p><strong>Apartment No:</strong>
<?= $user['apartment_no']; ?>
</p>

<a href="profile-edit.php"
class="btn btn-primary">
Edit Profile
</a>

</div>
</div>

</div>

</body>
</html>
