<?php

namespace toubilib\core\application\ports\spi;

use PDO;
use toubilib\core\domain\entities\praticien\Praticien as Praticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;

class PraticienRepository implements PraticienRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findPraticiens() : array{
        $stmt = $this->pdo->query("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite 
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle;");
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($praticien){
            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"]
            );
        }, $praticiens);
    }
    
    public function findById(int $id) : Praticien{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle, NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle, NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_it = mp.id
        WHERE p.id = :id
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['id' => $id]);
        $praticien = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Praticien(
            $praticien["nom"],
            $praticien["prenom"],
            $praticien["ville"],
            $praticien["email"],
            $praticien["specialite"],
            $praticien["telephone"],
            $praticien["adresse"],
            $praticien["motifs"],
            $praticien["moyens_paiement"]
        );
    }

}