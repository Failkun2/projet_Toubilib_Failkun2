<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\praticien\RendezVous as RendezVous;

interface RendezVousRepositoryInterface{
    
    public function findRDVByPraticienPeriod(int $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array;

    public function findById(int $id) : RendezVous;
}