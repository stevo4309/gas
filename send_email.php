<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Use absolute path to Composer autoload
require __DIR__ . '/vendor/autoload.php'; 

// Optional: check if PHPMailer class is loaded, for debugging
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    die('Error: PHPMailer class not found. Check autoload path and Composer installation.');
}

function sendOrderEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'joysmartgas@gmail.com'; // Your Gmail address
        $mail->Password = 'ouqf enfe hgoq btlk';    // Your app password, NOT Gmail password
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
