<?php
require __DIR__ . '/vendor/autoload.php';

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer is available!";
} else {
    echo "❌ PHPMailer class NOT found!";
}
