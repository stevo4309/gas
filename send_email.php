<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer dependencies

function sendOrderEmail($to, $subject, $body) {
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
        // Display error message in the browser
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
