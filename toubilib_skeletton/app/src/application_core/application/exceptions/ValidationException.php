<?php

namespace toubilib\core\application\exceptions;

class ValidationException extends \Exception{
    private array $errors;
    public function __construct(array $errors, String $message = "Validation échouer", int $code = 422){
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
}