<?php 
require_once "./Classes/W_Validator.php";
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
    
    // [$nama, $email, $password, $konfirmasi_password] = $validator->validated(); // well we dont need this because if the validation fails we return and if not then just continue.

    $password = password_hash($password, PASSWORD_BCRYPT);
    $level = 'Admin'; //all user

    mysqli_query($conn, "INSERT INTO `tb_user` VALUES ('', '$nama', '$email', '$password', '$level');");
    return mysqli_affected_rows($conn);
}


function validateText($input){
    return htmlspecialchars(stripslashes(trim($input)));
}

function login($data){
    global $conn;
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

}


?>