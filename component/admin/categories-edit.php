<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: categories-list.php");
    exit();
}

$category_id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM category WHERE categoryID = $category_id");
$category = mysqli_fetch_assoc($query);

if (!$category) {
    header("Location: categories-list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <a href="categories-list.php" class="back-link">← Back to Categories</a>
        <h1 class="page-title">Edit Category</h1>

        <div class="form-card" style="max-width:600px;">
            <form action="../../utils/controller.php" method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category['categoryID']; ?>">

                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input class="form-control" type="text" name="category_name" value="<?php echo e($category['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4"><?php echo e($category['description']); ?></textarea>
                </div>

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <a href="categories-list.php" class="btn-secondary">Cancel</a>
                    <button type="submit" name="edit_category_submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>