<?php
echo "Step 1: Starting test...<br>";

// STEP 2: Check if Composer autoload file exists
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("❌ ERROR: Composer autoload file not found at: $autoloadPath<br>");
}

require $autoloadPath;
echo "✅ Step 2: Autoload file loaded<br>";

// STEP 3: Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
echo "✅ Step 3: Namespace imported<br>";

// STEP 4: Check if PHPMailer class exists
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    die("❌ ERROR: PHPMailer class not found. Make sure it's installed by Composer.<br>");
}

echo "✅ Step 4: PHPMailer class exists<br>";

// STEP 5: Try creating an instance
try {
    $mail = new PHPMailer(true);
    echo "✅ Step 5: PHPMailer instance created successfully<br>";
} catch (Exception $e) {
    echo "❌ ERROR: Failed to create PHPMailer instance: " . $e->getMessage() . "<br>";
}
