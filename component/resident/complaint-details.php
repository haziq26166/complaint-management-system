<?php
session_start();
require_once '../../utils/db.php';

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

if (!isset($_SESSION['residentID'])) {
    header("Location: ../auth/login.php");
    exit();
}

$resident_id = $_SESSION['resident_id'];

if (!isset($_GET['id'])) {
    die("Complaint not found.");
}

$complaint_id = $_GET['id'];

$query = "
SELECT
    c.*,
    cat.name AS category,
    r.full_name,
    sc.status,
    sc.priority,
    sc.assigned_to,
    sc.notes,
    sc.update_date
FROM complaint c

LEFT JOIN category cat
ON c.categoryID = cat.categoryID

LEFT JOIN resident r
ON c.residentID = r.residentID

LEFT JOIN status_complaint sc
ON c.complaintID = sc.complaintID

WHERE c.complaintID='$complaint_id'
AND c.residentID='$resident_id'
";

$result = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Complaint not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint Details</title>
</head>
<body>

<a href="complaint-history.php">
    ← Back to My Complaints
</a>

<h2>Complaint Details</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Complaint ID</th>
    <td><?= $data['complaintID']; ?></td>
</tr>

<tr>
    <th>Category</th>
    <td><?= $data['category']; ?></td>
</tr>

<tr>
    <th>Resident</th>
    <td><?= $data['full_name']; ?></td>
</tr>

<tr>
    <th>Status</th>
    <td><?= $data['status']; ?></td>
</tr>

<tr>
    <th>Priority</th>
    <td><?= $data['priority']; ?></td>
</tr>

<tr>
    <th>Assigned To</th>
    <td><?= $data['assigned_to']; ?></td>
</tr>

<tr>
    <th>Created Date</th>
    <td><?= $data['created_date']; ?></td>
</tr>

<tr>
    <th>Last Updated</th>
    <td><?= $data['update_date']; ?></td>
</tr>

<tr>
    <th>Description</th>
    <td><?= $data['description']; ?></td>
</tr>

<tr>
    <th>Remarks / Notes</th>
    <td><?= $data['notes']; ?></td>
</tr>

</table>

</body>
</html>