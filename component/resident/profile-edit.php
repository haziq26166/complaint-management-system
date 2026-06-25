<?php
session_start();
include '../../utils/db.php';

if(!isset($_SESSION['residentID'])){
    header("Location: ../../auth/login.html");
    exit();
}

$residentID = $_SESSION['residentID'];

if(isset($_POST['update_profile'])){

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $apartment_no = $_POST['apartment_no'];

    mysqli_query($conn,
    "UPDATE resident
     SET
     full_name='$full_name',
     email='$email',
     phone_number='$phone_number',
     apartment_no='$apartment_no'
     WHERE residentID='$residentID'");

    $_SESSION['full_name'] = $full_name;
    $_SESSION['email'] = $email;

    echo "<script>
    alert('Profile Updated Successfully');
    window.location='profile.php';
    </script>";
}

$query = mysqli_query($conn,
"SELECT * FROM resident
 WHERE residentID='$residentID'");

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar-resident.php'; ?>

<div class="container mt-4">

<div class="card">

<div class="card-header">
<h3>Edit Profile</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>Full Name</label>
<input type="text"
name="full_name"
class="form-control"
value="<?= $user['full_name']; ?>"
required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email"
name="email"
class="form-control"
value="<?= $user['email']; ?>"
required>
</div>

<div class="mb-3">
<label>Phone Number</label>
<input type="text"
name="phone_number"
class="form-control"
value="<?= $user['phone_number']; ?>">
</div>

<div class="mb-3">
<label>Apartment No</label>
<input type="text"
name="apartment_no"
class="form-control"
value="<?= $user['apartment_no']; ?>">
</div>

<button type="submit"
name="update_profile"
class="btn btn-success">
Update Profile
</button>

<a href="profile.php"
class="btn btn-secondary">
Cancel
</a>

</form>

</div>
</div>

</div>

</body>
</html>
