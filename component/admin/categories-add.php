<?php
require_once '../../utils/session_check.php';
requireLogin();
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<div class="admin-main-layout">
    <?php include_once '../navbar-admin.php'; ?>
    
    <div class="admin-wrapper">
        <a href="categories-list.php" class="back-link">← Back to Categories</a>
        <h1 class="page-title">Add Category</h1>

        <div class="form-card" style="max-width:600px;">
            <form action="../../utils/controller.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input class="form-control" type="text" name="category_name" placeholder="e.g., HVAC, Plumbing" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Describe what issues fall under this category..."></textarea>
                </div>

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <a href="categories-list.php" class="btn-secondary">Cancel</a>
                    <button type="submit" name="add_category_submit" class="btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>