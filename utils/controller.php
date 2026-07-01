<?php
session_start();
require_once 'db.php';
require_once 'mailer.php';

// ======================
// REGISTER USER (Resident)
// ======================
if (isset($_POST['register'])) {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $apartment = mysqli_real_escape_string($conn, trim($_POST['apartment_no']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Validate full name
    if (!preg_match("/^[A-Za-z\s\-']+$/", $full_name)) {
        echo "<script>alert('Name can only contain letters, spaces, hyphens, and apostrophes'); window.location='../auth/register.html';</script>";
        exit();
    }
    
    // Validate phone number
    if (!preg_match("/^\d{10,15}$/", $phone)) {
        echo "<script>alert('Phone number must be 10-15 digits only'); window.location='../auth/register.html';</script>";
        exit();
    }
    
    // Check if email exists in resident table
    $check = mysqli_query($conn, "SELECT residentID FROM resident WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered!'); window.location='../auth/register.html';</script>";
        exit();
    }
    
    $sql = "INSERT INTO resident (full_name, email, phone_number, password, apartment_no) 
            VALUES ('$full_name', '$email', '$phone', '$password', '$apartment')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration successful! Please login.'); window.location='../auth/login.html';</script>";
    } else {
        echo "<script>alert('Registration failed: " . mysqli_error($conn) . "'); window.location='../auth/register.html';</script>";
    }
    exit();
}

// ======================
// LOGIN USER (Resident or Staff)
// ======================
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    
    // Validate email format before querying
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address.'); window.location='../auth/login.html';</script>";
        exit();
    }
    
    // Check staff table
    $staff_check = mysqli_query($conn, "SELECT * FROM staff WHERE email='$email'");
    $staff_count = mysqli_num_rows($staff_check);
    
    // Check resident table
    $resident_check = mysqli_query($conn, "SELECT * FROM resident WHERE email='$email'");
    $resident_count = mysqli_num_rows($resident_check);
    
    // FIRST: Check staff table
    if ($staff_count > 0) {
        $staff = mysqli_fetch_assoc($staff_check);
        if (password_verify($password, $staff['password'])) {
            // Staff/Admin login
            $_SESSION['staff_id'] = (int)$staff['staffID'];
            $_SESSION['full_name'] = $staff['full_name'];
            $_SESSION['email'] = $staff['email'];
            $_SESSION['user_type'] = 'staff';
            $_SESSION['role'] = $staff['role'] ?? 'staff';
            $_SESSION['LAST_ACTIVITY'] = time();
            
            header("Location: ../component/admin/dashboard-admin.php");
            exit();
        }
    }
    
    // SECOND: Check resident table
    if ($resident_count > 0) {
        $resident = mysqli_fetch_assoc($resident_check);
        
        // Check if resident account is active
        if ($resident['resident_status'] !== 'active') {
            echo "<script>alert('Your account has been deactivated. Please contact the administrator.'); window.location='../auth/login.html';</script>";
            exit();
        }
        
        if (password_verify($password, $resident['password'])) {
            // Resident login
            $_SESSION['residentID'] = (int)$resident['residentID'];
            $_SESSION['full_name'] = $resident['full_name'];
            $_SESSION['email'] = $resident['email'];
            $_SESSION['user_type'] = 'resident';
            $_SESSION['LAST_ACTIVITY'] = time();
            
            header("Location: ../component/resident/dashboard-resident.php");
            exit();
        }
    }
    
    // If neither works, show error
    echo "<script>alert('Invalid email or password. Please try again.'); window.location='../auth/login.html';</script>";
    exit();
}

