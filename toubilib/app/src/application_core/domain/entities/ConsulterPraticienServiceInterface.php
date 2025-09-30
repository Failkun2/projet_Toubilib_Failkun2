<?php

namespace toubilib\core\domain\entities;

use toubilib\core\application\ports\api\dtos\PraticienDTO as PraticienDTO;

interface ConsulterPraticienServiceInterface{
    public function afficherPraticien(int $id) : PraticienDTO;
}