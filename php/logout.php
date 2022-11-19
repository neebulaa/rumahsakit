<?php
require_once "./functions.php";

session_unset();
session_destroy();
$_SESSION = [];
$token = $_COOKIE['token'];
setcookie('token', '', time() - 3600, $base_url);
mysqli_query($conn, "DELETE FROM `tb_accesstoken` WHERE token = '$token'");

header('Location: ./login.php');
exit();
?>