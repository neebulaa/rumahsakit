<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dir = __DIR__; 
require "$dir/../phpmailer/src/Exception.php";
require "$dir/../phpmailer/src/PHPMailer.php";
require "$dir/../phpmailer/src/SMTP.php";


require_once "$dir/Classes/W_Validator.php";
require_once "$dir/Classes/W_Message.php";
require_once "$dir/Middleware/EnsureUserAuth.php";
require_once "$dir/../config.php";
require_once "$dir/table_init.php";

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

function getTableFields($table){
    global $tables;
    $fields = $tables[$table];

    $formatted = [];
    foreach($fields as $field => $type){
        if($type === 'id'){
            $formatted['id'] = $field;
        }else if($type === 'basic' || str_contains($type, "foreign")){
            $formatted['fields'][] = $field;
            if(str_contains($type, "foreign")){
                $table = explode(':', $type)[1];
                $formatted['foreign'][$field] = $table; 
            }
        }
    }

    return [$formatted['id'], $formatted['fields'], $formatted['foreign'] ?? []];
}


function getTableRules($table){
    global $tablesRules;
    $rules = $tablesRules[$table];
    return $rules;
}


// auth
function sendMail($email, $code){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'edwin.003@ski.sch.id';
    $mail->Password = 'zymhxmurljojteeb';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('edwin.003@ski.sch.id');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Email Verification";
    $mail->Body = "Your verification code is: <h2><b>$code</b></h2>";
    if($mail->send()){
        return true;
    }else{
        return false;
    }
}

function alreadyVerify($conn, $email){
    $user = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE email = '$email' AND `email_verified_at` NOT NULL");
    return mysqli_num_rows($user) > 0;
}

function verify($data){
    global $conn;
    $verification_code = $data['verification_code'];

    // validation
    $validator = new W_Validator($conn, compact('verification_code'), [
        "verification_code" => "required|digit|min:6|max:6"
    ]);

    if($validator->fails()){
        return $validator->errors();
    }

    $user_exist = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE `verification_code` = '$verification_code'");
    $user = mysqli_fetch_assoc($user_exist);

    $currentTimeStamp = date("Y-m-d H:i:s", time());
    mysqli_query($conn, "UPDATE `tb_user` SET `email_verified_at` = '$currentTimeStamp' WHERE `verification_code` = '$verification_code' AND `email_verified_at` IS NULL");

    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['login'] = true; 
        $_SESSION['user'] = [
            "nama" => $user['nama'],
            "email" => $user['email']
        ];
        return new W_Message('Verifikasi kode valid!', 'success');
    }else{
        return new W_Message('Verifikasi kode salah!', 'failed');
    }
}

