<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['resident_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$resident_id = $_SESSION['resident_id'];

$query = "
SELECT
    c.complaintID,
    c.description,
    c.created_date,
    cat.name AS category,
    sc.status
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