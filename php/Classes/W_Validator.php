<?php 
require_once "W_ErrorValidator.php";

class W_Validator {
    private $conn, $errorsObject;
    function __construct($conn, $credentials, $rules){
        $this->conn = $conn;
        $this->credentials = $credentials;

        // rules key is input tag 'name', table 'field' if there is unique:tablename, and credentials 'equals key'.
        $this->rules = $rules;
        $this->errorsObject = new W_ErrorValidator('Error Fields');
        $this->errorsObject->setOld($this->credentials);
    }

    function validate(){
        foreach($this->rules as $key => $ruleStr){
            $ruleArr = explode('|', $ruleStr);
    
            $errorContainer = [];
    
            foreach($ruleArr as $rule){
    
                if($rule === 'required'){
                    $valid = $this->checkRequired($this->credentials[$key]);
                    if(!$valid) $errorContainer[] = "$key wajib diisi.";
                }

                if($rule === 'digit'){
                    $valid = $this->isDigit($this->credentials[$key]);
                    if(!$valid) $errorContainer[] = "$key harus angka";
                }
    
                if(str_contains($rule, ':')){
                    [$funcType, $param] = explode(':', $rule);
                    switch($funcType){
                        case 'min' : 
                            $valid = $this->checkMin($this->credentials[$key], $param);
                            if(!$valid) $errorContainer[] = "$key minimum $param huruf.";
                            break;
                        case 'max' : 
                            $valid = $this->checkMax($this->credentials[$key], $param);
                            if(!$valid) $errorContainer[] = "$key maximum $param huruf.";
                            break;
                        case 'unique' : 
                            $valid = $this->checkUnique($this->credentials[$key], $key, $param);
                            if(!$valid) $errorContainer[] = "$key sudah ada, pakai yang lain.";
                            break;
                        case 'same':
                            $valid = $this->checkSame($this->credentials[$key], $param);
                            if(!$valid) $errorContainer[] = "$key tidak sesuai dengan $param";
                            break;
                        case 'enum':
                            $valid = $this->enum($this->credentials[$key], $param);
                            $opts = explode(',', $param);
                            $opts = implode(' atau ', $opts);
                            if(!$valid) $errorContainer[] = "$key harus $opts";
                            break;
                        case 'in':
                            if(str_contains($param, ',')){
                                [$tableName, $fieldName] = explode(',', $param);
                            }else{
                                $tableName = $param;
                                $fieldName = 'id'; //default;
                            }

                            $valid = $this->in($this->credentials[$key], $tableName, $fieldName);
                            if(!$valid) $errorContainer[] = "$key tidak sesuai dengan relasi";
                            break;
                    }
                }
    
            }
    
            if(count($errorContainer) > 0){
                $this->errorsObject->setNewError($key, $errorContainer);
            }
        }
    }

    function fails(){
        $this->validate();
        return count($this->errorsObject->getErrors()) > 0;
    }
    
    function isDigit($data){
        return is_numeric($data);
    }

    function enum($data, $options){
        $opts = explode(',', $options);
        return in_array($data, $opts);
    }

    function in($data, $table, $field = 'id'){
        global $conn;

        if(gettype($data) == 'array'){
            foreach($data as $d){
                $data_contains = mysqli_query($conn, "SELECT * FROM `$table` WHERE `$field` = '$d'");
                if(mysqli_num_rows($data_contains) === 0){
                    return false;
                };
            }
            return true;
        }else{
            $data_contains = mysqli_query($conn, "SELECT * FROM `$table` WHERE `$field` = '$data'");
            return mysqli_num_rows($data_contains) > 0;
        }

    }

    function checkRequired($data){
        if(gettype($data) == 'array'){
            return count($data) !== 0;
        }
        return $data !== '';
    }

    function checkUnique($data, $field, $table){
        $res = mysqli_query($this->conn, "SELECT * FROM `$table` WHERE $field = '$data'");
        return mysqli_num_rows($res) === 0;
    }

    function checkMin($data, $length){
        return strlen($data) >= $length;
    }

    function checkMax($data, $length){
        return strlen($data) <= $length;
    }

    function checkSame($data, $keyToCheck){
        return $data === $this->credentials[$keyToCheck];
    }

    function errors(){
        return $this->errorsObject;
    }

    function validated(){
        return $this->credentials;
    }

}

?>