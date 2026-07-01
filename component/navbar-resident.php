<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'Resident';
}
?>
<nav class="navbar-resident">
    <div class="container">
        <div class="nav-brand">
            <a href="dashboard-resident.php" class="brand-link">🏠 RCMS</a>
        </div>
        <div class="nav-links">
            <a href="dashboard-resident.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard-resident.php' ? 'active' : ''; ?>">
                Dashboard
            </a>
            <a href="complaint-submit.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'complaint-submit.php' ? 'active' : ''; ?>">
                Submit Complaint
            </a>
            <a href="complaint-history.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'complaint-history.php' ? 'active' : ''; ?>">
                Complaint History
            </a>
            <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">
                Profile
            </a>
        </div>
        <div class="nav-user">
            <span>👤 <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="../../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</nav>