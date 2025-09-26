<?php

namespace toubilib\core\application\exceptions;

class RendezVousInvalideException extends \Exception{
    private array $errors;
    public function __construct(String $message = "Le rendez-vous est invalide", int $code = 422){
        parent::__construct($message, $code);
    }
}