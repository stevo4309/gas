<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // <-- Make sure this path is correct

if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    die('Error: PHPMailer class not found. Check autoload path and Composer installation.');
}

function sendOrderEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'joysmartgas@gmail.com';
        $mail->Password = 'ouqf enfe hgoq btlk'; // Use an app password here
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('joysmartgas@gmail.com', 'Joy Smart Gas');
        $mail->addAddress($to);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
