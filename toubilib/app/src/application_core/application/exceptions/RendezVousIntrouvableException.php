<?php

namespace toubilib\core\application\exceptions;

class RendezVousIntrouvableException extends \Exception{
    private array $errors;
    public function __construct(String $message = "Le rendez-vous est introuvable", int $code = 422){
        parent::__construct($message, $code);
    }
}