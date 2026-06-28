<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['admin_name'] ?? $_SESSION['full_name'] ?? 'Administrator';

function adminActive($page, $current_page) {
    return $page === $current_page ? 'active' : '';
}
?>

<style>
    .admin-sidebar {
        width: 260px;
        min-height: 100vh;
        background: #233d5a;
        color: #ffffff;
        padding: 26px 20px;
        position: sticky;
        top: 0;
        align-self: flex-start;
    }

    .admin-logo {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .admin-subtitle {
        font-size: 12px;
        color: #cbd5e0;
        margin-bottom: 28px;
    }

    .admin-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .admin-menu li {
        margin-bottom: 10px;
    }

    .admin-menu a {
        display: block;
        color: #e2e8f0;
        text-decoration: none;
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .admin-menu a:hover,
    .admin-menu a.active {
        background: #ffffff;
        color: #233d5a;
    }

    .admin-user-box {
        margin-top: 30px;
        padding: 14px;
        background: rgba(255,255,255,0.10);
        border-radius: 8px;
        font-size: 13px;
        color: #e2e8f0;
    }

    .admin-user-box strong {
        display: block;
        color: #ffffff;
        margin-top: 3px;
    }

    .admin-logout {
        display: block;
        margin-top: 14px;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(255,255,255,0.14);
        text-align: center;
    }

    .admin-logout:hover {
        background: rgba(255,255,255,0.24);
    }

    @media (max-width: 900px) {
        .admin-main-layout {
            flex-direction: column;
        }

        .admin-sidebar {
            width: 100%;
            min-height: auto;
            position: relative;
        }

        .admin-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .admin-menu li {
            margin-bottom: 0;
        }
    }
</style>

<aside class="admin-sidebar">
    <div class="admin-logo">RCMS Admin</div>
    <div class="admin-subtitle">Residential Complaint Management System</div>

    <ul class="admin-menu">
        <li>
            <a href="dashboard-admin.php" class="<?php echo adminActive('dashboard-admin.php', $current_page); ?>">
                Dashboard
            </a>
        </li>
        <li>
            <a href="complaint-list.php" class="<?php echo adminActive('complaint-list.php', $current_page); ?>">
                Complaints
            </a>
        </li>
        <li>
            <a href="resident-list.php" class="<?php echo adminActive('resident-list.php', $current_page); ?>">
                Residents
            </a>
        </li>
        <li>
            <a href="categories-list.php" class="<?php echo adminActive('categories-list.php', $current_page); ?>">
                Categories
            </a>
        </li>
    </ul>

    <div class="admin-user-box">
        Logged in as
        <strong><?php echo htmlspecialchars($admin_name); ?></strong>
    </div>

    <a href="../../auth/login.html" class="admin-logout">Logout</a>
</aside>

