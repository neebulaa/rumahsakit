<?php 

class W_ErrorValidator{
    private $errors = [], $old = [];
    public $message;

    function __construct($message = "Error Default"){
        $this->message = $message;
    }

    function setNewError($field, $messages){
        $this->errors[$field] = $messages;
    }

    function setOld($data){
        $this->old = $data;
    }

    function getErrors(){
        return $this->errors;
    }

    function old(){
        return $this->old;
    }

}

?>