<?php

namespace toubilib\core\domain\entities;

use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;

interface ConsulterRendezVousServiceInterface{
    public function afficherRendezVous(int $id) : RendezVousDTO;
}