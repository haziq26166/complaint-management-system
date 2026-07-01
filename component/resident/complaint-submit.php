<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];
$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <a href="dashboard-resident.php" class="back-link">← Back to Dashboard</a>
        <h1 class="page-title">Submit a New Complaint</h1>

        <div class="form-card" style="max-width:700px;">
            <form action="../../utils/controller.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Complaint Title</label>
                    <input class="form-control" type="text" name="title" placeholder="Brief title for your complaint" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-control" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo $row['categoryID']; ?>">
                                <?php echo e($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="6" placeholder="Describe your complaint in detail..." required></textarea>
                </div>

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <a href="dashboard-resident.php" class="btn-secondary">Cancel</a>
                    <button type="submit" name="submit_complaint" class="btn-primary">Submit Complaint</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>