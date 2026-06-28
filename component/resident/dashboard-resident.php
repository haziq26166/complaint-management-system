<?php
session_start();
include '../../utils/db.php';

// =========================
// Session Timeout (2 Minutes)
// =========================

$timeout = 120;

if(isset($_SESSION['LAST_ACTIVITY'])){

    if(time() - $_SESSION['LAST_ACTIVITY'] > $timeout){

        session_unset();
        session_destroy();

        header("Location: ../../auth/login.html?timeout=1");
        exit();

    }

}

$_SESSION['LAST_ACTIVITY'] = time();

if(!isset($_SESSION['residentID'])){
    header("Location: ../../auth/login.html");
    exit();
}

$residentID = $_SESSION['residentID'];

$total = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) total
     FROM complaint
     WHERE residentID='$residentID'")
);

$pending = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) total
     FROM complaint c
     JOIN status_complaint s
     ON c.statusID=s.statusID
     WHERE c.residentID='$residentID'
     AND s.status_name='Pending'")
);

$progress = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) total
     FROM complaint c
     JOIN status_complaint s
     ON c.statusID=s.statusID
     WHERE c.residentID='$residentID'
     AND s.status_name='In Progress'")
);

$resolved = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) total
     FROM complaint c
     JOIN status_complaint s
     ON c.statusID=s.statusID
     WHERE c.residentID='$residentID'
     AND s.status_name='Resolved'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Resident Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include '../navbar-resident.php'; ?>

<div class="container mt-4">

<h2>Welcome,
<?= $_SESSION['full_name']; ?>
</h2>

<div class="row mt-4">

<div class="col-md-3">
<div class="card text-center bg-primary text-white">
<div class="card-body">
<h5>Total Complaints</h5>
<h2><?= $total['total']; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center bg-warning">
<div class="card-body">
<h5>Pending</h5>
<h2><?= $pending['total']; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center bg-info text-white">
<div class="card-body">
<h5>In Progress</h5>
<h2><?= $progress['total']; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-center bg-success text-white">
<div class="card-body">
<h5>Resolved</h5>
<h2><?= $resolved['total']; ?></h2>
</div>
</div>
</div>

</div>

</div>

</body>
</html>
