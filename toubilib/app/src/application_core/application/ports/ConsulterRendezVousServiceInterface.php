<?php

namespace toubilib\core\application\ports;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;

interface ConsulterRendezVousServiceInterface{
    public function afficherRendezVous(String $id) : array;
}