<?php

namespace toubilib\core\domain\entities;

interface ServiceRendezVousInterface{
    public function listerCrenaux(int $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : array;

    public function creerRendezVous(InputRendezVousDTO $dto) : String;
}