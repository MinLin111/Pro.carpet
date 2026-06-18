<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "db";
$user = "carpet_user";
$password = "carpet_pass";
$database = "carpet_shop";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Ошибка БД");
}

$conn->set_charset("utf8mb4");
 ?>