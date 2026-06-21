<?php
require_once '../../utils/controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - <?php echo htmlspecialchars($category_data['name']); ?></title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

    <div class="admin-main-layout">
        
        <?php include_once '../navbar-admin.php'; ?>

        <div class="admin-wrapper">
            
            <a href="categories-list.php" class="back-navigation-link">← Back to Categories</a>
            <h1 class="page-title" style="margin-bottom: 25px;">Edit Category</h1>

            <div class="manage-card" style="max-width: 650px; padding: 30px;">
                <form method="POST" action="categories-edit.php?id=<?php echo $category_data['categoryID']; ?>">
                    
                    <input type="hidden" name="category_id" value="<?php echo $category_data['categoryID']; ?>">
                    
                    <div class="form-field-group">
                        <label>Category Name</label>
                        <input 
                            type="text" 
                            name="category_name" 
                            value="<?php echo htmlspecialchars($category_data['name']); ?>" 
                            required>
                    </div>

                    <div class="form-field-group">
                        <label>Description</label>
                        <textarea name="description"><?php echo htmlspecialchars($category_data['description']); ?></textarea>
                    </div>

                    <div class="form-action-row">
                        <a href="categories-list.php" class="cancel-btn">Cancel</a>
                        <button type="submit" name="edit_category_submit" class="submit-blue-btn">Save Changes</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>
</html>