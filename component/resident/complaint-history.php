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
    header("Location: ../auth/login.html");
    exit();
}

$resident_id = $_SESSION['residentID'];

$query = "
SELECT
    c.complaintID,
    c.description,
    c.created_date,
    cat.name AS category,
    sc.status_name
FROM complaint c
LEFT JOIN category cat
ON c.categoryID = cat.categoryID

LEFT JOIN status_complaint sc
ON c.complaintID = sc.complaintID

WHERE c.residentID = '$resident_id'
ORDER BY c.created_date DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint History</title>
</head>
<body>

<h2>My Complaints</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

    <?php while($row=mysqli_fetch_assoc($result)){ ?>

    <tr>

        <td><?= $row['complaintID']; ?></td>

        <td><?= $row['category']; ?></td>

        <td><?= $row['status']; ?></td>

        <td><?= date('d M Y',strtotime($row['created_date'])); ?></td>

        <td>

            <a href="complaint-details.php?id=<?= $row['complaintID']; ?>">
                View
            </a>

        </td>

    </tr>

    <?php } ?>

</table>

</body>
</html>