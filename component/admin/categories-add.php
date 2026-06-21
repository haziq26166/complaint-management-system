<?php
require_once '../../utils/controller.php';
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
            
            <a href="categories-list.php" class="back-navigation-link">← Back to Categories</a>
            <h1 class="page-title" style="margin-bottom: 25px;">Add Category</h1>

            <div class="manage-card" style="max-width: 650px; padding: 30px;">
                <form method="POST" action="categories-add.php">
                    
                    <div class="form-field-group">
                        <label>Category Name</label>
                        <input 
                            type="text" 
                            name="category_name" 
                            placeholder="e.g., HVAC, Carpentry" 
                            required>
                    </div>

                    <div class="form-field-group">
                        <label>Description</label>
                        <textarea 
                            name="description" 
                            placeholder="Provide a brief description of what issues fall under this category..."></textarea>
                    </div>

                    <div class="form-action-row">
                        <a href="categories-list.php" class="cancel-btn">Cancel</a>
                        <button type="submit" name="add_category_submit" class="submit-blue-btn">Add Category</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>
</html>