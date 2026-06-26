<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

include 'db.php';
require_once 'mailer.php';

// ============================
// REGISTER
// ============================
if(isset($_POST['register'])){
    $full_name     = trim($_POST['full_name']);
    $email         = trim($_POST['email']);
    $phone_number  = trim($_POST['phone_number']);
    $apartment_no  = trim($_POST['apartment_no']);
    $password_raw  = $_POST['password'];

    // SERVER SIDE VALIDATION
    if(empty($full_name)){
        die("Full Name is required");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid Email Format");
    }
    if(strlen($password_raw) < 8){
        die("Password must be at least 8 characters");
    }

    // CHECK DUPLICATE EMAIL
    $checkEmail = mysqli_query($conn, "SELECT * FROM resident WHERE email='$email'");
    if(mysqli_num_rows($checkEmail) > 0){
        echo "<script>alert('Email already registered'); window.location.href = '../auth/register.html';</script>";
        exit();
    }

    // HASH PASSWORD
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // INSERT DATA
    $sql = "INSERT INTO resident (full_name, email, phone_number, password, apartment_no) VALUES ('$full_name', '$email', '$phone_number', '$password', '$apartment_no')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Registration successful!'); window.location.href = '../auth/login.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error registering user'); window.location.href = '../auth/register.html';</script>";
        exit();
    }
}

