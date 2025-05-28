<?php
// Load Composer dependencies
require __DIR__ . '/vendor/autoload.php'; // Corrected path with absolute reference

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOrderEmail($to, $subject, $body) {
    // Check if PHPMailer is available
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        die('Error: PHPMailer class not found. Check autoload path and Composer installation.');
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'joysmartgas@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'ouqf enfe hgoq btlk';    // Use an App Password (not your Gmail password)
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('joysmartgas@gmail.com', 'Joy Smart Gas');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo "Message sent successfully!";
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
