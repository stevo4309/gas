<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'joysmartgas@gmail.com'; // replace with your email
    $mail->Password = 'ouqf enfe hgoq btlk';    // replace with your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('joysmartgas@gmail.com', 'Test Sender');
    $mail->addAddress('kinyuastephenkahiga@gmail.com'); // replace with your recipient email

    $mail->isHTML(false);
    $mail->Subject = 'Test email from PHPMailer';
    $mail->Body = 'This is a test email sent via PHPMailer!';

    $mail->send();
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
