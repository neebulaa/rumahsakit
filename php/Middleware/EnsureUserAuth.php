<?php 

function EnsureUserAuth($conn, $page = 'index'){
    $dir = __DIR__;
    require_once "$dir/../../config.php";

    global $base_url;

    $isLogin = $_SESSION['login'] ?? CheckToken($conn, $base_url);
    $loginRoute = "$base_url/php/login.php";
    $indexRoute = $base_url;

    $guest = ['login', 'register'];

    if(in_array($page, $guest)){
        if($isLogin){
            header("Location: $indexRoute");
            exit();
        }
        // next
    }else{
        if(!$isLogin){
            header("Location: $loginRoute");
            exit();
        }
        // next
    }
}


function CheckToken($conn, $base_url){
    $token = $_COOKIE['token'] ?? null;

    if(!$token){
        return false;
    }

    $user_access = mysqli_query($conn, "SELECT tb_at.`expiration_date`, tb_u.`nama`, tb_u.`email`, tb_u.`level` FROM `tb_accesstoken` tb_at INNER JOIN `tb_user` tb_u ON tb_at.`token` = '$token' AND tb_at.`user_id` = tb_u.`id`");

    if($user = mysqli_fetch_assoc($user_access)){

        // checkexpiracy
        $expirationtime = mktime(...splitTimeStamp($user['expiration_date']));
        if(alreadyExpires(time(), $expirationtime)){
            mysqli_query($conn, "DELETE FROM `tb_accesstoken` WHERE `token` = $token"); //delete token record
            setcookie('token', '', time() - 3600, $base_url);
            return false;
        }

        $_SESSION['login'] = true;
        $_SESSION['user'] = [
            "nama" => $user['nama'],       
            "email" => $user['email'],       
        ];

        return true;
    }

    return false;
}

function splitTimeStamp($timestamp){
    [$ymd, $hms] = explode(' ', $timestamp);

    [$year, $month, $date] = explode('-', $ymd);
    [$hour, $minute, $second] = explode(':', $hms);

    return [$hour, $minute, $second, $month, $date, $year]; // format like mktime()
}

function alreadyExpires($start, $end){
    return $end - $start <= 0;
}


?>