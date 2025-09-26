<?php

namespace toubilib\core\domain\entities;

interface ServiceRendezVousInterface{
    public function listerCrenaux(int $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : array;

    public function creerRendezVous(InputRendezVousDTO $dto) : String;

    public function annulerRendezVous(String $idRdv) : void;

    public function consulterAgenda(int $praticienId, \DateTimeImmutable $debut = null, \DateTimeImmutable $fin = null) : array;
}