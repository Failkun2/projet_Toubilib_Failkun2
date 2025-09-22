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

    public function findById(int $id): RendezVous{
        $stmt = $this->pdo->prepare("SELECT date_heure_debut, date_heure_fin, duree, statut, motif_visite, date_creation 
        FROM rdv
        WHERE id = :id");
        $stmt->execute(['id' => $id,]);
        $rendezVous = $stmt->fetch(PDO::FETCH_ASSOC);
        return new RendezVous(
            new \DateTimeImmutable($rendezVous["date_heure_debut"]),
            new \DateTimeImmutable($rendezVous["date_heure_fin"]),
            (int)$rendezVous['duree'],
            (int)$rendezVous['statut'],
            $rendezVous['motif_visite'],
            new \DateTimeImmutable($rendezVous["date_creation"])
        );
    }

}