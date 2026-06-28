<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['resident_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$resident_id = $_SESSION['resident_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO complaint
              (description, created_date, categoryID, residentID)
              VALUES
              ('$description', NOW(), '$category_id', '$resident_id')";

    if (mysqli_query($conn, $query)) {

        $complaint_id = mysqli_insert_id($conn);

        mysqli_query($conn,"
            INSERT INTO status_complaint
            (staffID, complaintID, status, priority, assigned_to, notes, update_date)
            VALUES
            (1,'$complaint_id','Pending','Medium','Unassigned',
            'Complaint submitted',NOW())
        ");

        echo "<script>
                alert('Complaint submitted successfully!');
                window.location='complaint-history.php';
              </script>";
    }
}

$categories = mysqli_query($conn,"SELECT * FROM category ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../../styles/resident.css">
</head>
<body>

<div class="container">

    <h2>Submit Complaint</h2>

    <form method="POST">

        <label>Complaint Type</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>

            <?php while($row=mysqli_fetch_assoc($categories)){ ?>
                <option value="<?= $row['categoryID']; ?>">
                    <?= $row['name']; ?>
                </option>
            <?php } ?>

        </select>

        <label>Description</label>

        <textarea
            name="description"
            rows="6"
            required
            placeholder="Describe your complaint..."
        ></textarea>

        <button type="submit">
            Submit Complaint
        </button>

    </form>

</div>

</body>
</html>