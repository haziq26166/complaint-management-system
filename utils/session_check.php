<?php
// Session timeout and authentication check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/db.php';

$timeout = 120; // 2 minutes

if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout) {
        session_unset();
        session_destroy();
        header("Location: ../../auth/login.html?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['residentID']) || isset($_SESSION['staff_id']);
}

// Check if user is resident
function isResident() {
    return isset($_SESSION['residentID']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'resident';
}

// Check if user is staff/admin
function isStaff() {
    return isset($_SESSION['staff_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'staff';
}

// Check if user is admin
function isAdmin() {
    return isStaff() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check if resident account is active
function isResidentActive($residentId) {
    global $conn;
    if (!$conn) return false;
    
    $result = mysqli_query($conn, "SELECT resident_status FROM resident WHERE residentID = $residentId");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['resident_status'] === 'active';
    }
    return false;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../../auth/login.html");
        exit();
    }
    
    // Check if resident is active
    if (isset($_SESSION['residentID']) && !isResidentActive($_SESSION['residentID'])) {
        session_unset();
        session_destroy();
        header("Location: ../../auth/login.html?inactive=1");
        exit();
    }
}

// Redirect if not resident
function requireResident() {
    if (!isResident()) {
        header("Location: ../../auth/login.html");
        exit();
    }
}

// Redirect if not staff/admin
function requireStaff() {
    if (!isStaff()) {
        header("Location: ../../auth/login.html");
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../../auth/login.html");
        exit();
    }
}

// Escape output
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Get status badge class
function getStatusBadgeClass($status) {
    $status = strtolower(trim($status));
    switch ($status) {
        case 'pending':
            return 'badge-pending';
        case 'in progress':
            return 'badge-progress';
        case 'resolved':
            return 'badge-resolved';
        default:
            return 'badge-pending';
    }
}

// Get priority badge class
function getPriorityBadgeClass($priority) {
    $priority = strtolower(trim($priority));
    switch ($priority) {
        case 'high':
            return 'badge-high';
        case 'medium':
            return 'badge-medium';
        case 'low':
            return 'badge-low';
        default:
            return 'badge-low';
    }
}

// Get complaint counts for a resident
function getComplaintStats($residentId) {
    global $conn;
    
    $stats = [
        'total' => 0,
        'pending' => 0,
        'in_progress' => 0,
        'resolved' => 0
    ];
    
    if (!$conn) {
        return $stats;
    }
    
    $result = mysqli_query($conn, "
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN s.status_name = 'Pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN s.status_name = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN s.status_name = 'Resolved' THEN 1 ELSE 0 END) as resolved
        FROM complaint c
        LEFT JOIN status_complaint s ON c.complaintID = s.complaintID
        WHERE c.residentID = $residentId
    ");
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $stats = $row;
        }
    }
    
    return $stats;
}

// Get staff name by ID
function getStaffName($staffId) {
    global $conn;
    if (!$conn) return 'Unknown';
    
    $result = mysqli_query($conn, "SELECT full_name FROM staff WHERE staffID = $staffId");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['full_name'];
    }
    return 'Unknown';
}
?>