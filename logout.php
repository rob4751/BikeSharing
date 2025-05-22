<?php
session_start();
session_unset();     // Remove all session variables
session_destroy();   // Destroy the session completely

// Optional: redirect to login or home page
header("Location: index.php");
exit();
