<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <div class="page-header-row">
            <h1 class="page-title" style="margin-bottom:0;">Manage Categories</h1>
            <a href="categories-add.php" class="btn-primary">+ Add Category</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:25%;">Name</th>
                        <th style="width:55%;">Description</th>
                        <th style="width:20%; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($categories) === 0): ?>
                        <tr>
                            <td colspan="3" style="text-align:center; color:#a0aec0; padding:40px;">
                                No categories configured.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                            <tr>
                                <td><strong><?php echo e($row['name']); ?></strong></td>
                                <td style="white-space:normal;"><?php echo e($row['description'] ?: 'No description provided.'); ?></td>
                                <td style="text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center;">
                                        <a href="categories-edit.php?id=<?php echo $row['categoryID']; ?>" class="btn-sm">Edit</a>
                                        <form method="POST" action="../../utils/controller.php" onsubmit="return confirm('Delete this category?');">
                                            <input type="hidden" name="category_id" value="<?php echo $row['categoryID']; ?>">
                                            <button type="submit" name="delete_category" class="btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>