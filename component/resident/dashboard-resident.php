<?php
require_once '../../utils/session_check.php';
requireLogin();

$residentID = $_SESSION['residentID'];
$stats = getComplaintStats($residentID);

// If stats is null, initialize with default values
if (!$stats) {
    $stats = [
        'total' => 0,
        'pending' => 0,
        'in_progress' => 0,
        'resolved' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>
<body>

<?php include_once '../navbar-resident.php'; ?>

<div class="main-container">
    <div class="container">
        <h1 class="page-title">Welcome, <?php echo e($_SESSION['full_name']); ?>!</h1>
        <p style="color:#718096; margin-bottom:30px;">Track your complaints and submit new ones from here.</p>

        <div class="dashboard-grid">
            <div class="stat-card">
                <span class="stat-label">Total Complaints</span>
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #ed8936;">
                <span class="stat-label">Pending</span>
                <div class="stat-number" style="color:#ed8936;"><?php echo $stats['pending'] ?? 0; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #4299e1;">
                <span class="stat-label">In Progress</span>
                <div class="stat-number" style="color:#4299e1;"><?php echo $stats['in_progress'] ?? 0; ?></div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #48bb78;">
                <span class="stat-label">Resolved</span>
                <div class="stat-number" style="color:#48bb78;"><?php echo $stats['resolved'] ?? 0; ?></div>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-top:30px; flex-wrap:wrap;">
            <a href="complaint-submit.php" class="btn-primary">Submit New Complaint</a>
            <a href="complaint-history.php" class="btn-secondary">View All Complaints</a>
        </div>
    </div>
</div>

</body>
</html>