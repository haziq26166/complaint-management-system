<?php
require_once '../../utils/controller.php';
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
                <h1 class="page-title" style="margin-bottom: 0;">Manage Categories</h1>
                <a href="categories-add.php" class="add-btn">+ Add Category</a>
            </div>

            <div class="table-container">
                <table class="complaint-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 60%;">Description</th>
                            <th style="width: 15%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories_list)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #a0aec0; padding: 30px;">No categories configured.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories_list as $cat): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                                    <td style="white-space: normal; color: #718096;">
                                        <?php echo htmlspecialchars($cat['description'] ?: 'No description provided.'); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="categories-edit.php?id=<?php echo $cat['categoryID']; ?>" class="action-link" title="Edit Category">
                                            <button class="action-btn">📝</button>
                                        </a>
                                        
                                        <form method="POST" action="../../utils/controller.php" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            <input type="hidden" name="category_id" value="<?php echo $cat['categoryID']; ?>">
                                            <button type="submit" name="delete_category" class="action-btn" title="Delete Category">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>