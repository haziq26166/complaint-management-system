document.addEventListener("DOMContentLoaded", function() {
    // ======================
    // FULL NAME VALIDATION (Registration)
    // ======================
    const fullName = document.getElementById("full_name");
    const nameError = document.getElementById("nameError");
    
    if (fullName) {
        fullName.addEventListener("keyup", function() {
            const value = this.value;
            const isValid = /^[A-Za-z\s\-']+$/.test(value);
            
            if (value.length === 0) {
                nameError.innerHTML = "";
                nameError.style.color = "#718096";
            } else if (!isValid) {
                nameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                nameError.style.color = "red";
            } else {
                nameError.innerHTML = "✓ Valid name format";
                nameError.style.color = "green";
            }
        });
        
        fullName.addEventListener("blur", function() {
            const value = this.value.trim();
            if (value.length > 0 && !/^[A-Za-z\s\-']+$/.test(value)) {
                nameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                nameError.style.color = "red";
            }
        });
    }

    // ======================
    // PROFILE EDIT FULL NAME VALIDATION
    // ======================
    const profileFullName = document.getElementById("profile_full_name");
    const profileNameError = document.getElementById("profileNameError");

    if (profileFullName) {
        profileFullName.addEventListener("keyup", function() {
            const value = this.value;
            const isValid = /^[A-Za-z\s\-']+$/.test(value);
            
            if (value.length === 0) {
                profileNameError.innerHTML = "Enter your full name";
                profileNameError.style.color = "#718096";
            } else if (!isValid) {
                profileNameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                profileNameError.style.color = "red";
            } else {
                profileNameError.innerHTML = "✓ Valid name format";
                profileNameError.style.color = "green";
            }
        });
        
        profileFullName.addEventListener("blur", function() {
            const value = this.value.trim();
            if (value.length > 0 && !/^[A-Za-z\s\-']+$/.test(value)) {
                profileNameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                profileNameError.style.color = "red";
            }
        });
    }

    // ======================
    // ADMIN RESIDENT EDIT FULL NAME VALIDATION
    // ======================
    const adminFullName = document.getElementById("admin_full_name");
    const adminNameError = document.getElementById("adminNameError");

    if (adminFullName) {
        adminFullName.addEventListener("keyup", function() {
            const value = this.value;
            const isValid = /^[A-Za-z\s\-']+$/.test(value);
            
            if (value.length === 0) {
                adminNameError.innerHTML = "Enter resident's full name";
                adminNameError.style.color = "#718096";
            } else if (!isValid) {
                adminNameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                adminNameError.style.color = "red";
            } else {
                adminNameError.innerHTML = "✓ Valid name format";
                adminNameError.style.color = "green";
            }
        });
        
        adminFullName.addEventListener("blur", function() {
            const value = this.value.trim();
            if (value.length > 0 && !/^[A-Za-z\s\-']+$/.test(value)) {
                adminNameError.innerHTML = "❌ Name can only contain letters, spaces, hyphens, and apostrophes";
                adminNameError.style.color = "red";
            }
        });
    }

    // ======================
    // EMAIL VALIDATION (Registration)
    // ======================
    const email = document.getElementById("email");
    const emailError = document.getElementById("emailError");
    
    if (email) {
        email.addEventListener("keyup", function() {
            const value = this.value;
            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            
            if (value.length === 0) {
                emailError.innerHTML = "Email format: example@gmail.com";
                emailError.style.color = "#718096";
            } else if (!isValid) {
                emailError.innerHTML = "❌ Please enter a valid email address";
                emailError.style.color = "red";
            } else {
                emailError.innerHTML = "✓ Valid email address";
                emailError.style.color = "green";
            }
        });
    }

    // ======================
    // LOGIN EMAIL VALIDATION
    // ======================
    const loginEmail = document.getElementById("loginEmail");
    const loginEmailError = document.getElementById("loginEmailError");
    
    if (loginEmail) {
        loginEmail.addEventListener("keyup", function() {
            const value = this.value.trim();
            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            
            if (value.length === 0) {
                loginEmailError.innerHTML = "Enter your registered email address";
                loginEmailError.style.color = "#718096";
            } else if (!isValid) {
                loginEmailError.innerHTML = "❌ Please enter a valid email address (e.g., name@domain.com)";
                loginEmailError.style.color = "red";
            } else {
                loginEmailError.innerHTML = "✓ Valid email format";
                loginEmailError.style.color = "green";
            }
        });
        
        loginEmail.addEventListener("blur", function() {
            const value = this.value.trim();
            if (value.length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                loginEmailError.innerHTML = "❌ Please enter a valid email address (e.g., name@domain.com)";
                loginEmailError.style.color = "red";
            }
        });
    }

    // ======================
    // PHONE NUMBER VALIDATION (Registration)
    // ======================
    const phone = document.getElementById("phone_number");
    const phoneError = document.getElementById("phoneError");
    
    if (phone) {
        phone.addEventListener("keyup", function() {
            const numericValue = this.value.replace(/\D/g, '');
            const value = this.value;
            
            if (!/^\d*$/.test(value)) {
                this.value = numericValue;
                phoneError.innerHTML = "❌ Phone number can only contain numbers";
                phoneError.style.color = "red";
            } else if (value.length > 0 && value.length < 10) {
                phoneError.innerHTML = "❌ Phone number must be at least 10 digits";
                phoneError.style.color = "red";
            } else if (value.length >= 10 && value.length <= 15) {
                phoneError.innerHTML = "✓ Valid phone number";
                phoneError.style.color = "green";
            } else if (value.length > 15) {
                phoneError.innerHTML = "❌ Phone number cannot exceed 15 digits";
                phoneError.style.color = "red";
            } else {
                phoneError.innerHTML = "";
                phoneError.style.color = "#718096";
            }
        });
        
        phone.addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // ======================
    // PROFILE EDIT PHONE NUMBER VALIDATION
    // ======================
    const profilePhone = document.getElementById("profile_phone_number");
    const profilePhoneError = document.getElementById("profilePhoneError");

    if (profilePhone) {
        profilePhone.addEventListener("keyup", function() {
            const numericValue = this.value.replace(/\D/g, '');
            const value = this.value;
            
            if (!/^\d*$/.test(value)) {
                this.value = numericValue;
                profilePhoneError.innerHTML = "❌ Phone number can only contain numbers";
                profilePhoneError.style.color = "red";
            } else if (value.length > 0 && value.length < 10) {
                profilePhoneError.innerHTML = "❌ Phone number must be at least 10 digits";
                profilePhoneError.style.color = "red";
            } else if (value.length >= 10 && value.length <= 15) {
                profilePhoneError.innerHTML = "✓ Valid phone number";
                profilePhoneError.style.color = "green";
            } else if (value.length > 15) {
                profilePhoneError.innerHTML = "❌ Phone number cannot exceed 15 digits";
                profilePhoneError.style.color = "red";
            } else {
                profilePhoneError.innerHTML = "Enter 10-15 digits";
                profilePhoneError.style.color = "#718096";
            }
        });
        
        profilePhone.addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // ======================
    // ADMIN PHONE NUMBER VALIDATION
    // ======================
    const adminPhone = document.getElementById("admin_phone_number");
    const adminPhoneError = document.getElementById("adminPhoneError");

    if (adminPhone) {
        adminPhone.addEventListener("keyup", function() {
            const numericValue = this.value.replace(/\D/g, '');
            const value = this.value;
            
            if (!/^\d*$/.test(value)) {
                this.value = numericValue;
                adminPhoneError.innerHTML = "❌ Phone number can only contain numbers";
                adminPhoneError.style.color = "red";
            } else if (value.length > 0 && value.length < 10) {
                adminPhoneError.innerHTML = "❌ Phone number must be at least 10 digits";
                adminPhoneError.style.color = "red";
            } else if (value.length >= 10 && value.length <= 15) {
                adminPhoneError.innerHTML = "✓ Valid phone number";
                adminPhoneError.style.color = "green";
            } else if (value.length > 15) {
                adminPhoneError.innerHTML = "❌ Phone number cannot exceed 15 digits";
                adminPhoneError.style.color = "red";
            } else {
                adminPhoneError.innerHTML = "Enter 10-15 digits";
                adminPhoneError.style.color = "#718096";
            }
        });
        
        adminPhone.addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // ======================
    // PASSWORD VALIDATION
    // ======================
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const confirmPasswordError = document.getElementById("confirmPasswordError");
    
    if (password) {
        password.addEventListener("keyup", function() {
            let value = this.value;
            updateCheck("lengthCheck", value.length >= 8);
            updateCheck("upperCheck", /[A-Z]/.test(value));
            updateCheck("lowerCheck", /[a-z]/.test(value));
            updateCheck("numberCheck", /[0-9]/.test(value));
            updateCheck("specialCheck", /[^A-Za-z0-9]/.test(value));
            
            if (confirmPassword && confirmPassword.value.length > 0) {
                checkPasswordMatch();
            }
        });
    }
    
    if (confirmPassword) {
        confirmPassword.addEventListener("keyup", function() {
            checkPasswordMatch();
        });
    }
    
    function checkPasswordMatch() {
        if (!confirmPassword || !password) return;
        
        if (confirmPassword.value.length === 0) {
            confirmPasswordError.innerHTML = "";
            confirmPasswordError.style.color = "#718096";
        } else if (confirmPassword.value !== password.value) {
            confirmPasswordError.innerHTML = "❌ Passwords do not match";
            confirmPasswordError.style.color = "red";
        } else {
            confirmPasswordError.innerHTML = "✓ Passwords match";
            confirmPasswordError.style.color = "green";
        }
    }

    // ======================
    // APARTMENT NUMBER FORMAT
    // ======================
    const apartment = document.getElementById("apartment_no");
    if (apartment) {
        apartment.addEventListener("keyup", function() {
            this.value = this.value.toUpperCase();
        });
    }

    // ======================
    // PROFILE PAGE PASSWORD VALIDATION
    // ======================
    const newPassword = document.getElementById('new_password');
    const profileConfirmPassword = document.getElementById('confirm_password');
    const profileConfirmError = document.getElementById('confirmPasswordError');
    
    if (newPassword) {
        newPassword.addEventListener('keyup', function() {
            const value = this.value;
            updateCheck('lengthCheck', value.length >= 8);
            updateCheck('upperCheck', /[A-Z]/.test(value));
            updateCheck('lowerCheck', /[a-z]/.test(value));
            updateCheck('numberCheck', /[0-9]/.test(value));
            updateCheck('specialCheck', /[^A-Za-z0-9]/.test(value));
            
            if (profileConfirmPassword && profileConfirmPassword.value.length > 0) {
                checkProfilePasswordMatch();
            }
        });
    }
    
    if (profileConfirmPassword) {
        profileConfirmPassword.addEventListener('keyup', function() {
            checkProfilePasswordMatch();
        });
    }
    
    function checkProfilePasswordMatch() {
        if (!profileConfirmPassword || !newPassword) return;
        
        if (profileConfirmPassword.value.length === 0) {
            profileConfirmError.innerHTML = '';
            profileConfirmError.style.color = '#718096';
        } else if (profileConfirmPassword.value !== newPassword.value) {
            profileConfirmError.innerHTML = '❌ Passwords do not match';
            profileConfirmError.style.color = 'red';
        } else {
            profileConfirmError.innerHTML = '✓ Passwords match';
            profileConfirmError.style.color = 'green';
        }
    }
});

// ======================
// HELPER FUNCTIONS
// ======================

function updateCheck(id, valid) {
    let element = document.getElementById(id);
    if (!element) return;
    
    if (valid) {
        element.style.color = "green";
        element.innerHTML = element.innerHTML.replace("❌", "✓");
    } else {
        element.style.color = "red";
        element.innerHTML = element.innerHTML.replace("✓", "❌");
    }
}

// ======================
// REGISTER VALIDATION
// ======================

function validateRegister() {
    // Full Name Validation
    let fullName = document.getElementById("full_name").value.trim();
    if (!/^[A-Za-z\s\-']+$/.test(fullName)) {
        alert("Name can only contain letters, spaces, hyphens, and apostrophes");
        document.getElementById("full_name").focus();
        return false;
    }
    if (fullName.length < 2) {
        alert("Name must be at least 2 characters");
        document.getElementById("full_name").focus();
        return false;
    }

    // Email Validation
    let email = document.getElementById("email").value.trim();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address");
        document.getElementById("email").focus();
        return false;
    }

    // Phone Validation
    let phone = document.getElementById("phone_number").value.trim();
    if (!/^\d{10,15}$/.test(phone)) {
        alert("Phone number must be 10-15 digits only");
        document.getElementById("phone_number").focus();
        return false;
    }

    // Apartment Validation
    let apartment = document.getElementById("apartment_no").value.trim();
    if (apartment.length === 0) {
        alert("Please enter your apartment/unit number");
        document.getElementById("apartment_no").focus();
        return false;
    }

    // Password Validation
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    
    if (password.length < 8) {
        alert("Password must be at least 8 characters");
        document.getElementById("password").focus();
        return false;
    }
    if (!/[A-Z]/.test(password)) {
        alert("Password must contain an uppercase letter");
        document.getElementById("password").focus();
        return false;
    }
    if (!/[a-z]/.test(password)) {
        alert("Password must contain a lowercase letter");
        document.getElementById("password").focus();
        return false;
    }
    if (!/[0-9]/.test(password)) {
        alert("Password must contain a number");
        document.getElementById("password").focus();
        return false;
    }
    if (!/[^A-Za-z0-9]/.test(password)) {
        alert("Password must contain a special character");
        document.getElementById("password").focus();
        return false;
    }
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        document.getElementById("confirm_password").focus();
        return false;
    }
    
    return true;
}

// ======================
// LOGIN VALIDATION
// ======================

function validateLogin() {
    let email = document.getElementById("loginEmail").value.trim();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address (e.g., name@domain.com)");
        document.getElementById("loginEmail").focus();
        return false;
    }
    
    let password = document.getElementById("loginPassword").value;
    if (password.length === 0) {
        alert("Please enter your password");
        document.getElementById("loginPassword").focus();
        return false;
    }
    
    return true;
}

// ======================
// PROFILE EDIT VALIDATION
// ======================

function validateProfileEdit() {
    // Full Name Validation
    let fullName = document.getElementById("profile_full_name").value.trim();
    if (!/^[A-Za-z\s\-']+$/.test(fullName)) {
        alert("Name can only contain letters, spaces, hyphens, and apostrophes");
        document.getElementById("profile_full_name").focus();
        return false;
    }
    if (fullName.length < 2) {
        alert("Name must be at least 2 characters");
        document.getElementById("profile_full_name").focus();
        return false;
    }

    // Phone Validation
    let phone = document.getElementById("profile_phone_number").value.trim();
    if (!/^\d{10,15}$/.test(phone)) {
        alert("Phone number must be 10-15 digits only");
        document.getElementById("profile_phone_number").focus();
        return false;
    }

    return true;
}

// ======================
// ADMIN RESIDENT EDIT VALIDATION
// ======================

function validateAdminResidentEdit() {
    // Full Name Validation
    let fullName = document.getElementById("admin_full_name").value.trim();
    if (!/^[A-Za-z\s\-']+$/.test(fullName)) {
        alert("Name can only contain letters, spaces, hyphens, and apostrophes");
        document.getElementById("admin_full_name").focus();
        return false;
    }
    if (fullName.length < 2) {
        alert("Name must be at least 2 characters");
        document.getElementById("admin_full_name").focus();
        return false;
    }

    // Phone Validation
    let phone = document.getElementById("admin_phone_number").value.trim();
    if (!/^\d{10,15}$/.test(phone)) {
        alert("Phone number must be 10-15 digits only");
        document.getElementById("admin_phone_number").focus();
        return false;
    }

    return true;
}

// ======================
// PASSWORD CHANGE VALIDATION
// ======================

function validatePasswordChange() {
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (!currentPassword.value.trim()) {
        alert('Please enter your current password.');
        currentPassword.focus();
        return false;
    }
    
    if (!newPassword.value.trim()) {
        alert('Please enter a new password.');
        newPassword.focus();
        return false;
    }
    
    if (!confirmPassword.value.trim()) {
        alert('Please confirm your new password.');
        confirmPassword.focus();
        return false;
    }
    
    const password = newPassword.value;
    if (password.length < 8) {
        alert('Password must be at least 8 characters.');
        newPassword.focus();
        return false;
    }
    if (!/[A-Z]/.test(password)) {
        alert('Password must contain an uppercase letter.');
        newPassword.focus();
        return false;
    }
    if (!/[a-z]/.test(password)) {
        alert('Password must contain a lowercase letter.');
        newPassword.focus();
        return false;
    }
    if (!/[0-9]/.test(password)) {
        alert('Password must contain a number.');
        newPassword.focus();
        return false;
    }
    if (!/[^A-Za-z0-9]/.test(password)) {
        alert('Password must contain a special character.');
        newPassword.focus();
        return false;
    }
    
    if (newPassword.value !== confirmPassword.value) {
        alert('New passwords do not match.');
        confirmPassword.focus();
        return false;
    }
    
    if (newPassword.value === currentPassword.value) {
        alert('New password must be different from current password.');
        newPassword.focus();
        return false;
    }
    
    return true;
}

function togglePassword(id) {
    let input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}