function register($data){
    global $conn;
    
    $nama = validateText($data['nama']);
    $email = validateText($data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $konfirmasi_password = mysqli_real_escape_string($conn, $data['konfirmasi_password']);
    $verification_code = $data['verification_code'];

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

    mysqli_query($conn, "INSERT INTO `tb_user` VALUES ('', '$nama', '$email', '$password', '$level', '$verification_code', NULL);");
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

    // check user already verified
    if($user['email_verified_at'] === NULL){
        return new W_Message('Email ini belum terverifikasi!', 'need-verify');
    }

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
function search($keyword, $table, $withLimit = false){
    [$tbId, $tbFields, $tbForeign] = getTableFields($table);

    $query = "SELECT * FROM `$table` WHERE ";
    foreach($tbFields as $index => $field){
        $query .= "$field LIKE '%$keyword%'";

        if($index !== count($tbFields) - 1){
            $query .= " OR ";
        }
    }

    if(!$withLimit){
        return query($query);
    }else{
        return $query;
    }
}

function searchWithLimit($keyword, $start, $limit, $table){
    $query = search($keyword, $table, true);
    $query .= " LIMIT $start, $limit";
    return query($query);

}

function validateHasError($datas, $tableRules){
    global $conn;
    $errors = [];
    foreach($datas as $index => $data){

        $data = array_map(function($d){ 
            if(gettype($d) === 'array'){
                return array_map(fn($s) => validateText($s), $d);
            }else{
                return validateText($d); 
            }
        }, $data);

        $dataKey = $index + 1;

        $rulesFormatted = [];
        foreach($tableRules as $field => $rules){
            $rulesFormatted["$field--$dataKey"] = $rules;
        }
        
        $validator = new W_Validator($conn, $data, $rulesFormatted);

        $validator->fails();
        $errors[] = $validator->errors();
    }

    if(w_validator_errors_contains_errors($errors)){
        $forms_error = new W_ErrorValidator('Forms Error');
        $forms_error->setNewError('forms_error', $errors);
        return $forms_error;
    }
}

function add($datas, $table){
    global $conn;

    [$tbId, $tbFields, $tbForeign] = getTableFields($table);
    $tableRules = getTableRules($table);

    if($res = validateHasError($datas, $tableRules)){
        return $res;
    }

    $onlyFields = implode(', ', $tbFields);
    $query = "INSERT INTO `$table` ($onlyFields) VALUES ";

    foreach($datas as $index => $data){
        $dataKey = $index + 1;

        $statement = "(";
        foreach($tbFields as $fi => $f){
            $val = $data["$f--$dataKey"];
            $statement .= "'$val'";

            if($fi !== count($tbFields) - 1){
                $statement .= ", ";
            }
        }
        $statement .= ")";

        $query .= $statement;
        if($index != count($datas) - 1){
            $query .= ", ";
        }
    }
    // var_dump($query);
    // die();

    mysqli_query($conn, $query);

    if(mysqli_affected_rows($conn) > 0){
        return new W_Message('Data baru berhasil ditambahkan!', 'success');
    }else{
        return new W_Message('Data gagal ditambahkan!', 'failed');
    }
}

function edit($datas, $table){
    global $conn;
    
    [$tbId, $tbFields, $tbForeign] = getTableFields($table);
    $tableRules = getTableRules($table);

    if($res = validateHasError($datas, $tableRules)){
        return $res;
    }

    foreach($datas as $index => $data){
        $dataKey = $index + 1;

        $query = "UPDATE `$table` SET ";
        foreach($tbFields as $fi => $f){
            $dataField = $data["$f--$dataKey"];
            $query .= "$f = '$dataField'";

            if($fi !== count($tbFields) - 1){
                $query .= ', ';
            }
        }
        $id = $data["$tbId--$dataKey"];
        $query .= " WHERE `$tbId` = $id";

        mysqli_query($conn, $query);
    }
     

    // if(mysqli_affected_rows($conn) > 0){
    return new W_Message('Data berhasil diubah!', 'success');
    // }
    // else{
    //     return new W_Message('Data gagal diubah!', 'failed');
    // }
}

function delete($ids, $table){
    global $conn;
    [$tbId, $tbFields, $tbForeign] = getTableFields($table);

    $query = "DELETE FROM `$table` WHERE ";

    foreach($ids as $index => $id){
        $query .= "`$tbId` = $id";
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


// rekam medis
function addRekamMedis($datas, $table){
    global $conn;
    
    [$tbId, $tbFields, $tbForeign] = getTableFields($table);
    $tableRules = getTableRules($table);

    $newDatas = [];
    foreach($datas as $d){
        $obatIds = array_filter(end($d), fn($id) => $id !== '');
        $d[array_key_last($d)] = $obatIds;
        $newDatas[] = $d;
    }
    $datas = $newDatas;

    if($res = validateHasError($datas, $tableRules)){
        return $res;
    }
    
    $onlyFields = implode(', ', $tbFields);
    foreach($datas as $index => $data){
        $query = "INSERT INTO `$table` ($onlyFields) VALUES ";
        $dataKey = $index + 1;

        $statement = "(";
        foreach($tbFields as $fi => $f){
            $val = $data["$f--$dataKey"];
            $statement .= "'$val'";

            if($fi !== count($tbFields) - 1){
                $statement .= ", ";
            }
        }
        $statement .= ")";

        $query .= $statement;
        mysqli_query($conn, $query);

        $inserted_id = mysqli_insert_id($conn);

        foreach($data["id_obat--$dataKey"] as $id){
            mysqli_query($conn, "INSERT INTO `tb_rekammedis_obat` (id_rekammedis, id_obat) VALUES ('$inserted_id', '$id')");
        }
    }
    
    
    if(mysqli_affected_rows($conn) > 0){
        return new W_Message('Data baru berhasil ditambahkan!', 'success');
    }else{
        return new W_Message('Data gagal ditambahkan!', 'failed');
    }
}

function editRekamMedis($datas, $table){
    global $conn;
    
    [$tbId, $tbFields, $tbForeign] = getTableFields($table);
    $tableRules = getTableRules($table);

    $newDatas = [];
    foreach($datas as $d){
        $obatIds = array_filter(end($d), fn($id) => $id !== '');
        $d[array_key_last($d)] = $obatIds;
        $newDatas[] = $d;
    }
    $datas = $newDatas;

    if($res = validateHasError($datas, $tableRules)){
        return $res;
    }

    foreach($datas as $index => $data){
        $dataKey = $index + 1;

        $query = "UPDATE `$table` SET ";
        foreach($tbFields as $fi => $f){
            $dataField = $data["$f--$dataKey"];
            $query .= "$f = '$dataField'";

            if($fi !== count($tbFields) - 1){
                $query .= ', ';
            }
        }
        $id_rm = $data["$tbId--$dataKey"];
        $query .= " WHERE `$tbId` = $id_rm";

        mysqli_query($conn, $query);

        

        // delete all rekammedis_obat
        mysqli_query($conn, "DELETE FROM `tb_rekammedis_obat` WHERE `id_rekammedis` = '$id_rm'");

        foreach($data["id_obat--$dataKey"] as $id){
            mysqli_query($conn, "INSERT INTO `tb_rekammedis_obat` (id_rekammedis, id_obat) VALUES ('$id_rm', '$id')");
        }
    }
     

    // if(mysqli_affected_rows($conn) > 0){
    return new W_Message('Data berhasil diubah!', 'success');
    // }
    // else{
    //     return new W_Message('Data gagal diubah!', 'failed');
    // }
}

function searchRekamMedis($keyword, $table, $withLimit = false){
    global $tableRelations;

    [$tbId, $tbFields, $tbForeign] = getTableFields($table);

    $tablesToSearch = array_values($tbForeign);
    $tablesToSearch[] = 'tb_obat';


    $searched_values = [];
    foreach($tablesToSearch as $tb){
        $res = search($keyword, $tb);
        $res_id = array_map(fn($d) => $d['id'], $res);

        $tbType = explode('tb_', $tb);
        $tbId = implode('id_', $tbType);

        $searched_values[$tbId] = $res_id;
    };


    // search obat
    $obat_searched_values = $searched_values['id_obat'];
    if(count($obat_searched_values) > 0){
        $queryToTakeObat = 'SELECT `tb_rekammedis_obat`.`id_rekammedis` FROM `tb_rekammedis_obat` WHERE ';
        foreach($obat_searched_values as $oidx => $osv){
            $queryToTakeObat .= "`id_obat` = '$osv'";
    
            if($oidx !== count($obat_searched_values) - 1){
                $queryToTakeObat .= " OR ";
            }
        }

        $rm_ids = array_unique(array_map(fn($qo) => $qo['id_rekammedis'], query($queryToTakeObat)));
    }

    unset($searched_values['id_obat']);

    // search 3 other tables (dokter, pasien, klinik)
    $query = "SELECT `$table`.*, ";
    $queryToTake = " WHERE 
    `$table`.`keluhan` LIKE '%$keyword%' OR
    `$table`.`tgl_periksa` LIKE '%$keyword%' OR
    `$table`.`diagnosa` LIKE '%$keyword%'";

    foreach($searched_values as $tableId => $searched_value){
        foreach($searched_value as $idValue){
            $queryToTake .= " OR `$table`.`$tableId` = $idValue";
        }
    }

    foreach(($rm_ids ?? []) as $rm_id){
        $queryToTake .= " OR `tb_rekammedis`.`id` = $rm_id";
    }



    $tbRelation = $tableRelations[$table] ?? false;
    if($tbRelation){
        [$tbsToRelate, $tbsFieldToGet] = [array_keys($tbRelation), array_values($tbRelation)];
        [$tbId, $tbFields, $tbForeign] = getTableFields($table);
        $tbForeign = array_flip($tbForeign);

        $tbSelectionStr = "";
        $tbJoinStr = "";

        foreach($tbsToRelate as $tIdx => $tableRelate) {
            [$tbIdToRelate] = getTableFields($tableRelate);
            foreach($tbsFieldToGet[$tIdx] as $fIdx => $fieldToGet){
                // selection field name and 'as'
                $fieldName = $fieldToGet;
                if(str_contains($fieldName, ':')){
                    [$fieldName, $fieldNameAs] = explode(':', $fieldToGet);
                }else{
                    $fieldNameAs = $fieldName;
                }
                $tbSelectionStr .= "`$tableRelate`.`$fieldName` as `$fieldNameAs`";

                if($tIdx !== count($tbsToRelate) - 1){
                    $tbSelectionStr .= ",";
                }
                $tbSelectionStr .= " ";
            }

            $tbForeignId = $tbForeign[$tableRelate];
            $tbJoinStr .= " INNER JOIN `$tableRelate` ON `$table`.`$tbForeignId` = `$tableRelate`.`$tbIdToRelate`";
        }

        $query .= $tbSelectionStr;
        $query .= "FROM `$table` ";
        $query .= $tbJoinStr;
        $query .= $queryToTake;
        $query .= " ORDER BY `$table`.id";
    }

    // var_dump($query);
    // die();


    if(!$withLimit){
        return query($query);
    }else{
        return $query;
    }

}

function searchRekamMedisWithLimit($keyword, $start, $limit, $table){

    $query = searchRekamMedis($keyword, $table, true);
    $query .= " LIMIT $start, $limit";
    return query($query);

}




?>