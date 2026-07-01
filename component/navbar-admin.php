<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['full_name'] ?? 'Administrator';

function adminActive($page, $current_page) {
    return $page === $current_page ? 'active' : '';
}
?>
<aside class="admin-sidebar">
    <div class="admin-logo">RCMS Admin</div>
    <div class="admin-subtitle">Residential Complaint Management System</div>

    <ul class="admin-menu">
        <li>
            <a href="dashboard-admin.php" class="<?php echo adminActive('dashboard-admin.php', $current_page); ?>">
                📊 Dashboard
            </a>
        </li>
        <li>
            <a href="complaint-list.php" class="<?php echo adminActive('complaint-list.php', $current_page); ?>">
                📋 Complaints
            </a>
        </li>
        <li>
            <a href="resident-list.php" class="<?php echo adminActive('resident-list.php', $current_page); ?>">
                👥 Residents
            </a>
        </li>
        <li>
            <a href="categories-list.php" class="<?php echo adminActive('categories-list.php', $current_page); ?>">
                🏷️ Categories
            </a>
        </li>
    </ul>

    <div class="admin-user-box">
        Logged in as
        <strong><?php echo htmlspecialchars($admin_name); ?></strong>
    </div>

    <a href="../../auth/logout.php" class="admin-logout">🚪 Logout</a>
</aside>