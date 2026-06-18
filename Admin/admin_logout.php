<?php
include '../includes/config.php';
unset($_SESSION['admin_logged']);
header("Location: admin_login.php");
exit;