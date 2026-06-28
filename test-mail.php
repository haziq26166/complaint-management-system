<?php

require_once "utils/mailer.php";

$result = sendMail(
    "tryocompany@gmail.com",
    "Test User",
    "PHPMailer Testing",
    "<h2>Congratulations 🎉</h2>
    <p>PHPMailer is working successfully.</p>"
);

if($result){

    echo "Email sent successfully.";

}else{

    echo "Failed to send email.";

}

?>