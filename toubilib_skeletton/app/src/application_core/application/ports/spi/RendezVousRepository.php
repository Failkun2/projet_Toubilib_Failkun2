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

    public function countOverlapping(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : int{
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS cnt
        FROM rdv
        WHERE praticien_id = :id
        AND NOT(
            date_heure_debut <= :debut
            OR date_heure_fin >= :fin
        )");
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H-i-s'),
            'fin' => $fin->format('Y-m-d H-i-s')
        ]);
        $rendezVous = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($rendezvous['cnt'] ?? 0);
    }

    public function createRdv(array $rdv) : String{
        $stmt = $this->pdo->prepare("INSERT INTO rdv
        VALUES(:id, :praticienId, :patientId, :patientEmail, :dateDebut, :statut, :duree, :dateFin, :dateCreation, :motifVisite)");

        $stmt->execute([
            'id' => $rdv['id'],
            'praticienId' => $rdv['praticienId'],
            'patientId' => $rdv['patientId'],
            'patientEmail' => $rdv['patientEmail'] ?? null,
            'dateDebut' => $rdv['dateDebut']->format('Y-m-d H:i:s'),
            'statut' => $rdv['statut'],
            'duree' => $rdv['duree'],
            'dateFin' => $rdv['dateFin']->format('Y-m-d H:i:s'),
            'dateCreation' => $rdv['dateCreation']->format('Y-m-d H:i:s'),
            'motifVisite' => $rdv['motifVisite']
        ]);

        return $rdv['id'];
    }
        
    public function updateStatut(String $id, RendezVous $rdv) : void{
        $stmt = $this->pdo->prepare("UPDATE rdv
        SET status = :statut
        WHERE id = :id");
        $stmt->execute([
            'statut' => $rdv->__get("statut"),
            'id' => $id
        ]);
    }

}