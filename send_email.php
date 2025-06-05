<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOrderEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Enable verbose debug output for SMTP connection troubleshooting
        $mail->SMTPDebug = 2; // 0 = off, 1 = client messages, 2 = client and server messages
        $mail->Debugoutput = 'html';

        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joysmartgas@gmail.com'; // Your Gmail address
        $mail->Password   = 'ouqf enfe hgoq btlk';   // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('joysmartgas@gmail.com', 'Joy Smart Gas');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log detailed error message for debugging
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
