<?php 

class W_Error{
    private $errors = [];
    public $messages;

    function setNewError($field, $messages){
        $this->errors[$field] = $messages;
    }

    function getErrors(){
        return $this->errors;
    }
}

?>