// ======================
// FORGOT PASSWORD
// ======================
if (isset($_POST['forgot_password'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['reset_email']));
    
    // Check both resident and staff tables
    $resident = mysqli_query($conn, "SELECT * FROM resident WHERE email='$email'");
    $staff = mysqli_query($conn, "SELECT * FROM staff WHERE email='$email'");
    
    if (mysqli_num_rows($resident) == 1 || mysqli_num_rows($staff) == 1) {
        $token = bin2hex(random_bytes(32));
        
        // Try to update resident first, then staff
        mysqli_query($conn, "UPDATE resident SET reset_token='$token' WHERE email='$email'");
        if (mysqli_affected_rows($conn) == 0) {
            mysqli_query($conn, "UPDATE staff SET reset_token='$token' WHERE email='$email'");
        }
        
        // Send email with reset link
        $resetLink = "http://localhost/rcms/auth/reset-password.php?token=$token";
        $body = "
            <h2>Password Reset Request</h2>
            <p>Click the link below to reset your password:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>This link will expire in 24 hours.</p>
        ";
        sendMail($email, "User", "Password Reset", $body);
        
        echo "<script>alert('Password reset link sent to your email.'); window.location='../auth/login.html';</script>";
    } else {
        echo "<script>alert('Email not found.'); window.location='../auth/forgot-password.php';</script>";
    }
    exit();
}

// ======================
// RESET PASSWORD
// ======================
if (isset($_POST['reset_password'])) {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Try to update resident first, then staff
    mysqli_query($conn, "UPDATE resident SET password='$new_password', reset_token=NULL WHERE reset_token='$token'");
    if (mysqli_affected_rows($conn) == 0) {
        mysqli_query($conn, "UPDATE staff SET password='$new_password', reset_token=NULL WHERE reset_token='$token'");
    }
    
    echo "<script>alert('Password reset successful! Please login.'); window.location='../auth/login.html';</script>";
    exit();
}

// ======================
// SUBMIT COMPLAINT (Resident)
// ======================
if (isset($_POST['submit_complaint'])) {
    if (!isset($_SESSION['residentID'])) {
        echo "<script>alert('Please login first.'); window.location='../auth/login.html';</script>";
        exit();
    }
    
    $residentID = (int)$_SESSION['residentID'];
    $category_id = (int)$_POST['category_id'];
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? 'Complaint'));
    
    $query = "INSERT INTO complaint (name, description, created_date, categoryID, residentID)
              VALUES ('$title', '$description', NOW(), $category_id, $residentID)";
    
    if (mysqli_query($conn, $query)) {
        $complaint_id = mysqli_insert_id($conn);
        
        // Get first staff member as default assigned
        $staff_result = mysqli_query($conn, "SELECT staffID FROM staff LIMIT 1");
        $staff_id = $staff_result ? mysqli_fetch_assoc($staff_result)['staffID'] : 1;
        
        mysqli_query($conn, "
            INSERT INTO status_complaint (staffID, complaintID, status_name, priority, assigned_to, noted, updated_date)
            VALUES ($staff_id, $complaint_id, 'Pending', 'Medium', 'Unassigned', 'Complaint submitted', NOW())
        ");
        
        echo "<script>alert('Complaint submitted successfully!'); window.location='../component/resident/complaint-history.php';</script>";
    } else {
        echo "<script>alert('Failed to submit complaint: " . mysqli_error($conn) . "'); window.location='../component/resident/complaint-submit.php';</script>";
    }
    exit();
}

// ======================
// UPDATE COMPLAINT (Admin)
// ======================
if (isset($_POST['update_complaint'])) {
    $complaint_id = (int)$_POST['complaint_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $assigned_to = mysqli_real_escape_string($conn, trim($_POST['assigned_to']));
    $note = mysqli_real_escape_string($conn, trim($_POST['update_note'] ?? ''));
    
    // Get staff ID from session
    $staff_id = isset($_SESSION['staff_id']) ? (int)$_SESSION['staff_id'] : 1;
    
    $sql = "UPDATE status_complaint 
            SET status_name = '$status', 
                priority = '$priority', 
                assigned_to = '$assigned_to', 
                staffID = $staff_id,
                updated_date = NOW()";
    
    if ($note !== '') {
        $staff_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Admin';
        $sql .= ", noted = CONCAT(IFNULL(noted, ''), '\n[" . date('Y-m-d H:i') . "] $staff_name: $note')";
    }
    
    $sql .= " WHERE complaintID = $complaint_id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Complaint updated successfully!'); window.location='../component/admin/complaint-manage.php?id=$complaint_id';</script>";
    } else {
        echo "<script>alert('Failed to update complaint.'); window.location='../component/admin/complaint-manage.php?id=$complaint_id';</script>";
    }
    exit();
}

