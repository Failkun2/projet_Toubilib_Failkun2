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

    public function findRDVByPraticienPeriod(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array{
        $stmt = $this->pdo->prepare("SELECT date_heure_debut, date_heure_fin, duree, status, motif_visite, date_creation 
        FROM rdv
        WHERE praticien_id = :praticien_id
        AND date_heure_debut >= :debut
        AND date_heure_fin <= :fin
        ORDER BY date_heure_debut ASC;");
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
        $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($rdv){
            
            return new RendezVous(
                new \DateTimeImmutable($rdv["date_heure_debut"]),
                new \DateTimeImmutable($rdv["date_heure_fin"]),
                $rdv["duree"],
                $rdv["status"],
                $rdv["motif_visite"],
                new \DateTimeImmutable($rdv["date_creation"])
            );
        }, $rendezVous);
    }

    public function findById(String $id): RendezVous{
        try{
            $stmt = $this->pdo->prepare("SELECT date_heure_debut, date_heure_fin, duree, status, motif_visite, date_creation, praticien_id 
            FROM rdv
            WHERE id = :id");
            $stmt->execute(['id' => $id,]);
            $rendezVous = $stmt->fetch(PDO::FETCH_ASSOC);
            return new RendezVous(
                new \DateTimeImmutable($rendezVous["date_heure_debut"]),
                new \DateTimeImmutable($rendezVous["date_heure_fin"]),
                (int)$rendezVous['duree'],
                (int)$rendezVous['status'],
                $rendezVous['motif_visite'],
                new \DateTimeImmutable($rendezVous["date_creation"]),
                $rendezVous['praticien_id'],
            );
        } catch(\RuntimeException $e){
            throw new \RuntimeException("Aucun rdv trouvé avec l'id : $id");
        }catch(\Throwable $e){
            throw $e;
        }
    }

    public function countOverlapping(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : int{
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS cnt
        FROM rdv
        WHERE praticien_id = :praticien_id
        AND date_heure_debut < :fin
        AND date_heure_fin > :debut");
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
        $rendezVous = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($rendezVous['cnt'] ?? 0);
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


    public function findAgendaByPraticien(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array{
        $stmt = $this->pdo->prepare("SELECT id, praticien_id, patient_id, date_heure_debut, date_heure_fin, duree, status, motif_visite
        FROM rdv
        WHERE praticien_id = :praticienId
        AND date_heure_debut BETWEEN :debut AND :fin
        ORDER BY date_heure_debut ASC");
        $stmt->execute([
            'praticienId' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findHistoriqueByPatient(String $patientId): array{
        $stmt = $this->pdo->prepare("SELECT id, praticien_id, patient_id, date_heure_debut, date_heure_fin, duree, status, motif_visite
        FROM rdv
        WHERE patient_id = :patientId
        ORDER BY date_heure_debut ASC");
        $stmt->execute([
            'patientId' => $patientId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addIndisponibilite(String $id, String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : void{
        $stmt = $this->pdo->prepare("INSERT INTO indisponibilite (id, praticien_id, date_heure_debut, date_heure_fin)
        VALUES(:id, :praticienId, :debut, :fin)");

        $stmt->execute([
            'id' => $id,
            'praticienId' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
    }

    public function verifierIndisponibilite(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : int{
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS cnt
        FROM indisponibilite
        WHERE praticien_id = :praticien_id
        AND date_heure_debut < :fin
        AND date_heure_fin > :debut");
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
        $indisponibilite = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($indisponibilite['cnt'] ?? 0);
    }
}