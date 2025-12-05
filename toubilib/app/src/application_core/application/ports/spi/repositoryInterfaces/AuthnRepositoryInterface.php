<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\User as User;

interface AuthnRepositoryInterface{
    public function findUserByEmail(String $email) : ?User;
    public function findById(String $id) : ?User;
    public function creerUser(string $id, array $data) : void;
    public function userExiste(String $email) : bool;
}