<?php

namespace toubilib\core\application\ports;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;

interface AuthnServiceInterface{
    public function byCredentials(CredentialsDTO $credentials) : ?ProfileDTO;
    public function findById(String $id) : ?ProfileDTO;
}