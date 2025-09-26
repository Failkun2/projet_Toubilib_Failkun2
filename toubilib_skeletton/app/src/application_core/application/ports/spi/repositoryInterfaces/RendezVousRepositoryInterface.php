<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\praticien\RendezVous as RendezVous;

interface RendezVousRepositoryInterface{
    
    public function findRDVByPraticienPeriod(int $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array;

    public function findById(int $id) : RendezVous;

    public function countOverlapping(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : int;

    public function createRdv(array $rdv) : String;

    public function updateStatut(String $id, RendezVous $rdv) : void;
}