<?php

namespace toubilib\core\application\ports;

use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;

interface ServiceRendezVousInterface{
    public function listerCrenaux(String $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : array;

    public function creerRendezVous(InputRendezVousDTO $dto) : String;

    public function annulerRendezVous(String $idRdv) : void;

    public function consulterAgenda(String $praticienId, \DateTimeImmutable $debut = null, \DateTimeImmutable $fin = null) : array;
}