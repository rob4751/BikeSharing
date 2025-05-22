<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isMobile = preg_match('/(android|iphone|ipad|ipod|windows phone|blackberry|mobile)/i', $userAgent);

if ($isMobile) {
    header('Location: mobile.html');
    exit;
} else {
    header('Location: desktop.php');
    exit;
}
?>