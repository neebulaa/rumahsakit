<?php
$dir = __DIR__; 
require_once "$dir/Classes/W_Validator.php";
require_once "$dir/Classes/W_Message.php";
require_once "$dir/Middleware/EnsureUserAuth.php";
require_once "$dir/../config.php";
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'rumahsakit');

// utils
function validateText($input){
    return htmlspecialchars(stripslashes(trim($input)));
}

function query($q){
    global $conn;
    $data = mysqli_query($conn, $q);
    $arr = [];
    while($a = mysqli_fetch_assoc($data)){
        $arr[] = $a;
    }
    return $arr;
}

function getCounts(...$tables){
    $query = "SELECT";

    foreach($tables as $index => $table){
        $query .= " (SELECT COUNT(*) FROM `$table`) AS '{$table}_count'";

        if($index != count($tables) - 1){
            $query .= ', ';
        }
    }
    
    return query($query);
}

function getPage(){
    global $base_url;
    $reqURI = getCurrentURI();
    $pageURI = explode($base_url, $reqURI);
    return $pageURI[1];
}

function getCurrentURI(){
    $uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $http_protocol = $_SERVER['SERVER_PORT'] === 443 ? "https://" : "http://";
    $uri = $http_protocol . $uri;
    return $uri;
}

function formatMultipleFormData($data, $formCount) {
    $formatted = [];

    // -1 karena ada key pas klik buttonnya
    $fieldPerForm = (count($data) - 1) / $formCount;
    for($i = 0; $i < $formCount; $i++){
        $formatted[] = array_slice($data, $i * $fieldPerForm, $fieldPerForm);
    }
    return $formatted;
}

function w_validator_errors_merge($arrayOfWErrors){
    $formatted = [];
    foreach($arrayOfWErrors as $WError){
        foreach($WError->getErrors() as $key => $error){
            $formatted['errors'][$key] = $error;
        }

        foreach($WError->old() as $key => $old){
            $formatted['old'][$key] = $old;
        }
    }
    return $formatted;
}

function normalizeKeys($datas) {
    $records = [];
    foreach($datas as $row){
        $arr = [];
        foreach($row as $key => $col){
            // ex: nama_dokter--1 => "Edwin Hendly"
            [$key, $prefix] = explode('--', $key);
            $arr[$key] = $col;
        }        
        $records[] = $arr;
    }
    return $records;
}

function w_validator_errors_contains_errors($errors){
    return count(array_filter($errors, fn($e) => count($e->getErrors()) > 0)) > 0;
}


// auth
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


            if(isset($data['remember-me'])){
                $user_id = $user['id'];
                $token = uniqid('', true) . (string) time();

                $expired_days_count = 3;
                $now = time();
                $expired_at = $now + (60 * 60 * 24) * $expired_days_count;
                $login_date = date("Y-m-d H:i:s", $now);
                $expiration_date = date("Y-m-d H:i:s", $expired_at);
                mysqli_query($conn, "INSERT INTO `tb_accesstoken` VALUES ('', '$user_id', '$token', '$login_date', '$expiration_date');");

                setcookie('token', $token, time() + (60 * 60 * 24 * 365), $base_url); 
            }

            return new W_Message("Login Berhasil!", 'success');
        }
    }

    return new W_Message("Login gagal, salah kredensial!", 'failed');

}


// CRUD
function search($keyword, $table){
    $query = "SELECT * FROM `$table` WHERE
    `nama_dokter` LIKE '%$keyword%' OR
    `spesialis` LIKE '%$keyword%' OR
    `alamat` LIKE '%$keyword%' OR
    `no_telp` LIKE '%$keyword%'";

    return query($query);
}

function searchWithLimit($keyword, $start, $limit, $table){
    $query = "SELECT * FROM `$table` WHERE
    `nama_dokter` LIKE '%$keyword%' OR
    `spesialis` LIKE '%$keyword%' OR
    `alamat` LIKE '%$keyword%' OR
    `no_telp` LIKE '%$keyword%'
    LIMIT $start, $limit
    ";

    return query($query);
}


