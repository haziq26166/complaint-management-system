<?php

session_start();

include 'db.php';


// ============================
// REGISTER
// ============================

if(isset($_POST['register'])){

    $full_name =
    trim($_POST['full_name']);

    $email =
    trim($_POST['email']);

    $phone_number =
    trim($_POST['phone_number']);

    $apartment_no =
    trim($_POST['apartment_no']);

    $password_raw =
    $_POST['password'];


    // SERVER SIDE VALIDATION

    if(empty($full_name)){

        die("Full Name is required");

    }

    if(!filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )){

        die("Invalid Email Format");

    }

    if(strlen($password_raw) < 8){

        die("Password must be at least 8 characters");

    }


    // CHECK DUPLICATE EMAIL

    $checkEmail =
    mysqli_query(
        $conn,
        "SELECT * FROM resident
        WHERE email='$email'"
    );

    if(mysqli_num_rows($checkEmail) > 0){

        echo "
        <script>

            alert('Email already registered');

            window.location.href =
            '../auth/register.html';

        </script>
        ";

        exit();

    }


    // HASH PASSWORD

    $password =
    password_hash(
        $password_raw,
        PASSWORD_DEFAULT
    );


    // INSERT DATA

    $sql =
    "INSERT INTO resident
    (
        full_name,
        email,
        phone_number,
        password,
        apartment_no
    )
    VALUES
    (
        '$full_name',
        '$email',
        '$phone_number',
        '$password',
        '$apartment_no'
    )";


    if(mysqli_query($conn,$sql)){

        header(
            "Location: ../auth/login.html"
        );

        exit();

    }

    else{

        echo "
        <script>

            alert(
            'Email already registered'
            );

            window.location.href =
            '../auth/register.html';

        </script>
        ";

        exit();

    }

}



// ============================
// LOGIN
// ============================

if(isset($_POST['login'])){

    $email =
    trim($_POST['email']);

    $password =
    $_POST['password'];


    $query =
    mysqli_query(
        $conn,
        "SELECT * FROM resident
        WHERE email='$email'"
    );


    if(mysqli_num_rows($query) == 1){

        $row =
        mysqli_fetch_assoc($query);


        if(
            password_verify(
                $password,
                $row['password']
            )
        ){

            $_SESSION['residentID']
            =
            $row['residentID'];

            $_SESSION['full_name']
            =
            $row['full_name'];

            $_SESSION['email']
            =
            $row['email'];


            header(
                "Location: ../index.html"
            );

            exit();

        }

        else{

            echo "
            <script>

                alert('Incorrect Password');

                window.location.href =
                '../auth/login.html';

            </script>
            ";

            exit();

        }

    }

    else{

        echo "
        <script>

            alert('User Not Found');

            window.location.href =
            '../auth/login.html';

        </script>
        ";

        exit();

    }

}

?>