// ======================
// ADD CATEGORY (Admin)
// ======================
if (isset($_POST['add_category_submit'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $sql = "INSERT INTO category (name, description) VALUES ('$name', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Category added successfully!'); window.location='../component/admin/categories-list.php';</script>";
    } else {
        echo "<script>alert('Failed to add category.'); window.location='../component/admin/categories-add.php';</script>";
    }
    exit();
}

// ======================
// EDIT CATEGORY (Admin)
// ======================
if (isset($_POST['edit_category_submit'])) {
    $category_id = (int)$_POST['category_id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $sql = "UPDATE category SET name='$name', description='$description' WHERE categoryID=$category_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Category updated successfully!'); window.location='../component/admin/categories-list.php';</script>";
    } else {
        echo "<script>alert('Failed to update category.'); window.location='../component/admin/categories-edit.php?id=$category_id';</script>";
    }
    exit();
}

// ======================
// DELETE CATEGORY (Admin)
// ======================
if (isset($_POST['delete_category'])) {
    $category_id = (int)$_POST['category_id'];
    
    $check = mysqli_query($conn, "SELECT COUNT(*) as count FROM complaint WHERE categoryID=$category_id");
    $row = mysqli_fetch_assoc($check);
    if ($row['count'] > 0) {
        echo "<script>alert('Cannot delete category. It is being used by complaints.'); window.location='../component/admin/categories-list.php';</script>";
    } else {
        $sql = "DELETE FROM category WHERE categoryID=$category_id";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Category deleted successfully!'); window.location='../component/admin/categories-list.php';</script>";
        } else {
            echo "<script>alert('Failed to delete category.'); window.location='../component/admin/categories-list.php';</script>";
        }
    }
    exit();
}

// ======================
// UPDATE RESIDENT (Admin)
// ======================
if (isset($_POST['update_resident'])) {
    $resident_id = (int)$_POST['resident_id'];
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $apartment = mysqli_real_escape_string($conn, trim($_POST['apartment_no']));
    $resident_status = mysqli_real_escape_string($conn, $_POST['resident_status']);
    
    // Validate full name (only letters, spaces, hyphens, apostrophes)
    if (!preg_match("/^[A-Za-z\s\-']+$/", $full_name)) {
        echo "<script>alert('Full name can only contain letters, spaces, hyphens, and apostrophes.'); window.location='../component/admin/resident-edit.php?id=$resident_id';</script>";
        exit();
    }
    
    // Validate phone number (only digits, 10-15 chars)
    if (!preg_match("/^\d{10,15}$/", $phone)) {
        echo "<script>alert('Phone number must be 10-15 digits only.'); window.location='../component/admin/resident-edit.php?id=$resident_id';</script>";
        exit();
    }
    
    // Validate status
    if (!in_array($resident_status, ['active', 'inactive'])) {
        $resident_status = 'active';
    }
    
    $check = mysqli_query($conn, "SELECT residentID FROM resident WHERE email='$email' AND residentID!=$resident_id");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already used by another resident.'); window.location='../component/admin/resident-edit.php?id=$resident_id';</script>";
        exit();
    }
    
    $sql = "UPDATE resident 
            SET full_name='$full_name', 
                email='$email', 
                phone_number='$phone', 
                apartment_no='$apartment',
                resident_status='$resident_status'
            WHERE residentID=$resident_id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Resident updated successfully!'); window.location='../component/admin/resident-list.php';</script>";
    } else {
        echo "<script>alert('Failed to update resident.'); window.location='../component/admin/resident-edit.php?id=$resident_id';</script>";
    }
    exit();
}

// If no action matched, redirect to home
header("Location: ../index.html");
exit();
?>