<?php

namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\domain\entities\praticien\Praticien as Praticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;

class PraticienRepository implements PraticienRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findPraticiens() : array{
        $stmt = $this->pdo->query("SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        GROUP BY p.id, p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($praticien){

            $stmt2 = $this->pdo->prepare("SELECT mv.libelle
            FROM praticien2motif pm
            JOIN motif_visite mv ON pm.motif_id = mv.id
            WHERE pm.praticien_id = :id");
            $stmt2->execute(['id' => $praticien["id"]]);
            $motifsArray = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            $stmt3 = $this->pdo->prepare("SELECT mp.libelle
            FROM praticien2moyen pm
            JOIN moyen_paiement mp ON pm.moyen_id = mp.id
            WHERE pm.praticien_id = :id");
            $stmt3->execute(['id' => $praticien["id"]]);
            $moyensArray = array_column($stmt3->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }
    
    public function findById(String $id) : Praticien{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        WHERE p.id = :id
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['id' => $id]);
        $praticien = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $this->pdo->prepare("SELECT mv.libelle
        FROM praticien2motif pm
        JOIN motif_visite mv ON pm.motif_id = mv.id
        WHERE pm.praticien_id = :id");
        $stmt2->execute(['id' => $id]);
        $motifsArray = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'libelle');

        $stmt3 = $this->pdo->prepare("SELECT mp.libelle
        FROM praticien2moyen pm
        JOIN moyen_paiement mp ON pm.moyen_id = mp.id
        WHERE pm.praticien_id = :id");
        $stmt3->execute(['id' => $id]);
        $moyensArray = array_column($stmt3->fetchAll(PDO::FETCH_ASSOC), 'libelle');

        return new Praticien(
            $praticien["nom"],
            $praticien["prenom"],
            $praticien["ville"],
            $praticien["email"],
            $praticien["specialite"],
            $praticien["telephone"],
            $praticien["adresse"],
            $motifsArray,
            $moyensArray
        );
    }

    public function findMotifsByPraticien(String $praticienId) : array{
        $stmt = $this->pdo->prepare("SELECT m.id, m.libelle
        FROM motif_visite m JOIN praticien2motif pm ON m.id = pm.motif_id
        WHERE pm.praticien_id = :praticien_id;");
        $stmt->execute(['praticien_id' => $praticienId]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($praticien) => [
            'id' => (int)$praticien['id'],
            'libelle' => (String)$praticien['libelle'],
        ], $praticiens);
    }

    public function findBySpecialite(String $specialite) : array{

        $stmt = $this->pdo->prepare("SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        WHERE s.libelle = :specialite
        GROUP BY p.id, p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['specialite' => $specialite]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($praticien){
            $stmt2 = $this->pdo->prepare("SELECT mv.libelle
            FROM praticien2motif pm
            JOIN motif_visite mv ON pm.motif_id = mv.id
            WHERE pm.praticien_id = :id");
            $stmt2->execute(['id' => $praticien["id"]]);
            $motifsArray = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            $stmt3 = $this->pdo->prepare("SELECT mp.libelle
            FROM praticien2moyen pm
            JOIN moyen_paiement mp ON pm.moyen_id = mp.id
            WHERE pm.praticien_id = :id");
            $stmt3->execute(['id' => $praticien["id"]]);
            $moyensArray = array_column($stmt3->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

    public function findByVille(String $ville) : array{

        $stmt = $this->pdo->prepare("SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        WHERE p.ville = :ville
        GROUP BY p.id, p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['ville' => $ville]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($praticien){
            $stmt2 = $this->pdo->prepare("SELECT mv.libelle
            FROM praticien2motif pm
            JOIN motif_visite mv ON pm.motif_id = mv.id
            WHERE pm.praticien_id = :id");
            $stmt2->execute(['id' => $praticien["id"]]);
            $motifsArray = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            $stmt3 = $this->pdo->prepare("SELECT mp.libelle
            FROM praticien2moyen pm
            JOIN moyen_paiement mp ON pm.moyen_id = mp.id
            WHERE pm.praticien_id = :id");
            $stmt3->execute(['id' => $praticien["id"]]);
            $moyensArray = array_column($stmt3->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

    public function findBySpecialiteVille(String $specialite, String $ville) : array{

        $stmt = $this->pdo->prepare("SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        WHERE s.libelle = :specialite
        AND p.ville = :ville
        GROUP BY p.id, p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['specialite' => $specialite, 'ville' => $ville]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($praticien){
            $stmt2 = $this->pdo->prepare("SELECT mv.libelle
            FROM praticien2motif pm
            JOIN motif_visite mv ON pm.motif_id = mv.id
            WHERE pm.praticien_id = :id");
            $stmt2->execute(['id' => $praticien["id"]]);
            $motifsArray = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            $stmt3 = $this->pdo->prepare("SELECT mp.libelle
            FROM praticien2moyen pm
            JOIN moyen_paiement mp ON pm.moyen_id = mp.id
            WHERE pm.praticien_id = :id");
            $stmt3->execute(['id' => $praticien["id"]]);
            $moyensArray = array_column($stmt3->fetchAll(PDO::FETCH_ASSOC), 'libelle');

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

}