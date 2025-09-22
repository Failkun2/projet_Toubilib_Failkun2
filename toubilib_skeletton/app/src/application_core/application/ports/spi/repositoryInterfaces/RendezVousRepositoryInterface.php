<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface RendezVousRepositoryInterface{
    
    public function findRDVByPraticienPeriod(int $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array;
}