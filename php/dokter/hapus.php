<?php 
require_once "../functions.php";

EnsureUserAuth($conn, 'php/dokter/hapus.php');

if(count($_POST) <= 0 || $_SERVER['REQUEST_METHOD'] === "GET") {
    echo "
        <script>
            alert('Silakan pilih data terlebih dahulu!');
            document.location.href = './index.php';
        </script>
    ";
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $query = "DELETE FROM `tb_dokter` WHERE ";
    $ids = array_values($_POST);
    $result = delete($ids);

    if($result->status === 'success'){
        header("Location: ./index.php");
        exit();
    }
}