// ============================
// LOGIN
// ============================
if(isset($_POST['login'])){
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM resident WHERE email='$email'");

    if(mysqli_num_rows($query) == 1){
        $row = mysqli_fetch_assoc($query);

        if(password_verify($password, $row['password'])){
            $_SESSION['residentID'] = $row['residentID'];
            $_SESSION['full_name']  = $row['full_name'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['LAST_ACTIVITY'] = time();

            // Since login forms submit from /auth/, step out one level to find index.html
            header("Location: ../component/resident/dashboard-resident.php");
        } else {
            echo "<script>alert('Incorrect Password'); window.location.href = '../auth/login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User Not Found'); window.location.href = '../auth/login.html';</script>";
        exit();
    }
}

// ============================
// FORGOT PASSWORD
// ============================
if(isset($_POST['forgot_password'])){

    $email = mysqli_real_escape_string($conn, trim($_POST['reset_email']));

    if(empty($email)){
        echo "<script>
        alert('Email is required.');
        window.location.href='../auth/forgot-password.php';
        </script>";
        exit();
    }

    $query = mysqli_query($conn,
        "SELECT * FROM resident WHERE email='$email'");

    // Sentiasa papar mesej yang sama (lebih selamat)
    if(mysqli_num_rows($query) == 1){

        $resident = mysqli_fetch_assoc($query);

        // Generate token
        $token = bin2hex(random_bytes(32));

        // Sah selama 15 minit
        $expiry = date(
            "Y-m-d H:i:s",
            strtotime("+15 minutes")
        );

        mysqli_query($conn,
            "UPDATE resident
             SET reset_token='$token',
                 token_expiry='$expiry'
             WHERE residentID='".$resident['residentID']."'"
        );

        $link =
        "http://localhost/complaint-management-system/auth/reset-password.php?token=".$token;

        $subject = "Reset Password";

        $body = "
        <h2>Residential Complaint Management System</h2>

        <p>Hello ".$resident['full_name'].",</p>

        <p>You requested to reset your password.</p>

        <p>
        Click the link below to continue:
        </p>

        <a href='$link'>
        Reset Password
        </a>

        <br><br>

        <small>
        This link will expire in 15 minutes.
        </small>";

        sendMail(
            $resident['email'],
            $resident['full_name'],
            $subject,
            $body
        );

    }

    echo "<script>
    alert('If the email exists in our system, a password reset link has been sent.');
    window.location.href='../auth/login.html';
    </script>";

    exit();
}

// ============================
// RESET PASSWORD
// ============================
if(isset($_POST['reset_password'])){

    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password !== $confirm_password){

        echo "<script>
        alert('Password does not match.');
        window.history.back();
        </script>";
        exit();

    }

    if(strlen($new_password) < 8){

        echo "<script>
        alert('Password must be at least 8 characters.');
        window.history.back();
        </script>";
        exit();

    }

    $hash = password_hash($new_password, PASSWORD_DEFAULT);

    $update = mysqli_query($conn,
        "UPDATE resident
         SET
            password='$hash',
            reset_token=NULL,
            token_expiry=NULL
         WHERE
            reset_token='$token'
            AND token_expiry > NOW()"
    );

    if(mysqli_affected_rows($conn) > 0){

        echo "<script>
        alert('Password has been reset successfully.');
        window.location.href='../auth/login.html';
        </script>";

    }else{

        echo "<script>
        alert('Reset link is invalid or has expired.');
        window.location.href='../auth/forgot-password.php';
        </script>";

    }

    exit();
}

// ========================================================
// COMPLAINT LIST MODULE (For Admin View Data Management)
// ========================================================

// Check if the file calling this code is complaint-list.php
if (basename($_SERVER['PHP_SELF']) == 'complaint-list.php') {
    
    // 1. Fetch categories for selection dropdowns
    $categories = [];
    $catQuery = mysqli_query($conn, "SELECT categoryID, name FROM category");
    while ($catRow = mysqli_fetch_assoc($catQuery)) {
        $categories[] = $catRow;
    }

    // 2. Identify drop-down filter selection parameters
    $status_filter   = isset($_GET['status']) ? trim($_GET['status']) : 'All';
    $category_filter = isset($_GET['category']) ? trim($_GET['category']) : 'All';
    $priority_filter = isset($_GET['priority']) ? trim($_GET['priority']) : 'All';

    // 3. Construct base query string linking schema elements
    $sql = "SELECT 
                c.complaintID, 
                c.name AS complaint_title, 
                c.created_date,
                r.full_name AS resident_name, 
                r.apartment_no, 
                cat.name AS category_name,
                sc.status_name, 
                sc.priority, 
                sc.assigned_to
            FROM complaint c
            LEFT JOIN resident r ON c.residentID = r.residentID
            LEFT JOIN category cat ON c.categoryID = cat.categoryID
            LEFT JOIN status_complaint sc ON c.statusID = sc.statusID
            WHERE 1=1";

    // Dynamic Filter Sanitization and Parameter Building
    if ($status_filter !== 'All') {
        $safe_status = mysqli_real_escape_string($conn, $status_filter);
        $sql .= " AND sc.status_name = '$safe_status'";
    }

    if ($category_filter !== 'All') {
        $safe_category = mysqli_real_escape_string($conn, $category_filter);
        $sql .= " AND c.categoryID = '$safe_category'";
    }

    if ($priority_filter !== 'All') {
        $safe_priority = mysqli_real_escape_string($conn, $priority_filter);
        $sql .= " AND sc.priority = '$safe_priority'";
    }

    $sql .= " ORDER BY c.complaintID ASC";

    // 4. Run structured results collection
    $complaints = [];
    $complaintQuery = mysqli_query($conn, $sql);
    
    if ($complaintQuery) {
        while ($row = mysqli_fetch_assoc($complaintQuery)) {
            $complaints[] = $row;
        }
    }
}

// ========================================================
// COMPLAINT MANAGEMENT MODULE (Fetch Single Record Details)
// ========================================================
if (basename($_SERVER['PHP_SELF']) == 'complaint-manage.php') {
    
    // 1. Grab the tracking ID from the browser address bar query string (?id=X)
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "<script>alert('No complaint ID provided.'); window.location.href = 'complaint-list.php';</script>";
        exit();
    }
    
    $complaint_id = intval($_GET['id']);
    
    // 2. Fetch the structural data matching your database schema layout
    $sql = "SELECT 
                c.complaintID, 
                c.name AS complaint_title, 
                c.description,
                c.created_date,
                r.full_name AS resident_name, 
                r.apartment_no, 
                cat.name AS category_name,
                sc.status_name, 
                sc.priority, 
                sc.assigned_to,
                sc.updated_date
            FROM complaint c
            LEFT JOIN resident r ON c.residentID = r.residentID
            LEFT JOIN category cat ON c.categoryID = cat.categoryID
            LEFT JOIN status_complaint sc ON c.statusID = sc.statusID
            WHERE c.complaintID = $complaint_id";
            
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $complaint = mysqli_fetch_assoc($result);
        
        // Default fallbacks in case table cells are empty or null
        if (empty($complaint['priority'])) $complaint['priority'] = 'Low';
        if (empty($complaint['status_name'])) $complaint['status_name'] = 'Pending';
        if (empty($complaint['updated_date'])) $complaint['updated_date'] = $complaint['created_date'];
        
    } else {
        echo "<script>alert('Complaint record not found.'); window.location.href = 'complaint-list.php';</script>";
        exit();
    }

    // 3. Optional: Fetch timeline logs or comment entries if you maintain them
    $notes = []; // Kept empty for now so your template loop runs cleanly without errors
}

