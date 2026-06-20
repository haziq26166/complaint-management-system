document.addEventListener("DOMContentLoaded", function(){ 

const email = 
document.getElementById("email"); 

const password = 
document.getElementById("password"); 

const confirmPassword = 
document.getElementById("confirm_password"); 

// EMAIL VALIDATION 

if(email){ 
email.addEventListener("keyup", function(){ 

let emailError = 
document.getElementById("emailError"); 

let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

if(pattern.test(this.value)){ 

emailError.innerHTML = "✓ Valid Email Format"; 
emailError.style.color = "green"; 
} 

else{ 
emailError.innerHTML = "❌ Invalid Email Format"; 
emailError.style.color = "red"; } }); } 

// PASSWORD VALIDATION 
if(password){ 

password.addEventListener("keyup", function(){ 
let value = this.value; 
updateCheck( "lengthCheck", value.length >= 8 
    ); 
updateCheck( "upperCheck", /[A-Z]/.test(value) 
); 

updateCheck( "lowerCheck", /[a-z]/.test(value) 
); 

updateCheck( "numberCheck", /[0-9]/.test(value) 
); updateCheck( "specialCheck", /[^A-Za-z0-9]/.test(value) 

); 
}); 
} 

// CONFIRM PASSWORD 
if(confirmPassword){ 
    confirmPassword.addEventListener("keyup", function(){ 
        let confirmPasswordError = document.getElementById( 
            "confirmPasswordError" 
            ); 

        if(this.value !== password.value){ 
            confirmPasswordError.innerHTML = "❌ Password does not match"; 

            confirmPasswordError.style.color = "red"; } 

            else{ confirmPasswordError.innerHTML = "✓ Password Matched"; 

            confirmPasswordError.style.color = "green"; 
        } 
    }); 
} 
}); 

function updateCheck(id, valid){ 
    let element = document.getElementById(id); 

    if(valid){ element.style.color = "green"; 
    element.innerHTML = element.innerHTML.replace( "❌", "✓" 
        ); 
} 

else{ 
    element.style.color = "red"; 
    element.innerHTML = element.innerHTML.replace( "✓", "❌" 
        ); 
} 
} 
function validateRegister(){ 
    let password = document.getElementById( "password" ).value; 
    let confirmPassword = document.getElementById( "confirm_password" ).value; 

    if(password.length < 8){ alert( "Password must be at least 8 characters" ); 

    return false; 
} 

if(!/[A-Z]/.test(password)){ 
    alert( "Password must contain an uppercase letter" 
        ); 

    return false; 
} 

if(!/[a-z]/.test(password)){ 
    alert( "Password must contain a lowercase letter" ); 

    return false; 
} 

if(!/[0-9]/.test(password)){ 
    alert( "Password must contain a number" ); 

    return false; 
} 

if(!/[^A-Za-z0-9]/.test(password)){ 
    alert( "Password must contain a special character" 
        ); 

    return false; 
} 

if(password !== confirmPassword){ 
    alert( "Password does not match" 
        ); 

    return false; 
} 

return true; 
}