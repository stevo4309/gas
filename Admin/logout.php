<?php
session_start();
session_unset(); // Optional but safe
session_destroy();
header("Location: index.php"); // Change this if you want to redirect elsewhere
exit;
?>
