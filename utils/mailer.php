<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendMail($recipientEmail, $recipientName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        
        // CHANGE THESE TO YOUR EMAIL AND APP PASSWORD
        $mail->Username = 'nurazleenahsidek@gmail.com';
        $mail->Password = 'iwnn hgmj ucvn otxl'; // App password
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
        $mail->setFrom('nurazleenahsidek@gmail.com', 'Residential Complaint Management System');

        // Receiver
        $mail->addAddress($recipientEmail, $recipientName);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mail error: " . $e->getMessage());
        return false;
    }
}
?>