// ========================================================
// CATEGORIES LIST MODULE (For categories-list.php)
// ========================================================
if (basename($_SERVER['PHP_SELF']) == 'categories-list.php') {
    
    // Construct database query selection statement
    $sql = "SELECT categoryID, name, description FROM category ORDER BY categoryID ASC";
    $categoryQuery = mysqli_query($conn, $sql);
    
    $categories_list = [];
    if ($categoryQuery) {
        while ($row = mysqli_fetch_assoc($categoryQuery)) {
            $categories_list[] = $row;
        }
    }
}

// ========================================================
// ADD CATEGORY PROCESSOR (For categories-add.php)
// ========================================================
if (isset($_POST['add_category_submit'])) {
    $name        = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    if (empty($name)) {
        echo "<script>alert('Category name is required.'); window.history.back();</script>";
        exit();
    }

    // Insert record statement query execution
    $sql = "INSERT INTO category (name, description) VALUES ('$name', '$description')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Category added successfully!'); window.location.href = 'categories-list.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error creating category.'); window.history.back();</script>";
        exit();
    }
}

// ========================================================
// EDIT CATEGORY PROCESSOR (For categories-edit.php)
// ========================================================

// 1. Fetch data for the selected category when page loads
if (basename($_SERVER['PHP_SELF']) == 'categories-edit.php') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "<script>alert('No category ID provided.'); window.location.href = 'categories-list.php';</script>";
        exit();
    }

    $category_id = intval($_GET['id']);
    $editQuery = mysqli_query($conn, "SELECT * FROM category WHERE categoryID = $category_id");

    if ($editQuery && mysqli_num_rows($editQuery) == 1) {
        $category_data = mysqli_fetch_assoc($editQuery);
    } else {
        echo "<script>alert('Category not found.'); window.location.href = 'categories-list.php';</script>";
        exit();
    }
}

// 2. Handle form submission to update changes
if (isset($_POST['edit_category_submit'])) {
    $category_id = intval($_POST['category_id']);
    $name        = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    if (empty($name)) {
        echo "<script>alert('Category name cannot be empty.'); window.history.back();</script>";
        exit();
    }

    $sql = "UPDATE category SET name = '$name', description = '$description' WHERE categoryID = $category_id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Category updated successfully!'); window.location.href = 'categories-list.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating category.'); window.history.back();</script>";
        exit();
    }
}

// ========================================================
// DELETE CATEGORY PROCESSOR
// ========================================================
if (isset($_POST['delete_category'])) {
    $category_id = intval($_POST['category_id']);

    // Optional but highly recommended: Check if any complaints are currently using this category
    $checkUsage = mysqli_query($conn, "SELECT complaintID FROM complaint WHERE categoryID = $category_id LIMIT 1");
    if (mysqli_num_rows($checkUsage) > 0) {
        // Updated redirect path: step out of utils/ and into component/admin/
        echo "<script>alert('Cannot delete category. There are existing complaints tied to this category!'); window.location.href = '../component/admin/categories-list.php';</script>";
        exit();
    }

    // Execute the deletion
    $sql = "DELETE FROM category WHERE categoryID = $category_id";
    
    if (mysqli_query($conn, $sql)) {
        // Updated redirect path: step out of utils/ and into component/admin/
        echo "<script>alert('Category deleted successfully!'); window.location.href = '../component/admin/categories-list.php';</script>";
        exit();
    } else {
        // Updated redirect path: step out of utils/ and into component/admin/
        echo "<script>alert('Error deleting category.'); window.location.href = '../component/admin/categories-list.php';</script>";
        exit();
    }
}

?>