<?php

namespace toubilib\core\application\ports\spi;

use PDO;
use toubilib\core\domain\entities\praticien\RendezVous as RendezVous;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;

class RendezVousRepository implements RendezVousRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findRDVByPraticienPeriod(int $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array{
        $stmt = $this->pdo->prepare("SELECT date_heure_debut, date_heure_fin 
        FROM rdv
        WHERE praticien_id = :praticien_id
        AND date_heure_debut >= :debut
        AND date_heure_fin <= :fin
        ORDER BY date_heure_debut ASC;");
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H-i-s'),
            'fin' => $fin->format('Y-m-d H-i-s')
        ]);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($rdv){
            return new RendezVous(
                new \DateTimeImmutable($rdv["date_heure_debut"]),
                new \DateTimeImmutable($rdv["date_heure_fin"])
            );
        }, $rendezVous);
    }

}