function add($datas, $table){
    global $conn;

    $errors = [];
    foreach($datas as $index => $data){

        $data = array_map(function($d){ 
            return validateText($d); 
        }, $data);

        $dataKey = $index + 1;
        $validator = new W_Validator($conn, $data, [
            "nama_dokter--$dataKey" => "required|min:3|max:255",
            "spesialis--$dataKey" => "required|min:3|max:255",
            "alamat--$dataKey" => "required|min:8|max:255",
            "no_telp--$dataKey" => "required|digit|min:10|max:12"
        ]);

        if($validator->fails()){
            $errors[] = $validator->errors();
        }
    }

    if(count($errors) > 0){
        $forms_error = new W_ErrorValidator('Forms Error');
        $forms_error->setNewError('forms_error', $errors);
        return $forms_error;
    }

    $query = "INSERT INTO `$table` VALUES ";

    foreach($datas as $index => $data){
        $dataKey = $index + 1;
        $nama_dokter = $data["nama_dokter--$dataKey"];
        $spesialis = $data["spesialis--$dataKey"];
        $alamat = $data["alamat--$dataKey"];
        $no_telp = $data["no_telp--$dataKey"];

        $query .= "('', '$nama_dokter', '$spesialis', '$alamat', '$no_telp')";
        if($index != count($datas) - 1){
            $query .= ", ";
        }
    }

    mysqli_query($conn, $query);

    if(mysqli_affected_rows($conn) > 0){
        return new W_Message('Data baru berhasil ditambahkan!', 'success');
    }else{
        return new W_Message('Data gagal ditambahkan!', 'failed');
    }
}

function edit($datas, $table){
    global $conn;

    $errors = [];
    foreach($datas as $index => $data){

        $data = array_map(function($d){ 
            return validateText($d); 
        }, $data);

        $dataKey = $index + 1;
        $validator = new W_Validator($conn, $data, [
            "nama_dokter--$dataKey" => "required|min:3|max:255",
            "spesialis--$dataKey" => "required|min:3|max:255",
            "alamat--$dataKey" => "required|min:10|max:255",
            "no_telp--$dataKey" => "required|digit|min:8|max:15"
        ]);

        if($validator->fails()){
            $errors[] = $validator->errors();
        }
    }

    if(count($errors) > 0){
        $forms_error = new W_ErrorValidator('Forms Error');
        $forms_error->setNewError('forms_error', $errors);
        return $forms_error;
    }

    foreach($datas as $index => $data){
        $dataKey = $index + 1;
        $id = $data["id--$dataKey"];
        $nama_dokter = $data["nama_dokter--$dataKey"];
        $spesialis = $data["spesialis--$dataKey"];
        $alamat = $data["alamat--$dataKey"];
        $no_telp = $data["no_telp--$dataKey"];

        $query = "UPDATE `$table` SET 
            nama_dokter = '$nama_dokter', 
            spesialis = '$spesialis', 
            alamat = '$alamat', 
            no_telp = '$no_telp'
            WHERE id = $id;
        ";
        mysqli_query($conn, $query);
    }
     

    // if(mysqli_affected_rows($conn) > 0){
    return new W_Message('Data berhasil diubah!', 'success');
    // }
    // else{
    //     return new W_Message('Data gagal diubah!', 'failed');
    // }
}

function delete($ids){
    global $conn;
    $query = "DELETE FROM `tb_dokter` WHERE ";

    foreach($ids as $index => $id){
        $query .= "id = $id";
        if($index != count($ids) - 1){
            $query .= " OR ";
        }
    }

    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) > 0){
        return new W_Message("Data berhasil di hapus!", "success");
    }else{
        return new W_Message("Data gagal di hapus!", "failed");
    }
}


?>