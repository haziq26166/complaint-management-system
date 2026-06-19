document.addEventListener("DOMContentLoaded", function(){ 
    const password = 
    document.getElementById("password"); 

    const confirmPassword = 
    document.getElementById("confirm_password"); 

    if(password){ 
        password.addEventListener("keyup", function(){ 

            let passwordError = 
            document.getElementById("passwordError"); 

            if(this.value.length < 8){ 
                passwordError.innerHTML = "Password must be at least 8 characters"; 
                passwordError.style.color = "red"; } 

            else{ 
                passwordError.innerHTML = "✓ Valid Password"; 
                passwordError.style.color = "green"; 
            } 
        }); 
    } 
    if(confirmPassword){ 
        confirmPassword.addEventListener("keyup", function(){ 
            let confirmPasswordError = 
            document.getElementById("confirmPasswordError"); 

            if(this.value !== password.value){ 
                confirmPasswordError.innerHTML = "Password does not match"; 
                confirmPasswordError.style.color = "red"; } 

            else{ 
                confirmPasswordError.innerHTML = "✓ Password Matched"; 
                confirmPasswordError.style.color = "green"; 
            } 
        }); 
    } 
}); 

function validateRegister(){ 
    let password = 
    document.getElementById("password").value; 

    let confirmPassword = 
    document.getElementById("confirm_password").value; 

    if(password.length < 8){ 
        alert( "Password must be at least 8 characters" 
            ); 

        return false; 
    } 

    if(password !== confirmPassword){ 

        alert( "Password does not match" 
            ); 

        return false; 
    } return true; 
}