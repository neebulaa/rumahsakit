<?php 
require_once "../functions.php";

EnsureUserAuth($conn, 'php/poliklinik/hapus.php');
$current_table = 'tb_poliklinik';

if(count($_POST) <= 0 || $_SERVER['REQUEST_METHOD'] === "GET") {
    $_SESSION['process-failed'] = "Silakan pilih data terlebih dahulu!";
    header("Location: ./index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $ids = array_values($_POST);
    $result = delete($ids, $current_table);

    if($result->status === 'success'){
        $_SESSION['process-success'] = $result->message;
        header("Location: ./index.php");
        exit();
    }else{
        $_SESSION['process-failed'] = $result->message;
        header("Location: ./index.php");
        exit();
    }
}