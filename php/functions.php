<?php
$dir = __DIR__; 
require_once "$dir/Classes/W_Validator.php";
require_once "$dir/Classes/W_Message.php";
require_once "$dir/Middleware/EnsureUserAuth.php";
require_once "$dir/../config.php";
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'rumahsakit');


function register($data){
    global $conn;
    
    $nama = validateText($data['nama']);
    $email = validateText($data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $konfirmasi_password = mysqli_real_escape_string($conn, $data['konfirmasi_password']);
    

    // Validation
    $credentials = compact(['nama', 'email', 'password', 'konfirmasi_password']);
    $validator = new W_Validator($conn, $credentials, [
        "nama" => "required|min:3|max:255",
        "email" => "required|unique:tb_user",
        "password" => "required|min:3|max:255",
        "konfirmasi_password" => "required|min:3|max:255|same:password"
    ]);

    if($validator->fails()){
        return $validator->errors(); //type W_Error
    }
    

    $password = password_hash($password, PASSWORD_BCRYPT);
    $level = 'Admin'; //all user

    mysqli_query($conn, "INSERT INTO `tb_user` VALUES ('', '$nama', '$email', '$password', '$level');");
    return mysqli_affected_rows($conn);
}


function validateText($input){
    return htmlspecialchars(stripslashes(trim($input)));
}

function login($data){
    global $conn, $base_url;
    $email = validateText($data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);

    $credentials = compact(['email', 'password']);
    $validator = new W_Validator($conn, $credentials, [
        "email" => "required",
        "password" => "required|min:3|max:255"
    ]);

    if($validator->fails()){
        return $validator->errors();
    }


    $user_exist = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE email = '$email'");
    $user = mysqli_fetch_assoc($user_exist);

    if($user){
        if(password_verify($password, $user['password'])){
            $_SESSION['login'] = true; 
            $_SESSION['user'] = [
                "nama" => $user['nama'],
                "email" => $user['email']
            ];

            $token = uniqid('', true);
            $expired_days_count = 3;
            $user_id = $user['id'];

            $now = time();
            $expired_at = $now + (60 * 60 * 24) * $expired_days_count;

            $login_date = date("Y-m-d H:i:s", $now);
            $expiration_date = date("Y-m-d H:i:s", $expired_at);

            mysqli_query($conn, "INSERT INTO `tb_accesstoken` VALUES ('', '$user_id', '$token', '$login_date', '$expiration_date');");
            setcookie('token', $token, $expired_at, $base_url);

            return new W_Message("Login Berhasil!", 'success');
        }
    }

    return new W_Message("Login gagal, salah kredensial!", 'failed');

}


?>